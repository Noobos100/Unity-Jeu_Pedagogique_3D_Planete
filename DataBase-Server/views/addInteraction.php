<?php
require("loaders/autoloader.php");
$controller = new controllers\controllerInteractions($dbConn);

if (isset($_GET["type"]) && isset($_GET["value"]) && isset($_GET["isEval"])){
    $ip = '';
    $type = $_GET["type"];
    $value = $_GET["value"];
    $isEval = $_GET["isEval"];
    try{
        $controller->addInteration($ip, $type, $value, $isEval);
    }
    catch (\utilities\CannotDoException $e){
        $report = $e->getReport();
        $report = str_replace( '\n', '<br />', $report );
        echo '<p>', $report, '</p>';
    }
}
else{
    echo "URL not complete, cannot register new interaction.";
}