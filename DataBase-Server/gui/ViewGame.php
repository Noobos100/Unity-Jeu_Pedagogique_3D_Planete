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

		$this->title = 'Jeu';
		$this->content .= '<iframe src="/PlanetGame/index.html" width="800" height="600"></iframe>';


		// Add the button
		$this->content .= '<button id="popupButton">Scores</button>';

		// Add the popup with "Hello World" content
		$this->content .= '
            <div id="popup" class="popup2">
                <div class="popup-content2">
                            <button id="closeButton" >X</button>
                    <h1>Top score</h1>';
		// Afficher le temps minimum
		$totalMinTemps = $controller->calculateTempsMin($parties);
		$this->content .= "<p>Temps minimum de jeu : $totalMinTemps</p>";

		// Afficher le meilleur score
		$bestUsers = $controller->getBestUsers($data);
		$this->content .= '<h2>Meilleurs scores</h2>';
		$this->content .= '<table>';
		$this->content .= '<tr><th>Utilisateur</th><th>Score</th></tr>';
		foreach ($bestUsers as $user) {
			$this->content .= '<tr>';
			$this->content .= '<td>' . $user['Username'] . '</td>';
			$this->content .= '<td>' . $user['Max_Score'] . '</td>';
			$this->content .= '</tr>';
		}


		$this->content .= '
                </div>
            </div>
        ';

		$this->content .= '
<script>
    document.getElementById(\'popupButton\').addEventListener(\'click\', function() {
        var popup = document.getElementById(\'popup\');
        if (popup.style.display === \'none\' || popup.style.display === \'\') {
            popup.style.display = \'block\';
        } else {
            popup.style.display = \'none\';
        }
    });
</script>
';

		$this->content .= '
			<script>
				document.getElementById(\'closeButton\').addEventListener(\'click\', function() {
					var popup = document.getElementById(\'popup\');
					popup.style.display = \'none\';
				});
			</script>
			';
	}
}