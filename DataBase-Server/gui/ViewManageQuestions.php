<?php

namespace gui;

use gui\View;

class ViewManageQuestions extends View
{
    public function __construct(Layout $layout, mixed $questions)
    {
        parent::__construct($layout);

        $this->title = 'Gestion des questions';


        $this->content .= '<h1>Gestionnaire des questions</h1>';

        $questions = json_decode($questions, true);

        $this->content .= '
    <link href="/assets/css/managequestion.css" rel="stylesheet" />
    <div id="add-question-popup" class="popup">
        <form action="/add-question" method="post" class="popup-content">
            <span class="close" onclick="document.getElementById(\'add-question-popup\').style.display = \'none\'">&times;</span>
            <h2>Ajouter une question</h2>
            <label for="type">Type:</label>
            <select id="type" name="type" required onchange="showFormFields(this.value)">
                <option value="">Sélectionnez un type</option>
                <option value="VRAIFAUX">VRAIFAUX</option>
                <option value="QCU">QCU</option>
                <option value="QUESINTERAC">QUESINTERAC</option>
            </select>
            <div id="form-fields-container"></div>
            <input type="submit" value="Ajouter">
        </form>
    </div>
    <button onclick="document.getElementById(\'add-question-popup\').style.display = \'block\'">Ajouter une question</button>
    <label for="filter">Filtrer par type:</label>
    <select id="filter" onchange="filterQuestions()">
    <option value="all">Tous</option>
    <option value="VRAIFAUX">VRAIFAUX</option>
    <option value="QCU">QCU</option>
    <option value="QUESINTERAC">QUESINTERAC</option>
    </select>
    <table id="question-table">
       <tr>
       <th>ID Question</th>
       <th>Enoncé</th>
       <th>Type</th>
       <th>Réponses</th>
       </tr>';
        foreach ($questions as $question) {
            $this->content .= '<tr class="question-row">
                        <td>' . htmlspecialchars($question['Num_Ques']) . '</td>
                        <td>' . htmlspecialchars($question['Enonce']) . '</td>
                        <td>' . htmlspecialchars($question['Type']) . '</td>
                        <td><button onclick="location.href=\'modify-question?qid=' . $question['Num_Ques'] . '\'">Modifier</button></td>
                        <td><button onclick="deleteQuestion(' . $question['Num_Ques'] . ')">Supprimer</button></td>
                       </tr>';
        }
        $this->content .= '
        </table>
        </div>
        <script src="/assets/js/managequestion.js"></script>';
    }
}