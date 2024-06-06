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
        $this->title = 'Jeu';
        // Add the button
        ob_start();
        ?>
        <button class="btn" id="scoreBtn">Scores</button>
        <div class="iframe-container">
            <iframe src="https://jeupedagogique.pq.lu"></iframe>
        </div>
        <script type="module" src="/assets/js/game.js"></script>
        <?php

        $this->content .= ob_get_clean();
    }
}

