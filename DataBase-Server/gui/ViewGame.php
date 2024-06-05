<?php

namespace gui;

use control\ControllerGameData;
use data\DataAccess;

include_once "View.php";

/**
 *
 */
class ViewGame extends View
{
    /**
     * Constructs a new ViewGame instance.
     * @param Layout $layout
     * @param ControllerGameData $controller
     * @param DataAccess $data
     */
    public function __construct(Layout $layout, ControllerGameData $controller, DataAccess $data)
    {
        parent::__construct($layout);

        $bestUsers = $controller->getBestUsers($data);

        $this->title = 'Jeu';

        // Add the button
        ob_start();
        ?>
        <button class="buttonStyle2" id="popupButton">Scores</button>
        <div id="popup" class="popup2" style="display: none;">
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
                        echo '<td>' . htmlspecialchars($user['Username'], ENT_QUOTES, 'UTF-8') . '</td>';
                        echo '<td>' . htmlspecialchars($user['Moy_Questions'], ENT_QUOTES, 'UTF-8') . '</td>';
                        echo '<td>' . htmlspecialchars($user['Temps_Reponse'], ENT_QUOTES, 'UTF-8') . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </table>
            </div>
        </div>
        <script>
            let popup = document.getElementById('popup');
            document.getElementById('popupButton').addEventListener('click', function() {
                popup.style.display = (popup.style.display === 'none' || popup.style.display === '') ? 'block' : 'none';
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

