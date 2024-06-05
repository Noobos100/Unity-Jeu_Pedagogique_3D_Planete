<?php

namespace gui;

/**
 * Vue pour gérer les questions (supression, modification, etc...)
 */
class ViewManageQuestions extends View
{
	/**
	 * @param Layout $layout
	 * @param mixed $questions
	 */
	public function __construct(Layout $layout, mixed $questions)
	{
		parent::__construct($layout);

		$this->title = 'Gestion des questions';

		$questions = json_decode($questions, true);

		ob_start();
		?>
        <h1>Gestionnaire des questions</h1>
        <link href="/assets/css/managequestion.css" rel="stylesheet"/>
        <div id="add-question-popup" class="popup">
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
                <div id="form-fields-container"></div>
                <input class="submit" type="submit" value="Ajouter">
            </form>
        </div>
        <button class="buttonStyle" onclick="document.getElementById('add-question-popup').style.display = 'block'">Ajouter une question</button>
        <label for="filter">Filtrer par type :</label>
        <select class="selector" id="filter" onchange="filterQuestions()">
            <option value="all">Tous</option>
            <option value="VRAIFAUX">Vrai ou faux</option>
            <option value="QCU">QCU</option>
            <option value="QUESINTERAC">Question interactive</option>
        </select>
        <table id="question-table">
            <tr>
                <th>ID</th>
                <th>Enoncé</th>
                <th>Type</th>
            </tr>

			<?php
			foreach ($questions as $question) {
				?>
                <tr class="question-row">
                    <td><?php echo htmlspecialchars($question['Num_Ques']) ?></td>
                    <td><?php echo htmlspecialchars($question['Enonce']) ?></td>
                    <td><?php echo htmlspecialchars($question['Type']) ?></td>
                    <td>
                        <button class="fas fa-pencil" onclick="location.href='modify-question?qid=<?php echo $question['Num_Ques'] ?>'"></button>
                    </td>
                    <td>
                        <button class="fas fa-trash-can" onclick="deleteQuestion('<?php echo $question['Num_Ques'] ?>')"></button>
                    </td>
                </tr>
				<?php
			}
			?>
        </table>
        <script src="/assets/js/managequestion.js"></script>';
		<?php
		$this->content = ob_get_clean();
	}
}