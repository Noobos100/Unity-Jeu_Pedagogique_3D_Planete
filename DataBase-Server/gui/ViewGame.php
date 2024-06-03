<?php

namespace gui;

use control\ControllerGameData;
use data\DataAccess;

include_once "View.php";

class ViewGame extends View
{
	public function __construct(Layout $layout, ControllerGameData $controller, DataAccess $data)
	{
		parent::__construct($layout);

		$parties = $controller->getParties($data);
		$totalMinTemps = $controller->calculateTempsMin($parties);
		$bestUsers = $controller->getBestUsers($data);

		$this->title = 'Jeu';

		// Add the button
		ob_start();
		?>
		<button id="popupButton">Scores</button>
		<div id="popup" class="popup2">
			<div class="popup-content2">
				<button id="closeButton" class="fas fa-pen"></button>
				<h1>Top score</h1>
				<p>Temps minimum de jeu : <?php echo $totalMinTemps ?></p>
				<h2>Meilleurs scores</h2>
				<table>
				<tr>
					<th>Utilisateur</th>
					<th>Score</th>
				</tr>
				<?php
				foreach ($bestUsers as $user) {
					echo '<tr>';
					echo '<td>' . $user['Username'] . '</td>';
					echo '<td>' . $user['Max_Score'] . '</td>';
					echo '</tr>';
				}
				?>
				</table>
			</div>
		</div>
		<script>
          	let popup = document.getElementById('popup');
			document.getElementById('popupButton').addEventListener('click', function() {
				if (popup.style.display === 'none' || popup.style.display === '') {
					popup.style.display = 'block';
				} else {
					popup.style.display = 'none';
				}
			});
			document.getElementById('closeButton').addEventListener('click', function() {
				popup.style.display = 'none';
			});
		</script>
		<iframe src="https://jeupedagogique.pq.lu" width="960" height="540"></iframe>
<?php

		$this->content .= ob_get_clean();
	}
}