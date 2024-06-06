<?php

include_once 'data/DataAccess.php';
use data\DataAccess;

include_once 'control/ControllerGame.php';
include_once 'control/ControllerInteractions.php';
include_once 'control/ControllerQuestions.php';
include_once 'control/ControllerPlayers.php';
include_once 'control/ControllerGameData.php';
use control\{ControllerGame, ControllerQuestions, ControllerInteractions, ControllerPlayers, ControllerGameData};

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
include_once 'gui/ViewManageQuestions.php';
include_once 'gui/ViewGame.php';
include_once 'gui/ViewPlayer.php';
include_once 'gui/ViewGameData.php';

use gui\{Layout,ViewInteractions,ViewGameData,ViewPlayer,ViewManageQuestions,ViewPartie,ViewQuestions,ViewRandomQuestion,ViewLogin,ViewGame};

include_once 'gui/pages/question/_qcu.php';
include_once 'gui/pages/question/_vraifaux.php';
include_once 'gui/pages/question/_questionInteractive.php';

include_once 'gui/pages/game/_score.php';

session_start();

$data = null;
try {
    $data = DataAccess::createFromConfig();
} catch (PDOException $e) {
    print "Erreur de connexion !: " . $e->getMessage() . "<br/>";
    die();
}

// initialisation des controllers
$controllerGame = new ControllerGame();
$controllerInte = new ControllerInteractions();
$controllerQuestions = new ControllerQuestions();
$controllerPlayers = new ControllerPlayers();
$controllerGameData = new ControllerGameData();

// initilisation du cas d'utilisation PartieChecking
$partieChecking = new PartieChecking();


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if('/' == $uri) {
	header('Location: /game');
}
// Interface public
elseif('/game' == $uri){
	$layout = new Layout('gui/layout.html');
	$viewPartie = new ViewGame($layout, $controllerGameData, $data);
	$viewPartie->display();
}
elseif('/get-score' == $uri){
	$score = new _score($data);
	echo $score->render();
}
elseif ('/logout' == $uri) {
	session_destroy();
	header('Location: /');
	exit;
}
elseif ('/login' == $uri ) {
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
}
// Gestion des interactions
elseif ('/add-interaction' == $uri) {
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
}
elseif ('/abort-on-going-game' == $uri) {
	$ip = $_SERVER['REMOTE_ADDR'];
	$controllerGame->abortPartie($ip, $partieChecking, $data);

	$partieStatus = "Partie abandonnée";
	$date = date('Y-m-d H:i:s');
	$layout = new Layout('gui/layout.html');
	$viewPartie = new ViewPartie($layout, $partieStatus, $ip, $date);

	$viewPartie->display();
}
elseif ('/new-game' == $uri) {
	if (isset($_GET['plateforme']) && isset($_GET['username'])) {
		$ip = $_SERVER['REMOTE_ADDR'];
		$plateforme = $_GET['plateforme'];
		$date = date('Y-m-d H:i:s');
		$username = $_GET['username'];
		try {
			$controllerGame->newPlayer($ip, $plateforme, $username, $partieChecking, $data);
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
}
elseif ('/question-answer' == $uri) {
	$ip = $_SERVER['REMOTE_ADDR'];

	if (isset($_GET['qid']) && $data->verifyPartieInProgress($ip) && isset($_GET['answer']) && isset($_GET['start'])) {
		$dateFin = date('Y-m-d H:i:s');
		$dateDeb = $_GET['start'];
		$controllerQuestions->addFinishedQuestion($_GET['qid'], $data->getPartieInProgress($ip)->getIdPartie(), $dateDeb, $dateFin, $_GET['answer'], $partieChecking, $data);
	} else {
		echo "URL not complete, cannot add question answer to database";
	}
}
elseif ('/end-game' == $uri) {
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
}
elseif ('/question' == $uri ) {
	if (isset($_GET['qid'])) {
		$jsonQ = $controllerQuestions->getJsonAttributesQ($_GET['qid'], $partieChecking, $data);
	} else {
		// display all questions
		$jsonQ = $controllerQuestions->getJsonAttributesAllQ($partieChecking, $data);
	}
	$layout = new Layout('gui/layoutJson.html');
	$viewQuestion = new ViewQuestions($layout, $jsonQ);

	$viewQuestion->display();
}
elseif ('/random-questions' == $uri) {
	$nbQCU = $_GET['qcu'] ?? 0;
	$nbInteraction = $_GET['interaction'] ?? 0;
	$nbVraiFaux = $_GET['vraifaux'] ?? 0;

	$jsonRandQ = $controllerQuestions->getJsonRandomQs($partieChecking, $data, $nbQCU, $nbInteraction, $nbVraiFaux);

	$layout = new Layout('gui/layoutJson.html');
	$viewRandomQs = new ViewRandomQuestion($layout, $jsonRandQ);

	$viewRandomQs->display();
}
// Interface administrateur
elseif ('/game-data' == $uri && (isset($_SESSION['loggedin']) )) {
	$layout = new Layout('gui/layout.html');
	$viewPartie = new ViewGameData($layout, $controllerGameData, $data);
	$viewPartie->display();


}
elseif ('/players' == $uri && (isset($_SESSION['loggedin']) )) {
	$layout = new Layout('gui/layout.html');
	$viewPartie = new ViewPlayer($layout, $controllerPlayers, $data);
	$viewPartie->display();

}
elseif ('/manage-questions' == $uri && (isset($_SESSION['loggedin']) )){
	$layout = new Layout('gui/layout.html');
	$questions = $controllerQuestions->getJsonAttributesAllQ($partieChecking, $data);

	$viewManageQ = new ViewManageQuestions($layout, $questions);
	$viewManageQ->display();

}
elseif ('/modify-question' == $uri && (isset($_SESSION['loggedin']) )) {
	if (isset($_GET['qid'])) {
		$questionData = $controllerQuestions->getJsonAttributesQ($_GET['qid'], $partieChecking, $data);

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$questionData = json_decode($questionData, true);

			if ($questionData["Type"] == 'QCU') {
				$reponse = areQcuInputGood($_POST['question'], $_POST['option1'], $_POST['option2'], $_POST['option3'], $_POST['option4'], $_POST['answer']);
				if ($reponse) {
					return;
				}

				$question = $_POST['question'];
				$option1 = $_POST['option1'];
				$option2 = $_POST['option2'];
				$option3 = $_POST['option3'];
				$option4 = $_POST['option4'];
				$correct = $_POST['answer'];

				$controllerQuestions->updateQQCU($questionData["Num_Ques"], $question, $option1, $option2, $option3, $option4, $correct, $partieChecking, $data);
			}
			elseif ($questionData['Type'] == 'QUESINTERAC') {
				$reponse = areQuesInteracInputGood($_POST['question'], $_POST['orbit'], $_POST['rotation'], $_POST['margin-rotation'], $_POST['margin-orbit']);
				if ($reponse) {
					return;
				}

				$question = $_POST['question'];
				$orbit = ($_POST['orbit'] ?? '-1');
				$rotation = ($_POST['rotation'] ?? '-1');
				$rotationMargin = ($_POST['margin-orbit'] ?? '-1');
				$orbitMargin = ($_POST['margin-rotation'] ?? '-1');

				$controllerQuestions->updateQInterac($questionData['Num_Ques'], $question, $orbit, $rotation, $rotationMargin, $orbitMargin, $partieChecking, $data);
			}
			elseif ($questionData['Type'] == 'VRAIFAUX') {
				$reponse = areVraiFauxInputGood($_POST['question'], $_POST['answer'], $_POST['orbit'], $_POST['rotation']);
				if ($reponse) {
					return;
				}

				$question = $_POST['question'];
				$correct = $_POST['answer'];
				$orbit = ($_POST['orbit'] ?? '-1');
				$rotation = ($_POST['rotation'] ?? '-1');

				$controllerQuestions->updateQVraiFaux($questionData['Num_Ques'], $question, $orbit, $rotation, $correct, $partieChecking, $data);
			}
		}
	} else {
		echo "URL not complete, cannot modify question.";
	}
}
elseif ('/delete-question' == $uri && (isset($_SESSION['loggedin']) )) {
	if (isset($_GET['qid'])) {
		$controllerQuestions->deleteQuestion($_GET['qid'], $partieChecking, $data);
	} else {
		echo "URL not complete, cannot delete question.";
	}
}
elseif ('/add-question' == $uri && (isset($_SESSION['loggedin']) )) {
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$type = $_POST['type'];
		$question = $_POST['question'] ?? "";

		if ($type == 'VRAIFAUX') {
			$response = areVraiFauxInputGood($question, $_POST['answer'], $_POST['orbit'], $_POST['rotation']);
			if ($response) {
				return;
			}

			$correct = $_POST['answer'];
			$orbit = $_POST['orbit'] != "" ? $_POST['orbit'] : null;
			$rotation = $_POST['rotation'] != "" ? $_POST['rotation'] : null;

			$controllerQuestions->addQVraiFaux($question, $orbit, $rotation, $correct, $partieChecking, $data);
		}
		elseif ($type == 'QUESINTERAC') {
			$reponse = areQuesInteracInputGood($question, $_POST['orbit'], $_POST['rotation'], $_POST['margin-rotation'], $_POST['margin-orbit']);
			if ($reponse) {
				return;
			}

			$orbit = $_POST['orbit'];
			$rotation = $_POST['rotation'];
			$rotationMargin = $_POST['margin-rotation'];
			$orbitMargin = $_POST['margin-orbit'];

			$controllerQuestions->addQInterac($question, $orbit, $rotation, $rotationMargin, $orbitMargin, $partieChecking, $data);
		}
		elseif ($type == 'QCU') {
			$reponse = areQcuInputGood($question, $_POST['option1'], $_POST['option2'], $_POST['option3'], $_POST['option4'], $_POST['answer']);
			if ($reponse) {
				return;
			}

			$option1 = $_POST['option1'];
			$option2 = $_POST['option2'];
			$option3 = $_POST['option3'];
			$option4 = $_POST['option4'];
			$correct = $_POST['answer'];

			$controllerQuestions->addQCU($question, $option1, $option2, $option3, $option4, $correct, $partieChecking, $data);
		}
	} else {
		http_response_code(400);
		echo "URL not complete, cannot add question.";
	}

	return;
}
elseif ('/edit-question' == $uri && (isset($_SESSION['loggedin'])) ){
	$_GET['qid'] = $_GET['qid'] ?? '';
	$questionData = $controllerQuestions->getJsonAttributesQ($_GET['qid'], $partieChecking, $data);

	$questionData = json_decode($questionData, true);

	if (isset($questionData['Type']) && $questionData['Type'] == 'QCU') {
		$qcu = new _qcu($questionData['Enonce'], $questionData['Rep1'], $questionData['Rep2'], $questionData['Rep3'], $questionData['Rep4'], $questionData['BonneRep']);
		echo $qcu->render();
	}
	if (isset($questionData["Type"]) && $questionData['Type'] == 'VRAIFAUX') {
		$vraifaux = new _vraifaux($questionData['Enonce'], $questionData['Valeur_orbit'], $questionData['Valeur_rotation'], $questionData['BonneRep']);
		echo $vraifaux->render();
	}
	if (isset($questionData['Type']) && $questionData['Type'] == 'QUESINTERAC') {
		$quesinterac = new _questionInteractive($questionData['Enonce'], $questionData['BonneRepValeur_orbit'], $questionData['BonneRepValeur_rotation'], $questionData['Marge_Orbit'], $questionData['Marge_Rotation']);
		echo $quesinterac->render();
	}
}
elseif ('/question/qcu' == $uri && (isset($_SESSION['loggedin'])) ){
	$qcu = new _qcu();
	echo $qcu->render();
}
elseif ('/question/vraifaux' ==$uri && (isset($_SESSION['loggedin'])) ){
	$vraifaux = new _vraifaux();
	echo $vraifaux->render();
}
elseif ('/question/quesinterac' == $uri && (isset($_SESSION['loggedin'])) ){
	$quesinterac = new _questionInteractive();
	echo $quesinterac->render();
}
else {
	header('Status: 404 Not Found');
	echo '<html><body><h1>Page Not Found</h1>';
	echo '<button onclick="window.location.href=\'/\'">Retour à la page d\'accueil</button>';
	echo '</body></html>';
}

function areQcuInputGood($question, $option1, $option2, $option3, $option4, $answer): bool
{
	$cpt = 0;
	if (trim($question) == "") {
		$cpt++;
		http_response_code(400);
		echo "La question ne peut pas être vide.\n";
	}

	if (trim($option1) == "") {
		$cpt++;
		http_response_code(400);
		echo "L'option 1 ne peut pas être vide.\n";
	}

	if (trim($option2) == "") {
		$cpt++;
		http_response_code(400);
		echo "L'option 2 ne peut pas être vide.\n";
	}

	if (trim($option3) == "") {
		$cpt++;
		http_response_code(400);
		echo "L'option 3 ne peut pas être vide.\n";
	}

	if (trim($option4) == "") {
		$cpt++;
		http_response_code(400);
		echo "L'option 4 ne peut pas être vide.\n";
	}

	if (trim($answer) == "") {
		$cpt++;
		http_response_code(400);
		echo "La réponse ne peut pas être vide.\n";
	}

	return $cpt > 0;
}

function areVraiFauxInputGood($question, $answer, $orbit, $rotation): bool
{
	$cpt = 0;

	if (trim($question) == "") {
		$cpt++;
		http_response_code(400);
		echo "La question ne peut pas être vide.\n";
	}

	if (trim($answer) == "") {
		$cpt++;
		http_response_code(400);
		echo "La réponse ne peut pas être vide.\n";
	}



	if (trim($orbit) != "" && ($orbit < 0 || $orbit > 1)) {
		$cpt++;
		http_response_code(400);
		echo "La valeur de l'orbite doit être comprise entre 0 et 1.\n";
	}

	if (trim($rotation) != "" && ($rotation < 0 || $rotation > 1)) {
		$cpt++;
		http_response_code(400);
		echo "La valeur de la rotation doit être comprise entre 0 et 1.\n";
	}

	return $cpt > 0;
}

function areQuesInteracInputGood($question, $orbit, $rotation, $rotationMargin, $orbitMargin): bool
{
	$cpt = 0;
	if (trim($question) == "") {
		$cpt++;
		http_response_code(400);
		echo "La question ne peut pas être vide.\n";
	}

	if (trim($orbit) == "") {
		$cpt++;
		http_response_code(400);
		echo "La valeur de l'orbite ne peut pas être vide.\n";
	}

	if ($orbit < 0 || $orbit > 1) {
		$cpt++;
		http_response_code(400);
		echo "La valeur de l'orbite doit être comprise entre 0 et 1.\n";
	}

	if (trim($rotation) == "") {
		$cpt++;
		http_response_code(400);
		echo "La valeur de la rotation ne peut pas être vide.\n";
	}

	if ($rotation < 0 || $rotation > 1) {
		$cpt++;
		http_response_code(400);
		echo "La valeur de la rotation doit être comprise entre 0 et 1.\n";
	}

	if (trim($rotationMargin) == "") {
		$cpt++;
		http_response_code(400);
		echo "La marge de rotation ne peut pas être vide.\n";
	}

	if ($rotationMargin < 0 || $rotationMargin > 1) {
		$cpt++;
		http_response_code(400);
		echo "La marge de rotation doit être comprise entre 0 et 1.\n";
	}

	if (trim($orbitMargin) == "") {
		$cpt++;
		http_response_code(400);
		echo "La marge de l'orbite ne peut pas être vide.\n";
	}

	if ($orbitMargin < 0 || $orbitMargin > 1) {
		$cpt++;
		http_response_code(400);
		echo "La marge de l'orbite doit être comprise entre 0 et 1.\n";
	}

	return $cpt > 0;
}
