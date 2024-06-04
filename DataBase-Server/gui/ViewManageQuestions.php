<?php

namespace gui;

/**
 * Vue pour gérer les questions (supression, modification, etc...)
 */
class ViewManageQuestions extends View
{
    /**
     * @param Layout $layout
     * @param mixed $questions
     */
    public function __construct(Layout $layout, mixed $questions)
    {
        parent::__construct($layout);

        $this->title = 'Gestion des questions';

        $questions = json_decode($questions, true);

        $this->content .= '
    <h1>Gestionnaire des questions</h1>
    <link href="/assets/css/managequestion.css" rel="stylesheet" />
    <div id="add-question-popup" class="popup">
        <form action="/add-question" method="post" class="popup-content">
            <span class="fas fa-circle-xmark" onclick="document.getElementById(\'add-question-popup\').style.display = \'none\'"></i></span>
            <h2>Ajouter une question</h2>
            <label for="type">Type:</label>
            <select class="selector" id="type" name="type" required onchange="showFormFields(this.value)">
                <option value="">Sélectionnez un type</option>
                <option value="VRAIFAUX">VRAI OU FAUX</option>
                <option value="QCU">QCU</option>
                <option value="QUESINTERAC">QUESTION INTERACTIVE</option>
            </select>
            <div id="form-fields-container"></div>
            <input class="submit" type="submit" value="Ajouter">
        </form>
    </div>
    <button class="buttonStyle" onclick="document.getElementById(\'add-question-popup\').style.display = \'block\'">Ajouter une question</button>
    <label for="filter">Filtrer par type :</label>
    <select class="selector" id="filter" onchange="filterQuestions()">
    <option value="all">Tous</option>
    <option value="VRAIFAUX">VRAI OU FAUX</option>
    <option value="QCU">QCU</option>
    <option value="QUESINTERAC">QUESTION INTERACTIVE</option>
    </select>
    <table id="question-table">
       <tr>
       <th >ID Question</th>
       <th>Enoncé</th>
       <th>Type</th>
       <th></th>
       </tr>';
        foreach ($questions as $question) {
            $this->content .= '
                        <tr class="question-row">
                            <td>' . htmlspecialchars($question['Num_Ques']) . '</td>
                            <td>' . htmlspecialchars($question['Enonce']) . '</td>
                            <td>' . htmlspecialchars($question['Type']) . '</td>
                            <td><button class="fas fa-pencil" onclick="location.href=\'modify-question?qid=' . $question['Num_Ques'] . '\'"></button></td>
                            <td><button class="fas fa-trash-can" onclick="deleteQuestion(' . $question['Num_Ques'] . ')"></button></td>
                       </tr>';
        }
        $this->content .= '
        </table>
        </div>
        <script src="/assets/js/managequestion.js"></script>';
    }
}