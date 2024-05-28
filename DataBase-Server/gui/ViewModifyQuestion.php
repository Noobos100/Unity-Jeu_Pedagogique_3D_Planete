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

        $this->title = 'Modification de la question '. htmlspecialchars($questionData['Num_Ques']);
        $this->content = '<link rel="stylesheet" href="../assets/css/Style.css">';
        // Déterminer la page actuelle
        $this->currentPage = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        $this->content .= '<form action="/index.php/ModifyQuestion?qid=' . htmlspecialchars($questionData['Num_Ques']) . '" method="post">
            <label for="question">Question:</label>
            <input type="text" id="question" name="question" value="' . htmlspecialchars($questionData['Enonce']) . '" required>
            <br>';

        if ($questionData['Type'] == 'QCU') {
            $this->content .='
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
            <br>
            <label for="correct">Correct answer:</label>
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
                '<label for="orbit">Answer for orbit:</label>
            <input type="text" id="orbit" name="answer" value="' . htmlspecialchars($questionData['BonneRepValeur_orbit']) . '" required>
            <br>
            <label for="rotation">Answer for rotation:</label>
            <input type="text" id="rotation" name="answer" value="' . htmlspecialchars($questionData['BonneRepValeur_rotation']) . '" required>
            <br>
            <input type="submit" value="Submit">
        </form>';
        } elseif ($questionData['Type'] == 'VRAIFAUX') {
            $this->content .=
                '<label for="orbit">Answer for orbit:</label>
            <input type="text" id="orbit" name="orbit" pattern="^-?\d+(\.\d+)?$" title="Please enter a valid float value" value="'.htmlspecialchars($questionData['Valeur_orbit'] == '-1' ?? '', ENT_QUOTES).'">
            <br>
            <label for="rotation">Answer for rotation:</label>
            <input type="text" id="rotation" name="rotation" pattern="^-?\d+(\.\d+)?$" title="Please enter a valid float value" value="'.htmlspecialchars($questionData['Valeur_rotation'] == '-1' ?? '', ENT_QUOTES).'">
            <br>
            <label for="answer">Answer:</label>
            <input type="radio" id="torf" name="answer" value="Vrai" ' . ($questionData['BonneRep'] == 'Vrai' ? 'checked' : '') . '>
            <label for="true">True</label>
            <input type="radio" id="torf" name="answer" value="Faux" ' . ($questionData['BonneRep'] == 'Faux' ? 'checked' : '') . '>
            <label for="false">False</label>
            <br>
            <input type="submit" value="Submit">
        </form>';
        }
    }
}
