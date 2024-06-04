<?php

class _add
{

	public function render() {
		ob_start();
		?>
		<form action="/add-question" method="post" class="popup-content">
			<span class="fas fa-circle-xmark" onclick="document.getElementById('add-question-popup').style.display = 'none'"></i></span>
			<h2>Ajouter une question</h2>
			<label for="type">Type:</label>
			<select class="selector" id="type" name="type" required onchange="showFormFields(this.value)">
				<option value="">Sélectionnez un type</option>
				<option value="VRAIFAUX">Vrai ou faux</option>
				<option value="QCU">QCU</option>
				<option value="QUESINTERAC">Question interactive</option>
			</select>
			<div id="form-fields-container">

			</div>
			<input class="submit" type="submit" value="Ajouter">
		</form>
		<?php
		return ob_get_clean();
	}
}