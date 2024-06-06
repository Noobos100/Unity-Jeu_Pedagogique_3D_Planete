<?php

namespace gui;

use control\ControllerPlayers;
use data\DataAccess;

include_once "View.php";

class ViewPlayer extends View
{
    /**
     * Constructs a new ViewPlayer instance.
     *
     * @param Layout $layout The layout to use for displaying content.
     * @param ControllerPlayers $controller The controller to fetch data from.
     * @param DataAccess $data An instance of DataAccess.
     */
    public function __construct(Layout $layout, ControllerPlayers $controller, DataAccess $data)
    {
        parent::__construct($layout);

        // Page title
        $this->title = 'Utilisateurs';

        // Page content
        $this->content .= '<h1 class="h1-title">Types de joueur</h1>';

        // Get all players
        $players = $controller->getPlayers($data);

        // Get player count
        $nbPlayers = count($players);
        $this->content .= "<div class='texte'>
        <p>Nombre total de joueurs : $nbPlayers</p>
        </div>";

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