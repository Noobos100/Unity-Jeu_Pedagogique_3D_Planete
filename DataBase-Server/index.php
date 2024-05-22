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
use service\{PartieChecking};

include_once 'gui/Layout.php';
include_once 'gui/ViewRandomQuestion.php';
include_once 'gui/ViewInteractions.php';
include_once 'gui/ViewPartie.php';
include_once 'gui/ViewQuestions.php';
include_once 'gui/ViewHome.php';
use gui\{Layout, ViewInteractions, ViewPartie, ViewQuestions, ViewRandomQuestion, ViewHome};

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

if ('/index.php/addInteraction' == $uri) {

    if (isset($_GET["type"]) && isset($_GET["value"]) && isset($_GET["isEval"])) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $type = $_GET["type"];
        $value = $_GET["value"];
        $isEval = $_GET["isEval"];
        $dateInteract = date('Y-m-d H:i:s');

        try {
            $controllerInte->addInteration($type, (float)$value, $isEval, $ip, $dateInteract, $partieChecking, $data);
        } catch (\service\CannotDoException $e) {
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
}
else if ( '/index.php/abortOnGoingGame' == $uri){

    $ip = $_SERVER['REMOTE_ADDR'];
    $controllerGame->abortPartie($ip, $partieChecking, $data);

    $partieStatus = "Partie abandonnée";
    $date = date('Y-m-d H:i:s');
    $layout = new Layout('gui/layout.html');
    $viewPartie = new ViewPartie($layout, $partieStatus, $ip, $date);

    $viewPartie->display();
}
else if ( '/index.php' == $uri){

    $layout = new Layout('gui/layout.html');
    $viewPartie = new ViewHome($layout);

    $viewPartie->display();
}
elseif ( '/index.php/NewGame' == $uri){

    if (isset($_GET['plateforme'])){
        $ip = $_SERVER['REMOTE_ADDR'];
        $plateforme = $_GET['plateforme'];
        $date = date('Y-m-d H:i:s');
        try{
            $controllerGame->newPlayer($ip, $plateforme, $partieChecking, $data);
        } catch (\service\CannotDoException $e){
            $report = $e->getReport();
            $report = str_replace( '\n', '<br />', $report );
            echo '<p>', $report, '</p>';
        }
        try{
            $controllerGame->newPartie($ip, $date, $partieChecking, $data);
        } catch (\service\CannotDoException $e){
            $report = $e->getReport();
            $report = str_replace( '\n', '<br />', $report );
            echo '<p>', $report, '</p>';
        }

        $partieStatus = "Nouvelle partie";
        $layout = new Layout('gui/layout.html');
        $viewPartie = new ViewPartie($layout, $partieStatus, $ip, $date);

        $viewPartie->display();

    }
    else{
        echo "URL not complete, cannot register new player or game.";
    }
}
elseif ( '/index.php/QuestionAnswer' == $uri){

    $ip = $_SERVER['REMOTE_ADDR'];

    if (isset($_GET['qid']) && $data->verifyPartieInProgress($ip) && isset($_GET['correct']) && isset($_GET['start'])){
        $dateFin = date('Y-m-d H:i:s');
        $dateDeb = $_GET['start'];
        $controllerQuestions->addFinishedQuestion($_GET['qid'], $data->getPartieInProgress($ip)->getIdPartie(), $dateDeb, $dateFin, $_GET['correct'], $partieChecking, $data);
    }
    else{
        echo "URL not complete, cannot add question answer to database";
    }
}
elseif ( '/index.php/endGame' == $uri){
    $ip = $_SERVER['REMOTE_ADDR'];
    $date = date('Y-m-d H:i:s');

    try{
        $controllerGame->endPartie($ip, $date, $partieChecking, $data);

        $partieStatus = "Fin de partie";
        $layout = new Layout('gui/layout.html');
        $viewPartie = new ViewPartie($layout, $partieStatus, $ip, $date);

        $viewPartie->display();
    } catch (\service\CannotDoException $e){
        $report = $e->getReport();
        $report = str_replace( '\n', '<br />', $report );
        echo '<p>', $report, '</p>';
    }

}
elseif ( '/index.php/question' == $uri){

    // See if there is an indicated question in the url
    if (isset($_GET['qid'])){
        $jsonQ = $controllerQuestions->getJsonAttributesQ($_GET['qid'], $partieChecking, $data);

        $layout = new Layout('gui/layoutJson.html');
        $viewQuestion = new ViewQuestions($layout, $jsonQ);

        $viewQuestion->display();
    }
    else{
        echo "URL not complete, cannot show question attributes";
    }
}
elseif ( '/index.php/randomQuestions' == $uri){

// Determining how many questions of each type to retrieve
    if (isset($_GET['qcu'])){
        $nbQCU = $_GET['qcu'];
    }
    else{
        $nbQCU = 0;
    }
    if (isset($_GET['interaction'])){
        $nbInteraction = $_GET['interaction'];
    }
    else{
        $nbInteraction = 0;
    }
    if (isset($_GET['vraifaux'])){
        $nbVraiFaux = $_GET['vraifaux'];
    }
    else{
        $nbVraiFaux = 0;
    }

    $jsonRandQ = $controllerQuestions->getJsonRandomQs($partieChecking, $data, $nbQCU, $nbInteraction, $nbVraiFaux);
    
    $layout = new Layout('gui/layoutJson.html');
    $viewRandomQs = new ViewRandomQuestion($layout, $jsonRandQ);

    $viewRandomQs->display();
}
else {
    header('Status: 404 Not Found');
    echo '<html><body><h1>Page Not Found</h1></body></html>';
}