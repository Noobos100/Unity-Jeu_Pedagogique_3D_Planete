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

		$bestUsers = $controller->getBestUsers($data);

		$this->title = 'Jeu';

		// Add the button
		ob_start();
		?>
		<button class="buttonStyle" id="popupButton">Scores</button>
		<div id="popup" class="popup2">
			<div class="popup-content2">
				<button class="fas fa-circle-xmark" id="closeButton"></button>
				<h1>Meilleurs scores</h1>
				<h2>Top 10 :</h2>
				<table>
				<tr>
					<th>Utilisateur</th>
					<th>Score</th>
                    <th>Temps</th>
				</tr>
				<?php
				foreach ($bestUsers as $user) {
					echo '<tr>';
					echo '<td>' . $user['Username'] . '</td>';
					echo '<td>' . $user['Moy_Questions'] . '</td>';
                    echo '<td>' . $user['Temps_Reponse'] . '</td>';
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
        <div class="iframe-container">
            <iframe src="https://jeupedagogique.pq.lu"></iframe>
        </div>

        <?php

		$this->content .= ob_get_clean();
	}
}