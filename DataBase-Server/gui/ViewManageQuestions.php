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
    <style>
        .popup {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 50%;
            padding: 20px;
            border: 1px solid #888;
            background-color: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        .popup-content {
            position: relative;
        }
        .popup .close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
        }
        .popup-active {
            filter: blur(5px);
        }
        .form-fields {
            margin-top: 20px;
        }
    </style>
    <script>
function showFormFields(type) {
    const container = document.getElementById("form-fields-container");
    container.innerHTML = ""; // Clear previous fields

    let fields = "";

    if (type === "VRAIFAUX") {
        fields = `
            <label for="enonce">Enoncé:</label>
            <input type="text" id="enonce" name="enonce" required>
            <label for="orbit">Orbite (optionnel):</label>
            <input type="text" id="orbit" name="orbit">
            <label for="rotation">Rotation (optionnel):</label>
            <input type="text" id="rotation" name="rotation">
            <label for="reponse">Réponse:</label>
            <select id="reponse" name="reponse" required>
                <option value="Vrai">Vrai</option>
                <option value="Faux">Faux</option>
            </select>
        `;
    } 
    else if (type === "QCU") {
        fields = `
            <label for="enonce">Enoncé:</label>
            <input type="text" id="enonce" name="enonce" required>
            <label for="option1">Option 1:</label>
            <input type="text" id="option1" name="option1" required">
            <label for="option2">Option 2:</label>
            <input type="text" id="option2" name="option2" required">
            <label for="option3">Option 3:</label>
            <input type="text" id="option3" name="option3" required">
            <label for="option4">Option 4:</label>
            <input type="text" id="option4" name="option4" required">
            <label for="correct">Correct Answer:</label>
            <select id="correct" name="correct" required">
                <option value="Rep1">Option 1</option>
                <option value="Rep2">Option 2</option>
                <option value="Rep3">Option 3</option>
                <option value="Rep4">Option 4</option>
            </select>
        `;
    } 
    else if (type === "QUESINTERAC") {
        fields = `
            <label for="enonce">Enoncé:</label>
            <input type="text" id="enonce" name="enonce" required>
            <label for="orbit">Réponse orbite:</label>
            <input type="text" id="orbit" name="orbit" required>
            <label for="rotation">Réponse rotation:</label>
            <input type="text" id="rotation" name="rotation" required>
            
            <label for="margin-rotation">Marge rotation:</label>
            <input type="text" id="margin-rotation" name="margin-rotation" required>
            <label for="margin-orbit">Marge orbite:</label>
            <input type="text" id="margin-orbit" name="margin-orbit" required>
    `;
    }

    container.innerHTML = fields;
}

        document.addEventListener("DOMContentLoaded", (event) => {
            const popup = document.getElementById("add-question-popup");
            const closeButton = popup.querySelector(".close");

            document.querySelector("button[onclick]").addEventListener("click", () => {
                popup.style.display = "block";
                document.body.classList.add("popup-active");
            });

            closeButton.addEventListener("click", () => {
                popup.style.display = "none";
                document.body.classList.remove("popup-active");
            });

            window.addEventListener("click", (event) => {
                if (event.target === popup) {
                    popup.style.display = "none";
                    document.body.classList.remove("popup-active");
                }
            });
        });
    </script>
    <style>
        text {
            color: black;            
        }
        .popup {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 50%;
            padding: 20px;
            border: 1px solid #888;
            background-color: white;
            color: black;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        .popup-content {
            position: relative;
        }
        .popup .close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
        }
        .popup-active {
            filter: blur(5px);
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", (event) => {
            const popup = document.getElementById("add-question-popup");
            const closeButton = popup.querySelector(".close");

            document.querySelector("button[onclick]").addEventListener("click", () => {
                popup.style.display = "block";
                document.body.classList.add("popup-active");
            });

            closeButton.addEventListener("click", () => {
                popup.style.display = "none";
                document.body.classList.remove("popup-active");
            });

            window.addEventListener("click", (event) => {
                if (event.target === popup) {
                    popup.style.display = "none";
                    document.body.classList.remove("popup-active");
                }
            });
        });
    </script>
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
        <script>
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