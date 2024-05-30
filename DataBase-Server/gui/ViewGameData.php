<?php

namespace gui;

use control\ControllerGameData;
use data\DataAccess;

include_once "View.php";
include_once "charts/ViewPercentage.php";
include_once "charts/ViewMoyQuestions.php";
include_once "charts/ViewApparition.php";

class ViewGameData extends View
{
    /**
     * Constructs a new ViewHome instance.
     *
     * @param Layout $layout The layout to use for displaying content.
     */
    public function __construct(Layout $layout, ControllerGameData $controller, DataAccess $data)
    {
        parent::__construct($layout);

        $this->title = 'Utilisateurs';
        $this->content .= '<h1>Parties</h1>';

        $parties = $controller->getParties($data);
        $reponseUser = $controller->getReponsesUsers($data);
        $partiesAsc = $controller->getPartiesAsc($data);
        $getQuestionsNb = $controller->getQuestionNb($data);

        // Afficher le nombre total de parties
        $totalParties = count($parties);
        $this->content .= "<p>Nombre total de parties : $totalParties</p>";

        // Afficher le nombre total d'abandons sur le total
        $totalAbandons = $controller->calculateTotalAbandons($parties);
        $this->content .= "<p>Nombre total d'abandons : $totalAbandons</p>";

        $this->content .= '<h2>Parties</h2>';

        // Afficher le temps minimum
        $totalMinTemps = $controller->calculateTempsMin($parties);
        $this->content .= "<p>Temps minimum de jeu : $totalMinTemps</p>";

        // Afficher le temps maximum
        $totalMaxTemps = $controller->calculateTempsMax($parties);
        $this->content .= "<p>Temps maximum de jeu : $totalMaxTemps</p>";

        // Ajouter le tableau des données dans le contenu
        $this->content .= $controller->generateChartMoyQuestion($partiesAsc);

        $this->content .= '<h2>Réponses</h2>';

        // Afficher le temps minimum
        $totalMinTemps = $controller->calculateTempsMin($reponseUser);
        $this->content .= "<p>Temps minimum de réponse : $totalMinTemps</p>";

        // Afficher le temps maximum
        $totalMaxTemps = $controller->calculateTempsMax($reponseUser);
        $this->content .= "<p>Temps maximum de réponse : $totalMaxTemps</p>";

        $this->content .= $controller->generatePercentageChart($reponseUser);

        $this->content .= $controller->generateChartApparitions($getQuestionsNb);

    }

}

