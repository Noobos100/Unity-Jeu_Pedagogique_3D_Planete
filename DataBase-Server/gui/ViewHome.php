<?php

namespace gui;

include_once "View.php";

class ViewHome extends View
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

// Récupérer le login de l'utilisateur connecté depuis la session
        $login = isset($_SESSION['username']) ? $_SESSION['username'] : 'user';

        $this->title = 'Accueil';
        $this->content .= '
			<h1>Bienvenue sur le site de gestion des interactions</h1>
			<h2>Bonjour ' . htmlspecialchars($login) . '</h2>';
    }
}
