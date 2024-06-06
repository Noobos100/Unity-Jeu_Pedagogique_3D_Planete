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
     * Constructs a new ViewGameData instance.
     *
     * @param Layout $layout The layout to use for displaying content.
     * @param ControllerGameData $controller The controller to use for getting data.
     * @param DataAccess $data The data access service to use.
     */
    public function __construct(Layout $layout, ControllerGameData $controller, DataAccess $data)
    {
        parent::__construct($layout);

        $this->title = 'Utilisateurs';
        $this->content .= '<h1 class="h1-title">Données de jeu</h1>';

        $parties = $controller->getParties($data);
        $reponseUser = $controller->getReponsesUsers($data);
        $partiesAsc = $controller->getPartiesAsc($data);
        $getQuestionsNb = $controller->getQuestionNb($data);

        $this->content .= '<div class="grid-container">';

        // Afficher le nombre total de parties
        $totalParties = count($parties);
        $this->content .= "<div class=\"block\">Nombre total de parties : $totalParties</div>";

        // Afficher le nombre total d'abandons sur le total
        $totalAbandons = $controller->calculateTotalAbandons($parties);
        $this->content .= "<div class=\"block\">Nombre total d'abandons : $totalAbandons</div>";

        // Afficher le temps minimum
        $totalMinTemps = $controller->calculateTempsMin($parties);
        $this->content .= "<div class=\"block\">Temps minimum de jeu : $totalMinTemps</div>";

        // Afficher le temps maximum
        $totalMaxTemps = $controller->calculateTempsMax($parties);
        $this->content .= "<div class=\"block\">Temps maximum de jeu : $totalMaxTemps</div>";

        // Afficher le temps minimum
        $totalMinTemps = $controller->calculateTempsMin($reponseUser);
        $this->content .= "<div class=\"block\">Temps minimum de réponse : $totalMinTemps</div>";

        // Afficher le temps maximum
        $totalMaxTemps = $controller->calculateTempsMax($reponseUser);
        $this->content .= "<div class=\"block\">Temps maximum de réponse : $totalMaxTemps</div>";

        $this->content .= '</div>';

        $this->content .= '<h2>Parties</h2>';

        // Ajouter le tableau des données dans le contenu
        $this->content .= $controller->generateChartMoyQuestion($partiesAsc);

        $this->content .= '<h2>Réponses</h2>';

        $this->content .= $controller->generatePercentageChart($reponseUser);

        $this->content .= $controller->generateChartApparitions($getQuestionsNb);
    }
}
