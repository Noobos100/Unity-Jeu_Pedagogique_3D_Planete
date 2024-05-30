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

        $this->content .= '<form action="/modify-question?qid=' . $questionData['Num_Ques'] . '" method="post">
            <label for="question">Question ' . $questionData['Num_Ques'] . ' (' . $questionData['Type'] . ')'.':</label>
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
            <label for="current">Bonne réponse (actuellement):</label>
            <input type="text" id="current" name="current" value="' . $questionData['BonneRep'] . '" readonly> 
            <br>
            <label for="correct">Changer bonne réponse:</label>
            <select id="correct" name="correct" required>
                <option value="Rep1">Option 1</option>
                <option value="Rep2">Option 2</option>
                <option value="Rep3">Option 3</option>
                <option value="Rep4">Option 4</option>
            </select>
            <br>
            <input type="submit" value="Confirmer changements">
        </form>';
        }
        elseif ($questionData['Type'] == 'QUESINTERAC') {
            $this->content .=
            '<label for="orbit">Réponse pour orbite:</label>
            <input type="text" id="orbit" name="orbit" value="' . $questionData['BonneRepValeur_orbit'] . '">
            <input id="orbitable" type="checkbox" ' . (($questionData['BonneRepValeur_orbit']) == '-1' ? 'checked' : '') . '>
            
            <br>
            <label for="rotation">Réponse pour rotation:</label>
            <input type="text" id="rotation" name="rotation" value="' . $questionData['BonneRepValeur_rotation'] . '">
            <input id="rotatable" type="checkbox" ' . (($questionData['BonneRepValeur_rotation']) == '-1' ? 'checked' : '') . '>
            <br>
            
            <label for="marge-orbit">Marge orbite:</label>
            <input type="text" id="margin-orbit" name="margin-orbit" value="' . $questionData['Marge_Orbit'] . '">
            <input id="orbit-margin" type="checkbox" ' . (($questionData['Marge_Orbit']) == '-1' ? 'checked' : '') . '>

            <br>
            <label for="marge-rotation">Marge rotation:</label>
            <input type="text" id="margin-rotation" name="margin-rotation" value="' . $questionData['Marge_Rotation'] . '">
            <input id="rotation-margin" type="checkbox" ' . (($questionData['Marge_Rotation']) == '-1' ? 'checked' : '') . '>
            
            <br>
            <input type="submit" value="Confirmer changements">
            </form>';
        }
        elseif ($questionData['Type'] == 'VRAIFAUX') {
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
				<label for="true">Vrai</label>
				<input type="radio" id="torf" name="answer" value="Faux" ' . ($questionData['BonneRep'] == 'Faux' ? 'checked' : '') . '>
				<label for="false">Faux</label>
				<button id="submitBtn">Confirmer changements</button>
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

        $this->content .= '
        <button onclick="confirmLeave()">Annuler</button>
        <script src="/assets/js/modifyquestion.js"></script>';
    }
}
