<?php

namespace gui;


class ViewModifyQuestion extends View
{
    private string $currentPage;

    public function __construct($layout, $questionData)
    {
        parent::__construct($layout);

        // Decode the JSON data
        $questionData = json_decode($questionData, true);

        $this->title = 'Modification de la question';
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

        $this->content .= '<div class="main-content">';

        if ($questionData['Type'] == 'QCU') {
            $this->content .=
                '<form action="/index.php/ModifyQuestion?qid=' . htmlspecialchars($questionData['Num_Ques']) . '" method="post">
            <label for="question">Question:</label>
            <input type="text" id="question" name="question" value="' . htmlspecialchars($questionData['Enonce']) . '" required>
            <br>
            <label for="option1">Option 1:</label>
            <input type="text" id="option1" name="option1" value="' . htmlspecialchars($questionData['Rep1']) . '" required>
            <input type="radio" id="correct1" name="correct" value="1" ' . ($questionData['BonneRep'] == $questionData['Rep1'] ? 'checked' : '') . '>
            <br>
            <label for="option2">Option 2:</label>
            <input type="text" id="option2" name="option2" value="' . htmlspecialchars($questionData['Rep2']) . '" required>
            <input type="radio" id="correct2" name="correct" value="2" ' . ($questionData['BonneRep'] == $questionData['Rep2'] ? 'checked' : '') . '>
            <br>
            <label for="option3">Option 3:</label>
            <input type="text" id="option3" name="option3" value="' . htmlspecialchars($questionData['Rep3']) . '" required>
            <input type="radio" id="correct3" name="correct" value="3" ' . ($questionData['BonneRep'] == $questionData['Rep3'] ? 'checked' : '') . '>
            <br>
            <label for="option4">Option 4:</label>
            <input type="text" id="option4" name="option4" value="' . htmlspecialchars($questionData['Rep4']) . '" required>
            <input type="radio" id="correct4" name="correct" value="4" ' . ($questionData['BonneRep'] == $questionData['Rep4'] ? 'checked' : '') . '>
            <br>
            <input type="submit" value="Submit">
        </form>';
        } elseif ($questionData['Type'] == 'QUESINTERAC') {
            $this->content .=
                '<form action="/index.php/ModifyQuestion?qid=' . htmlspecialchars($questionData['Num_Ques']) . '" method="post">
            <label for="question">Question:</label>
            <input type="text" id="question" name="question" value="' . htmlspecialchars($questionData['Enonce']) . '" required>
            <br>
            <label for="orbit">Answer for orbit:</label>
            <input type="text" id="orbit" name="answer" value="' . htmlspecialchars($questionData['BonneRepValeur_orbit']) . '" required>
            <br>
            <label for="rotation">Answer for rotation:</label>
            <input type="text" id="rotation" name="answer" value="' . htmlspecialchars($questionData['BonneRepValeur_rotation']) . '" required>
            <br>
            <input type="submit" value="Submit">
        </form>';
        } elseif ($questionData['Type'] == 'VRAIFAUX') {
            $this->content .=
                '<form action="/index.php/ModifyQuestion?qid=' . htmlspecialchars($questionData['Num_Ques']) . '" method="post">
            <label for="question">Question:</label>
            <input type="text" id="question" name="question" value="' . htmlspecialchars($questionData['Enonce']) . '" required>
            <br>
            <label for="answer">Answer:</label>
            <input type="radio" id="true" name="answer" value="1" ' . ($questionData['BonneRep'] == 'Vrai' ? 'checked' : '') . '>
            <label for="true">True</label>
            <input type="radio" id="false" name="answer" value="0" ' . ($questionData['BonneRep'] == 'Faux' ? 'checked' : '') . '>
            <label for="false">False</label>
            <br>
            <input type="submit" value="Submit">
        </form>';
        }
        $this->content .= '</div>';
    }
}
