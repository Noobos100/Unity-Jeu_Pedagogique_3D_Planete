<?php

namespace gui;

include_once "View.php";

class ViewHome extends View
{
    /**
     * Constructs a new ViewHome instance.
     *
     * @param Layout $layout The layout to use for displaying content.
     */
    public function __construct($layout)
    {
        parent::__construct($layout);

        // Récupérer le login de l'utilisateur connecté depuis la session
        $login = isset($_SESSION['username']) ? $_SESSION['username'] : 'user';

        $this->title = 'Accueil';
        $this->content = '<div class="container">
            <div class="sidebar">
                <button class="sidebar-button">Accueil</button>
                <button class="sidebar-button">Utilisateurs</button>
                <button class="sidebar-button">Questions</button>
                <button class="sidebar-button">Parties</button>
                <button class="sidebar-button">Type joueurs</button>
                <div class="sidebar-footer">
                    <p id="datetime"></p>
                </div>
            </div>
            <div class="main-content">
                <p>Bienvenue sur le site de gestion des interactions</p>
                <p>Bonjour ' . htmlspecialchars($login) . '</p>
            </div>
        </div>';

        // Add CSS and JavaScript
        $this->content .= '<style>
            body, html {
                margin: 0;
                padding: 0;
                height: 100%;
                font-family: Arial, sans-serif;
            }
            .container {
                display: flex;
                height: 100%;
                background: url("/image/espace2.jpg") no-repeat center center fixed;
                background-size: cover;
            }
            .sidebar {
                width: 200px;
                background-color: #061123;
                color: white;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                padding: 10px;
            }
            .sidebar-button {
                background: linear-gradient(90deg, #061123, #0B1B66, #061123);
                color: white;
                border: none;
                padding: 10px;
                margin-top: 10px; /* Add margin-top for spacing */
                cursor: pointer;
                text-align: center;
                border-radius: 5px;
            }
            .sidebar-button:hover {
                background-color: #333;
            }
            .sidebar-footer {
                font-size: 14px;
            }
            .main-content {
                flex-grow: 1;
                padding: 20px;
                color: white;
                background: rgba(0, 0, 0, 0.5);
                margin: 20px;
                border-radius: 10px;
                overflow-y: auto;
            }
            label, input {
                display: block;
                margin-bottom: 10px;
                font-size: 16px;
            }
            input[type="text"], input[type="password"] {
                width: 100%;
                padding: 10px;
                border: none;
                border-radius: 5px;
                font-size: 16px;
            }
            input[type="submit"] {
                padding: 10px;
                border: none;
                border-radius: 5px;
                background-color: #1a1a1a;
                color: white;
                font-size: 18px;
                cursor: pointer;
            }
            input[type="submit"]:hover {
                background-color: #333;
            }
        </style>';

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
