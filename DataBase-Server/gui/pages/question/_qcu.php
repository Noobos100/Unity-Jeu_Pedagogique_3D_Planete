<?php

class _qcu
{
	private string $question;
	private string $rep1;
	private string $rep2;
    private string $rep3;
    private string $rep4;
    private string $answer;

	public function __construct(string $question = "",
                                string $rep1 = "",
                                string $rep2 = "",
                                string $rep3 = "",
                                string $rep4 = "",
                                string $answer = "")
    {
        $this->question = $question;
        $this->rep1 = $rep1;
        $this->rep2 = $rep2;
        $this->rep3 = $rep3;
        $this->rep4 = $rep4;
        $this->answer = $answer;
    }

	public function render(): string
	{
		ob_start();
		?>
        <label for="question">Question:</label>
        <input type="text" id="question" class="input-question" name="question" value="<?= $this->question ?>" required>
        <label for="option1">Option 1:</label>
        <input type="text" id="option1" class="input-text" name="option1" value="<?= $this->rep1 ?>" required>
        <label for="option2">Option 2:</label>
        <input type="text" id="option2" class="input-text" name="option2" value="<?= $this->rep2 ?>" required>
        <label for="option3">Option 3:</label>
        <input type="text" id="option3" class="input-text" name="option3" value="<?= $this->rep3 ?>" required>
        <label for="option4">Option 4:</label>
        <input type="text" id="option4" class="input-text" name="option4" value="<?= $this->rep4 ?>" required>
        <label for="answer">Correct Answer:</label>
        <select class="selector" id="answer" name="answer" required>
            <option value="Rep1" <?=($this->answer == $this->rep1) ? "selected" : ""?>>Option 1</option>
            <option value="Rep2" <?=($this->answer == $this->rep2) ? "selected" : ""?>>Option 2</option>
            <option value="Rep3" <?=($this->answer == $this->rep3) ? "selected" : ""?>>Option 3</option>
            <option value="Rep4" <?=($this->answer == $this->rep4) ? "selected" : ""?>>Option 4</option>
        </select>
		<?php
		return ob_get_clean();
	}

}