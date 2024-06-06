<?php

namespace gui;

/**
 * Vue pour gérer les questions (supression, modification, etc...)
 */
class ViewManageQuestions extends View
{
	/**
     * Construit une nouvelle instance de ViewManageQuestions.
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
        <h1 class="h1-title">Gestionnaire des questions</h1>
        <div id="filter-container">
        <!--filtre-->
            <div>
                <label for="filter">Filtrer par type :</label>
                <select class="selector" id="filter">
                    <option value="all">Tous</option>
                    <option value="VRAIFAUX">Vrai ou faux</option>
                    <option value="QCU">QCU</option>
                    <option value="QUESINTERAC">Question interactive</option>
                </select>
            </div>
            <!--bouton pour ajouter une question-->
            <button id="addQuestionBtn" class="btn add">Ajouter une question</button>
        </div>
        <!--tableau des questions-->
        <table id="question-table">
            <tr>
                <th>ID</th>
                <th>Enoncé</th>
                <th>Type</th>
            </tr>

			<?php
			foreach ($questions as $question) {
				?>
                <tr class="question-row" data-qid="<?=$question['Num_Ques']?>">
                    <td><?php echo htmlspecialchars($question['Num_Ques']) ?></td>
                    <td><?php echo htmlspecialchars($question['Enonce']) ?></td>
                    <td><?php echo htmlspecialchars($question['Type']) ?></td>
                    <td>
                        <button class="fas fa-pencil edit"></button>
                    </td>
                    <td>
                        <button class="fas fa-trash-can delete"></button>
                    </td>
                </tr>
				<?php
			}
			?>
        </table>
        <script type="module" src="/assets/js/managequestion.js"></script>';
		<?php
		$this->content = ob_get_clean();
	}
}