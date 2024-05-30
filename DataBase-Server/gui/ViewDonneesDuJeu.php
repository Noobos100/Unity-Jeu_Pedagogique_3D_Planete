<?php

namespace gui;

include_once "View.php";

class ViewDonneesDuJeu extends View
{

    /**
     * Constructs a new ViewHome instance.
     *
     * @param Layout $layout The layout to use for displaying content.
     */
    public function __construct($layout, $controller, $data)
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
        $this->content .= "<p>Nombre total d'abandons sur le total : $totalAbandons</p>";

        $this->content .= '<h2>Parties</h2>';

        // Afficher le temps minimum
        $totalMinTemps = $controller->calculateTempsMin($parties);
        $this->content .= "<p>Temps minimum de jeu : $totalMinTemps</p>";

        // Afficher le temps maximum
        $totalMaxTemps = $controller->calculateTempsMax($parties);
        $this->content .= "<p>Temps maximum de jeu : $totalMaxTemps</p>";

        // Ajouter le tableau des données dans le contenu
        $this->content .= $controller->generateTable($parties);

        $this->content .= $controller->generateChartMoyQuestion($partiesAsc);

        $this->content .= '<h2>Réponses</h2>';

        // Afficher le temps minimum
        $totalMinTemps = $controller->calculateTempsMin($reponseUser);
        $this->content .= "<p>Temps minimum de réponse : $totalMinTemps</p>";

        // Afficher le temps maximum
        $totalMaxTemps = $controller->calculateTempsMax($reponseUser);
        $this->content .= "<p>Temps maximum de réponse : $totalMaxTemps</p>";

        // Ajouter le tableau des données dans le contenu
        $this->content .= $controller->generateTable($reponseUser);

        $this->content .= $controller->generateChartPourcentage($reponseUser);

        $this->content .= $controller->generateChartApparitions($getQuestionsNb);
    }

}

