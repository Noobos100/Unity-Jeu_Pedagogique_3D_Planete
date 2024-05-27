<?php

namespace gui;

include_once "View.php";

class ViewUtilisateur extends View
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
        $this->content = '<link rel="stylesheet" href="../Assets/Css/Style.css">';
        $this->content .= '<div class="container">
    <div class="sidebar">
        <button class="sidebar-button" onclick="window.location.href=\'/index.php/Home\'">Accueil</button>
        <button class="sidebar-button" onclick="window.location.href=\'/index.php/Utilisateurs\'">Utilisateurs</button>
        <button class="sidebar-button" onclick="window.location.href=\'/index.php/ManageQuestions\'">Questions</button>
        <button class="sidebar-button" onclick="window.location.href=\'/index.php/Parties\'">Parties</button>
        <button class="sidebar-button" onclick="window.location.href=\'/index.php/TypesJoueur\'">Types joueur</button>
        <div class="sidebar-footer">
            <p id="datetime"></p>
        </div>
    </div>
    <div class="main-content">
        <h1>Utilisateurs</h1>
    </div>
</div>';

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