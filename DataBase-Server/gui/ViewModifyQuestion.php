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
        $this->content = '<link rel="stylesheet" href="../assets/css/Style.css">';
        $this->content .= 'Pour débug, request method: '.$_SERVER['REQUEST_METHOD'];
        // Déterminer la page actuelle
        $this->currentPage = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        $this->content .= '<div class="container">
            <div class="sidebar">
                <button class="sidebar-button' . ($this->currentPage == '/index.php/home' ? ' active url-match' : '') . '" onclick="window.location.href=\'/index.php/home\'">Accueil</button>
                <button class="sidebar-button' . ($this->currentPage == '/index.php/utilisateurs' ? ' active url-match' : '') . '" onclick="window.location.href=\'/index.php/utilisateurs\'">Utilisateurs</button>
                <button class="sidebar-button' . ($this->currentPage == '/index.php/ManageQuestions' ? ' active url-match' : '') . '" onclick="window.location.href=\'/index.php/ManageQuestions\'">Questions</button>
                <button class="sidebar-button' . ($this->currentPage == '/index.php/parties' ? ' active url-match' : '') . '" onclick="window.location.href=\'/index.php/parties\'">Parties</button>
                <button class="sidebar-button' . ($this->currentPage == '/index.php/type-joueurs' ? ' active url-match' : '') . '" onclick="window.location.href=\'/index.php/type-joueurs\'">Type joueurs</button>
                <div class="sidebar-footer">
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
            <br>
            <label for="option2">Option 2:</label>
            <input type="text" id="option2" name="option2" value="' . htmlspecialchars($questionData['Rep2']) . '" required>
            <br>
            <label for="option3">Option 3:</label>
            <input type="text" id="option3" name="option3" value="' . htmlspecialchars($questionData['Rep3']) . '" required>
            <br>
            <label for="option4">Option 4:</label>
            <input type="text" id="option4" name="option4" value="' . htmlspecialchars($questionData['Rep4']) . '" required>
            <br>';
            // A <select> input showing all options, labeled 'correct answer';
            $this->content .= '<label for="correct">Correct answer:</label>
            <select id="correct" name="correct" required>
                <option value="Rep1">Option 1</option>
                <option value="Rep2">Option 2</option>
                <option value="Rep3">Option 3</option>
                <option value="Rep4">Option 4</option>
            </select>
            <br>
            <label for="current">Current correct answer:</label>
            <input type="text" id="current" name="current" value="' . htmlspecialchars($questionData['BonneRep']) . '" readonly> 
            <br>
            <input type="submit" value="Submit changes">
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
