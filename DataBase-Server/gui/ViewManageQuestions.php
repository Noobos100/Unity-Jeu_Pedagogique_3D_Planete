<?php

namespace gui;

use gui\View;

class ViewManageQuestions extends View
{
    public function __construct(Layout $layout, mixed $questions)
    {
        parent::__construct($layout);

        $this->title = 'Gestion des questions';

        // Ajouter un script pour mettre à jour l'heure et la date actuelles
        $this->content .= '<h1>Vous pouvez gérer les questions ici</h1>';

        $questions = json_decode($questions, true);

        // form to add a question
        $this->content .= '
		<form action="add-question" method="post">
            <label for="question">Question:</label>
            <input type="text" id="question" name="question" required>
            <label for="answer">Réponse:</label>
            <input type="text" id="answer" name="answer" required>
            <input type="submit" value="Ajouter">
        </form>';
        // <select> to filter questions by type in list
        $this->content .= '<label for="filter">Filtrer par type:</label>
    <select id="filter" onchange="filterQuestions()">
    <option value="all">Tous</option>
    <option value="VRAIFAUX">VRAIFAUX</option>
    <option value="QCU">QCU</option>
    <option value="QUESINTERAC">QUESINTERAC</option>
    </select>';

        $this->content .= '<table id="question-table">';
        $this->content .= '<tr>
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
        $this->content .= '</table>';
        $this->content .= '</div>';
        $this->content .= '<script>
        function filterQuestions() {
            let filter = document.getElementById("filter").value;
            let rows = document.querySelectorAll("#question-table .question-row");
            rows.forEach(row => {
                let type = row.cells[2].innerText;
                if (filter === "all" || type === filter) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }
        function deleteQuestion(qid) {
            if (confirm("Êtes-vous sûr de vouloir supprimer cette question?")) {
                location.href = "delete-question?qid=" + qid;
            }
        }
</script>';
    }
}