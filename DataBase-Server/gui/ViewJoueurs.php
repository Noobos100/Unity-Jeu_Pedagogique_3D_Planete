<?php

namespace gui;

include_once "View.php";

class ViewJoueurs extends View
{
    /**
     * Constructs a new ViewJoueurs instance.
     *
     * @param Layout $layout The layout to use for displaying content.
     * @param $controller The controller to fetch data from.
     * @param $data Any additional data needed.
     */
    public function __construct($layout, $controller, $data)
    {
        parent::__construct($layout);

        $this->title = 'Utilisateurs';
        $this->content .= '<h1>Types de joueur</h1>';

        $joueurs = $controller->getJoueurs($data);

        // Afficher le nombre total de joueurs
        $totalJoueurs = count($joueurs);
        $this->content .= "<p>Nombre total de joueurs : $totalJoueurs</p>";

        // Passer les données des joueurs à la vue
        $this->content .= $controller->generateChart($joueurs);

        // Ajouter le tableau des adresses IP et leur nombre d'apparitions
        $this->content .= $controller->generateTable($joueurs);
    }

}
?>
