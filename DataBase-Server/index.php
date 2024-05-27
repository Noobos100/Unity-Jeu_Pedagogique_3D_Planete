<?php

include_once 'data/DataAccess.php';
use data\DataAccess;

include_once 'control/ControllerGame.php';
include_once 'control/ControllerInteractions.php';
include_once 'control/ControllerQuestions.php';
use control\{ControllerGame, ControllerQuestions, ControllerInteractions};

include_once 'service/PartieChecking.php';
include_once 'service/DataAccessInterface.php';
include_once 'service/CannotDoException.php';

use service\{PartieChecking, CannotDoException};

include_once 'gui/Layout.php';
include_once 'gui/ViewRandomQuestion.php';
include_once 'gui/ViewInteractions.php';
include_once 'gui/ViewPartie.php';
include_once 'gui/ViewQuestions.php';
include_once 'gui/ViewHome.php';
include_once 'gui/ViewLogin.php';
include_once 'gui/ViewModifyQuestion.php';
include_once 'gui/ViewManageQuestions.php';
include_once 'gui/ViewGame.php';

use gui\{Layout,ViewInteractions,ViewManageQuestions,ViewModifyQuestion,ViewPartie,ViewQuestions,ViewRandomQuestion,ViewHome,ViewLogin,ViewGame};


session_start();

$data = null;
try {
    $data = new DataAccess(new \PDO('mysql:host=mysql-jeupedagogique.alwaysdata.net;dbname=jeupedagogique_bd', '331395_jeu_pedag', 'Planete-T3rr3'));

} catch (PDOException $e) {
    print "Erreur de connexion !: " . $e->getMessage() . "<br/>";
    die();
}

// initialisation des controllers
$controllerGame = new ControllerGame();
$controllerInte = new ControllerInteractions();
$controllerQuestions = new ControllerQuestions();

// initilisation du cas d'utilisation PartieChecking
$partieChecking = new PartieChecking();


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if($uri == '/game' || '/' == $uri){
    $layout = new Layout('gui/layout.html');
    $viewPartie = new ViewGame($layout);

    $viewPartie->display();
}

// Vérifier si l'utilisateur est connecté, sauf pour la page de connexion
elseif (!($uri == '/index.php') && (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true)) {
    header('Status: 404 Not Found');
    echo '<html><body><h1>Page Not Found</h1>';
    echo '<button onclick="window.location.href=\'/index.php\'">Retour à la page d\'accueil</button>';
    echo '</body></html>';
}

elseif ('/index.php' == $uri ) {
    $layout = new Layout('gui/layout.html');
    $viewLogin = new ViewLogin($layout);
    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['password'] = $_POST['password'];

        if ($data->utilisateur($_SESSION['username'], $_SESSION['password'])) {
            $_SESSION['loggedin'] = true;
            header('Location: /index.php/home');
            exit;
        } else {
            $error = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }

    if ($error) {
        echo '<p style="color: red;">' . htmlspecialchars($error) . '</p>';
    }
    $viewLogin->display();

} elseif ('/index.php/home' == $uri && (isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == true)) {
    $layout = new Layout('gui/layout.html');
    $viewPartie = new ViewHome($layout);
    $viewPartie->display();

} elseif ('/index.php/addInteraction' == $uri) {
    if (isset($_GET["type"]) && isset($_GET["value"]) && isset($_GET["isEval"])) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $type = $_GET["type"];
        $value = $_GET["value"];
        $isEval = $_GET["isEval"];
        $dateInteract = date('Y-m-d H:i:s');

        try {
            $controllerInte->addInteration($type, (float)$value, $isEval, $ip, $dateInteract, $partieChecking, $data);
        } catch (CannotDoException $e) {
            $report = $e->getReport();
            $report = str_replace('\n', '<br />', $report);
            echo '<p>', $report, '</p>';
        }
        $interaction = "$ip, $type";

        $layout = new Layout("gui/layout.html");
        $viewInterac = new ViewInteractions($layout, $interaction);

        $viewInterac->display();
    } else {
        echo "URL not complete, cannot register new interaction.";
    }
} elseif ('/index.php/abortOnGoingGame' == $uri) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $controllerGame->abortPartie($ip, $partieChecking, $data);

    $partieStatus = "Partie abandonnée";
    $date = date('Y-m-d H:i:s');
    $layout = new Layout('gui/layout.html');
    $viewPartie = new ViewPartie($layout, $partieStatus, $ip, $date);

    $viewPartie->display();
} elseif ('/index.php/NewGame' == $uri) {
    if (isset($_GET['plateforme'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $plateforme = $_GET['plateforme'];
        $date = date('Y-m-d H:i:s');
        try {
            $controllerGame->newPlayer($ip, $plateforme, $partieChecking, $data);
        } catch (CannotDoException $e) {
            $report = $e->getReport();
            $report = str_replace('\n', '<br />', $report);
            echo '<p>', $report, '</p>';
        }
        try {
            $controllerGame->newPartie($ip, $date, $partieChecking, $data);
        } catch (CannotDoException $e) {
            $report = $e->getReport();
            $report = str_replace('\n', '<br />', $report);
            echo '<p>', $report, '</p>';
        }

        $partieStatus = "Nouvelle partie";
        $layout = new Layout('gui/layout.html');
        $viewPartie = new ViewPartie($layout, $partieStatus, $ip, $date);

        $viewPartie->display();
    } else {
        echo "URL not complete, cannot register new player or game.";
    }
} elseif ('/index.php/QuestionAnswer' == $uri) {
    $ip = $_SERVER['REMOTE_ADDR'];

    if (isset($_GET['qid']) && $data->verifyPartieInProgress($ip) && isset($_GET['correct']) && isset($_GET['start'])) {
        $dateFin = date('Y-m-d H:i:s');
        $dateDeb = $_GET['start'];
        $controllerQuestions->addFinishedQuestion($_GET['qid'], $data->getPartieInProgress($ip)->getIdPartie(), $dateDeb, $dateFin, $_GET['correct'], $partieChecking, $data);
    } else {
        echo "URL not complete, cannot add question answer to database";
    }
} elseif ('/index.php/endGame' == $uri) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $date = date('Y-m-d H:i:s');

    try {
        $controllerGame->endPartie($ip, $date, $partieChecking, $data);

        $partieStatus = "Fin de partie";
        $layout = new Layout('gui/layout.html');
        $viewPartie = new ViewPartie($layout, $partieStatus, $ip, $date);

        $viewPartie->display();
    } catch (CannotDoException $e) {
        $report = $e->getReport();
        $report = str_replace('\n', '<br />', $report);
        echo '<p>', $report, '</p>';
    }
} elseif ('/index.php/question' == $uri) {
    if (isset($_GET['qid'])) {
        $jsonQ = $controllerQuestions->getJsonAttributesQ($_GET['qid'], $partieChecking, $data);
    } else {
        // display all questions
        $jsonQ = $controllerQuestions->getJsonAttributesAllQ($partieChecking, $data);
    }
    $layout = new Layout('gui/layoutJson.html');
    $viewQuestion = new ViewQuestions($layout, $jsonQ);

    $viewQuestion->display();
} elseif ('/index.php/ManageQuestions' == $uri && $_SESSION['loggedin']){
    $layout = new Layout('gui/layout.html');
    $questions = $controllerQuestions->getJsonAttributesAllQ($partieChecking, $data);

    $viewManageQ = new ViewManageQuestions($layout, $questions);
    $viewManageQ->display();
} elseif ('/index.php/ModifyQuestion' == $uri && $_SESSION['loggedin']) {
    if (isset($_GET['qid'])) {
        $questionData = $controllerQuestions->getJsonAttributesQ($_GET['qid'], $partieChecking, $data);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($questionData['Type'] == 'QCU') {
                $question = $_POST['question'];
                $option1 = $_POST['option1'];
                $option2 = $_POST['option2'];
                $option3 = $_POST['option3'];
                $option4 = $_POST['option4'];
                $correct = $_POST['correct'];

                $controllerQuestions->updateQCU($questionData['Num_Ques'], $question, $option1, $option2, $option3, $option4, $correct, $partieChecking, $data);
            } elseif ($questionData['Type'] == 'QUESINTERAC') {
                $question = $_POST['question'];
                $orbit = $_POST['orbit'];
                $rotation = $_POST['rotation'];

                $controllerQuestions->updateQInterac($questionData['Num_Ques'], $question, $orbit, $rotation, $partieChecking, $data);
            } elseif ($questionData['Type'] == 'VRAIFAUX') {
                $question = $_POST['question'];
                $correct = $_POST['correct'];

                $controllerQuestions->updateQVraiFaux($questionData['Num_Ques'], $question, $correct, $partieChecking, $data);
            }
        }

        $layout = new Layout('gui/layoutJson.html');
        $viewModifyQ = new ViewModifyQuestion($layout, $questionData);

        $viewModifyQ->display();
    } else {
        echo "URL not complete, cannot modify question.";
    }
} elseif ('/index.php/randomQuestions' == $uri) {
    $nbQCU = $_GET['qcu'] ?? 0;
    $nbInteraction = $_GET['interaction'] ?? 0;
    $nbVraiFaux = $_GET['vraifaux'] ?? 0;

    $jsonRandQ = $controllerQuestions->getJsonRandomQs($partieChecking, $data, $nbQCU, $nbInteraction, $nbVraiFaux);

    $layout = new Layout('gui/layoutJson.html');
    $viewRandomQs = new ViewRandomQuestion($layout, $jsonRandQ);

    $viewRandomQs->display();
} else {
	session_destroy();
    header('Status: 404 Not Found');
    echo '<html><body><h1>Page Not Found</h1>';
    echo '<button onclick="window.location.href=\'/index.php\'">Retour à la page d\'accueil</button>';
    echo '</body></html>';
}
