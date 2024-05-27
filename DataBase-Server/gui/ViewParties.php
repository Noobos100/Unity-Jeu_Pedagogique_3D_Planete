<?php

namespace gui;

include_once "View.php";

class ViewParties extends View
{
    private string $currentPage;

    /**
     * Constructs a new ViewHome instance.
     *
     * @param Layout $layout The layout to use for displaying content.
     */
    public function __construct($layout)
    {
        parent::__construct($layout);

        // Déterminer la page actuelle
        $this->currentPage = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        $this->title = 'Utilisateurs';
        $this->content .= '<h1>Parties</h1>';
    }

}