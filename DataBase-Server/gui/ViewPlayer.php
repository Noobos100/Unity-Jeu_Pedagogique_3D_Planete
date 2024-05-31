<?php

namespace gui;

use control\ControllerPlayers;

include_once "View.php";

class ViewPlayer extends View
{
    /**
     * Constructs a new ViewPlayer instance.
     *
     * @param Layout $layout The layout to use for displaying content.
     * @param ControllerPlayers $controller The controller to fetch data from.
     * @param mixed $data additional data needed.
     */
    public function __construct(Layout $layout, ControllerPlayers $controller, mixed $data)
    {
        parent::__construct($layout);

        // Page title
        $this->title = 'Utilisateurs';

        // Page content
        $this->content .= '<h1>Types de joueur</h1>';

        // Get all players
        $players = $controller->getPlayers($data);

        // Get player count
        $nbPlayers = count($players);
        $this->content .= "<p>Nombre total de joueurs : $nbPlayers</p>";

// Generate the pie chart
        $this->content .= '<div class="column-left">';
        $this->content .= $controller->generateChartPlatforme($players);
        $this->content .= '</div>';

// Ip Table
        $this->content .= '<div class="column-right">';
        $this->content .= $controller->generateTable($players);
        $this->content .= '</div>';
    }

}