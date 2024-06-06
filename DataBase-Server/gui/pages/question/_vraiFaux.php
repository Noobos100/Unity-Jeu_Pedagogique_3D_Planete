<?php

class _vraiFaux
{
	private string $question;
	private string $orbit;
	private string $rotation;
	private string $answer;

    /**
     * Constructs a new _vraiFaux instance.
     *
     * @param string $question The question.
     * @param string $orbit The orbit.
     * @param string $rotation The rotation.
     * @param string $answer The answer.
     */
	function __construct($question = "", $orbit = "", $rotation = "", $answer = "")
	{
		$this->question = $question;
		$this->orbit = $orbit;
		$this->rotation = $rotation;
		$this->answer = $answer;
	}

    /**
     * Renders the question creation form.
     *
     * @return string The rendered question creation form.
     */
	public function render() {
		ob_start();
		?>
		<label for="question">Enoncé:</label>
		<input type="text" id="question" name="question" class="input-question" value="<?=$this->question?>" required>
		<label for="orbit">Orbite (optionnel):</label>
		<input type="text"
               id="orbit" name="orbit" class="input-text"
               value="<?=$this->orbit != "-1" ? $this->orbit : ""?>">
		<label for="rotation">Rotation (optionnel):</label>
		<input type="text"
               id="rotation" name="rotation" class="input-text"
               value="<?=$this->rotation != "-1" ? $this->rotation : ""?>">
		<label for="answer">Réponse:</label>
		<select class="selector" id="answer" name="answer" required>
			<option value="Vrai" <?= $this->answer == "Vrai" ? "selected" : "" ?>>Vrai</option>
			<option value="Faux" <?= $this->answer == "Faux" ? "selected" : "" ?>>Faux</option>
		</select>
		<?php
		return ob_get_clean();
	}

}