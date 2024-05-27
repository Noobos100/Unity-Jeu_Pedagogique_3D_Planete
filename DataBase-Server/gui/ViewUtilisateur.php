<?php

namespace gui;

include_once "View.php";

class ViewUtilisateur extends View
{

    /**
     * Constructs a new ViewHome instance.
     *
     * @param Layout $layout The layout to use for displaying content.
     */
    public function __construct($layout)
    {
        parent::__construct($layout);

        // Déterminer la page actuelle

        $this->title = 'Utilisateurs';
		$this->content .= '<h1>Utilisateurs</h1>';
    }

}