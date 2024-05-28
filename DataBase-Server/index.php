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
include_once 'gui/ViewLogin.php';
include_once 'gui/ViewModifyQuestion.php';
include_once 'gui/ViewManageQuestions.php';
include_once 'gui/ViewGame.php';
include_once 'gui/ViewTypesJoueur.php';
include_once 'gui/ViewParties.php';

use gui\{Layout,ViewInteractions,ViewParties,ViewTypesJoueur,ViewManageQuestions,ViewModifyQuestion,ViewPartie,ViewQuestions,ViewRandomQuestion,ViewUtilisateur,ViewLogin,ViewGame};


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

if($uri == '/') {
	header('Location: /game');
} elseif($uri == '/game'){
    $layout = new Layout('gui/layout.html');
    $viewPartie = new ViewGame($layout);
    $viewPartie->display();
} elseif ('/logout' == $uri) {
    session_destroy();
    header('Location: /');
    exit;
} elseif ('/login' == $uri ) {
    $layout = new Layout('gui/layout-login.html');
    $viewLogin = new ViewLogin($layout);
    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['password'] = $_POST['password'];

        if ($data->utilisateur($_SESSION['username'], $_SESSION['password'])) {
            $_SESSION['loggedin'] = true;
            header('Location: /game-data');
            exit;
        } else {
            $error = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }

    if ($error) {
        echo '<p style="color: red;">' . htmlspecialchars($error) . '</p>';
    }
    $viewLogin->display();
} elseif ('/game-data' == $uri && (isset($_SESSION['loggedin']) )) {
    $layout = new Layout('gui/layout.html');
    $viewPartie = new ViewParties($layout);
    $viewPartie->display();


} elseif ('/players' == $uri && (isset($_SESSION['loggedin']) )) {
    $layout = new Layout('gui/layout.html');
    $viewPartie = new ViewTypesJoueur($layout);
    $viewPartie->display();

} elseif ('/add-interaction' == $uri) {
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
} elseif ('/abort-on-going-game' == $uri) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $controllerGame->abortPartie($ip, $partieChecking, $data);

    $partieStatus = "Partie abandonnée";
    $date = date('Y-m-d H:i:s');
    $layout = new Layout('gui/layout.html');
    $viewPartie = new ViewPartie($layout, $partieStatus, $ip, $date);

    $viewPartie->display();
} elseif ('/new-game' == $uri) {
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
} elseif ('/question-answer' == $uri) {
    $ip = $_SERVER['REMOTE_ADDR'];

    if (isset($_GET['qid']) && $data->verifyPartieInProgress($ip) && isset($_GET['correct']) && isset($_GET['start'])) {
        $dateFin = date('Y-m-d H:i:s');
        $dateDeb = $_GET['start'];
        $controllerQuestions->addFinishedQuestion($_GET['qid'], $data->getPartieInProgress($ip)->getIdPartie(), $dateDeb, $dateFin, $_GET['correct'], $partieChecking, $data);
    } else {
        echo "URL not complete, cannot add question answer to database";
    }
} elseif ('/end-game' == $uri) {
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
} elseif ('/question' == $uri ) {
    if (isset($_GET['qid'])) {
        $jsonQ = $controllerQuestions->getJsonAttributesQ($_GET['qid'], $partieChecking, $data);
    } else {
        // display all questions
        $jsonQ = $controllerQuestions->getJsonAttributesAllQ($partieChecking, $data);
    }
    $layout = new Layout('gui/layoutJson.html');
    $viewQuestion = new ViewQuestions($layout, $jsonQ);

    $viewQuestion->display();
} elseif ('/manage-questions' == $uri && (isset($_SESSION['loggedin']) )){
    $layout = new Layout('gui/layout.html');
    $questions = $controllerQuestions->getJsonAttributesAllQ($partieChecking, $data);

    $viewManageQ = new ViewManageQuestions($layout, $questions);
    $viewManageQ->display();

} elseif ('/modify-question' == $uri && (isset($_SESSION['loggedin']) )) {
    if (isset($_GET['qid'])) {
        $questionData = $controllerQuestions->getJsonAttributesQ($_GET['qid'], $partieChecking, $data);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $questionData = json_decode($questionData, true);

            if ($questionData["Type"] == 'QCU') {
                $question = $_POST['question'];
                $option1 = $_POST['option1'];
                $option2 = $_POST['option2'];
                $option3 = $_POST['option3'];
                $option4 = $_POST['option4'];
                $correct = $_POST['correct'];

                $controllerQuestions->updateQQCU($questionData["Num_Ques"], $question, $option1, $option2, $option3, $option4, $correct, $partieChecking, $data);
                echo '<script>
                        alert("Changements sauvegardés.");
                        window.location.reload();
                    </script>';
            }
            elseif ($questionData['Type'] == 'QUESINTERAC') {
                $question = $_POST['question'];
                $orbit = $_POST['orbit'];
                $rotation = $_POST['rotation'];
                $controllerQuestions->updateQInterac($questionData['Num_Ques'], $question, $orbit, $rotation, $partieChecking, $data);
                echo '<script>
                        alert("Changements sauvegardés.");
                        window.location.reload();
                    </script>';
            } elseif ($questionData['Type'] == 'VRAIFAUX') {
                $question = $_POST['question'];
                $correct = $_POST['answer'];
                $orbit = ($_POST['orbit'] ?? '-1');
                $rotation = ($_POST['rotation'] ?? '-1');

                $controllerQuestions->updateQVraiFaux($questionData['Num_Ques'], $question, $orbit, $rotation, $correct, $partieChecking, $data);
                echo '<script>
                        alert("Changements sauvegardés.");
                        window.location.reload();
                    </script>';
            }
        }

        $layout = new Layout('gui/layout.html');
        $viewModifyQ = new ViewModifyQuestion($layout, $questionData);

        $viewModifyQ->display();
    } else {
        echo "URL not complete, cannot modify question.";
    }
} elseif ('/random-questions' == $uri) {
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
    echo '<button onclick="window.location.href=\'/\'">Retour à la page d\'accueil</button>';
    echo '</body></html>';
}
