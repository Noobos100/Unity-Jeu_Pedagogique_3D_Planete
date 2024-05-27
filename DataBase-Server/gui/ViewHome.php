<?php

namespace gui;

include_once "View.php";

class ViewHome extends View
{
    private $currentPage;

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
        $this->content = '<link rel="stylesheet" href="../Assets/Css/Style.css">';
        $this->content .= '<div class="container">
    <div class="sidebar">
        <button class="sidebar-button' . ($this->currentPage == '/index.php/home' ? ' active url-match' : '') . '" onclick="window.location.href=\'/index.php/home\'">Accueil</button>
        <button class="sidebar-button' . ($this->currentPage == '/index.php/utilisateurs' ? ' active url-match' : '') . '" onclick="window.location.href=\'/index.php/utilisateurs\'">Utilisateurs</button>
        <button class="sidebar-button' . ($this->currentPage == '/index.php/ManageQuestions' ? ' active url-match' : '') . '" onclick="window.location.href=\'/index.php/ManageQuestions\'">Questions</button>
        <button class="sidebar-button' . ($this->currentPage == '/index.php/parties' ? ' active url-match' : '') . '" onclick="window.location.href=\'/index.php/parties\'">Parties</button>
        <button class="sidebar-button' . ($this->currentPage == '/index.php/type-joueurs' ? ' active url-match' : '') . '" onclick="window.location.href=\'/index.php/type-joueurs\'">Type joueurs</button>
        <div class="sidebar-footer">
            <p id="datetime"></p>
        </div>
    </div>
    <div class="main-content">
        <p>Bienvenue sur le site de gestion des interactions</p>
        <p>Bonjour ' . htmlspecialchars($login) . '</p>
    </div>
</div>';

        // Ajouter un script pour mettre à jour l'heure et la date actuelles

        $this->content .= '<script>
            function updateDateTime() {
                const now = new Date();
                const date = now.toLocaleDateString("fr-FR");
                const time = now.toLocaleTimeString("fr-FR");
                document.getElementById("datetime").textContent = `${time} ${date}`;
            }
            setInterval(updateDateTime, 1000);
            window.onload = updateDateTime;
        </script>';
    }
}
