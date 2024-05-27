<?php

namespace gui;

use gui\View;

class ViewManageQuestions extends View
{

    private string $currentPage;

    public function __construct($layout, $questions)
    {
        parent::__construct($layout);

        $this->title = 'Gestion des questions';
        $this->content = '<link rel="stylesheet" href="../Assets/Css/Style.css">';

        // Déterminer la page actuelle
        $this->currentPage = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $this->content .= '<div class="container">
            <div class="sidebar">
        <button class="sidebar-button" onclick="window.location.href=\'/index.php/Home\'">Accueil</button>
        <button class="sidebar-button" onclick="window.location.href=\'/index.php/Utilisateurs\'">Utilisateurs</button>
        <button class="sidebar-button" onclick="window.location.href=\'/index.php/ManageQuestions\'">Questions</button>
        <button class="sidebar-button" onclick="window.location.href=\'/index.php/Parties\'">Parties</button>
        <button class="sidebar-button" onclick="window.location.href=\'/index.php/TypesJoueur\'">Types joueur</button>
                    <p id="datetime"></p>
                </div>';

        // Ajouter un script pour mettre à jour l'heure et la date actuelles
        $this->content .= '<h1>Vous pouvez gérer les questions ici</h1>';

        $questions = json_decode($questions, true);

        // form to add a question
        $this->content .= '<form action="/index.php/addQuestion" method="post">
            <label for="question">Question:</label>
            <input type="text" id="question" name="question" required>
            <label for="answer">Réponse:</label>
            <input type="text" id="answer" name="answer" required>
            <input type="submit" value="Ajouter">
        </form>';
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
        $this->content .= '</div>';
    }
}