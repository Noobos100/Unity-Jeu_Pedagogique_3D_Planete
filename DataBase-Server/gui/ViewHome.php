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

        $this->title = 'Accueil';
        $this->content = '<p>Bienvenue sur le site de gestion des interactions</p>';
        if ($_SESSION['username'] != null) {
            $this->content .= '<p>Bonjour ' . $_SESSION['username'] . '</p>';
        }
        // form to add a question
        $this->content .= '<form action="/index.php/addQuestion" method="post">
            <label for="question">Question:</label>
            <input type="text" id="question" name="question" required>
            <label for="answer">Réponse:</label>
            <input type="text" id="answer" name="answer" required>
            <input type="submit" value="Ajouter">
        </form>';
    }
}