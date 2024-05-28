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
        // Déterminer la page actuelle
        $this->currentPage = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        $this->content .= '<form action="/index.php/ModifyQuestion?qid=' . $questionData['Num_Ques'] . '" method="post">
            <label for="question">Question:</label>
            <input type="text" id="question" name="question" value="' . $questionData['Enonce'] . '" required>
            <br>';

        if ($questionData['Type'] == 'QCU') {
            $this->content .='
            <label for="option1">Option 1:</label>
            <input type="text" id="option1" name="option1" value="' . $questionData['Rep1'] . '" required>
            <br>
            <label for="option2">Option 2:</label>
            <input type="text" id="option2" name="option2" value="' . $questionData['Rep2'] . '" required>
            <br>
            <label for="option3">Option 3:</label>
            <input type="text" id="option3" name="option3" value="' . $questionData['Rep3'] . '" required>
            <br>
            <label for="option4">Option 4:</label>
            <input type="text" id="option4" name="option4" value="' . $questionData['Rep4'] . '" required>
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
            <input type="text" id="current" name="current" value="' . $questionData['BonneRep'] . '" readonly> 
            <br>
            <input type="submit" value="Submit changes">
        </form>';
        } elseif ($questionData['Type'] == 'QUESINTERAC') {
            $this->content .=
                '<label for="orbit">Answer for orbit:</label>
            <input type="text" id="orbit" name="answer" value="' . $questionData['BonneRepValeur_orbit'] . '" required>
            <br>
            <label for="rotation">Answer for rotation:</label>
            <input type="text" id="rotation" name="answer" value="' . $questionData['BonneRepValeur_rotation'] . '" required>
            <br>
            <input type="submit" value="Submit">
        </form>';
        } elseif ($questionData['Type'] == 'VRAIFAUX') {
			$this->content .= '
				<label for="forbit">Position de l\'orbite:</label>
				<div class="checkboxed-question">
					<input id="orbitable" type="checkbox" '. (($questionData['Valeur_orbit']) == '-1' ? 'checked' : '') .'>
					<input type="number" id="orbit" name="orbit" 
					value="'.($questionData['Valeur_orbit'] == '-1' ? '' : $questionData['Valeur_orbit']). '"
					min="0" max="1" step="0.01">
				</div>
				<label for="rotation">Position de la rotation:</label>
				<div class="checkboxed-question">
					<input id="rotatable" type="checkbox" '. (($questionData['Valeur_rotation']) == '-1' ? 'checked' : '') .'>
					<input type="number" id="rotation" name="rotation" 
					value="' .($questionData['Valeur_rotation'] == '-1' ? '' : $questionData['Valeur_rotation']).'"
					min="0" max="1" step="0.01">
				</div>
				<label for="answer">Réponse:</label>
				<input type="radio" id="torf" name="answer" value="Vrai" ' . ($questionData['BonneRep'] == 'Vrai' ? 'checked' : '') . '>
				<label for="true">True</label>
				<input type="radio" id="torf" name="answer" value="Faux" ' . ($questionData['BonneRep'] == 'Faux' ? 'checked' : '') . '>
				<label for="false">False</label>
				<button id="submitBtn">Envoyer</button>
			</form>
			<script>
				const isOrbitable = document.getElementById("orbitable");
        		const isRotatable = document.getElementById("rotatable");
            	const inputOrbit = document.getElementById("orbit");
              	const inputRotation = document.getElementById("rotation");
				inputOrbit.disabled = isOrbitable.checked;
				inputRotation.disabled = isRotatable.checked;
                
                isOrbitable.addEventListener("change", () => {
					inputOrbit.disabled = isOrbitable.checked;
          			inputOrbit.value = "";
				})
				
				isRotatable.addEventListener("change", () => {
					inputRotation.disabled = isRotatable.checked;
          			inputRotation.value = "";
				})
			</script>
			';

        }
    }
}
