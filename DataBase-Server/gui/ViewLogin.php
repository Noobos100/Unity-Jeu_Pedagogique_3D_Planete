<?php

namespace gui;

include_once "View.php";

class ViewLogin extends View
{
    /**
     * Constructs a new ViewLogin instance.
     *
     * @param Layout $layout The layout to use for displaying content.
     */
    public function __construct($layout)
    {
        parent::__construct($layout);

        $this->title = 'Connexion';
        $this->content = '<h2>Connexion</h2>';

        // Formulaire de connexion
        $this->content .= '<form action="/index.php" method="post">
            <label for="username">Nom d\'utilisateur:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Se connecter">
        </form>';
    }
}
