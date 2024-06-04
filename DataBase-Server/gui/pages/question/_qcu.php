<?php

class _qcu
{
	private string $question;
	private string $orbit;
	private string $rotation;
    private string $answer;

	function __construct($question = "", $orbit = "", $rotation = "", $answer = "")
       {
            $this->question = $question;
            $this->orbit = $orbit;
            $this->rotation = $rotation;
            $this->answer = $answer;
       }

	public function render(): string
	{
		ob_start();
		?>
        <label for="enonce">Enoncé:</label>
        <input type="text" id="enonce" name="enonce" value="<?php echo $this->question ?>" required>
        <label for="orbit">Orbite (optionnel):</label>
        <input type="text" id="orbit" name="orbit" value="<?php echo $this->orbit ?>">
        <label for="rotation">Rotation (optionnel):</label>
        <input type="text" id="rotation" name="rotation" value="<?php echo $this->rotation ?>">
        <label for="reponse">Réponse:</label>
        <select class="selector" id="reponse" name="reponse" required>
            <option value="Vrai" <?php if ($this->answer === "Vrai") echo "selected" ?>>Vrai</option>
            <option value="Faux" <?php if ($this->answer === "Faux") echo "selected" ?>>Faux</option>
        </select>
		<?php
		return ob_get_clean();
	}

}