<?php

class _questionInteractive
{
	private string $enonce;
    private string $orbit;
    private string $marginOrbit;
    private string $rotation;
    private string $marginRotation;

    public function __construct(string $enonce = "",
                                string $orbit = "",
                                string $marginOrbit = "",
                                string $rotation = "",
                                string $marginRotation = "")
    {
        $this->enonce = $enonce;
        $this->orbit = $orbit;
        $this->marginOrbit = $marginOrbit;
        $this->rotation = $rotation;
        $this->marginRotation = $marginRotation;
    }

	public function render() {
		ob_start();
		?>
        <label for="question">Enoncé:</label>
        <input type="text" class="input-question" id="question" name="question" value="<?= $this->enonce ?>" required>
        <label for="orbit">Réponse orbite:</label>
        <input type="text" class="input-text" id="orbit" name="orbit" value="<?= $this->orbit ?>" required>
        <label for="margin-orbit">Marge orbite:</label>
        <input type="text" class="input-text" id="margin-orbit" name="margin-orbit" value="<?= $this->marginOrbit ?>" required>

        <label for="rotation">Réponse rotation:</label>
        <input type="text" class="input-text" id="rotation" name="rotation" value="<?= $this->rotation ?>" required>
        <label for="margin-rotation">Marge rotation:</label>
        <input type="text" class="input-text" id="margin-rotation" name="margin-rotation" value="<?= $this->marginRotation ?>" required>
        <?php
		return ob_get_clean();
	}
}