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
    public function __construct($layout, $questions)
    {
        parent::__construct($layout);

        $this->title = 'Accueil';
        $this->content = '<p>Bienvenue sur le site de gestion des interactions</p>';
        $questions = json_decode($questions, true);

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

        // existing questions
        $this->content .= '<h2>Questions existantes</h2>';
        $this->content .= '<table id="question-table">';
        $this->content .= '<tr>
               <th>Numéro Question</th>
               <th>Enoncé</th>
               <th>Type</th>
               <th>Réponses</th>
               </tr>';
        foreach ($questions as $question) {
            $this->content .= '<tr>
                                <td>' . htmlspecialchars($question['Num_Ques']) . '</td>
                                <td>' . htmlspecialchars($question['Enonce']) . '</td>
                                <td>' . htmlspecialchars($question['Type']) . '</td>
                                <td><button onclick="window.open(\'/index.php/ModifyQuestion?qid=' . $question['Num_Ques'] . '\')">Réponses</button></td>
                               </tr>';
        }
        $this->content .= '</table>';

    }
}