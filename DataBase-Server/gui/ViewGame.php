<?php

namespace gui;

include_once "View.php";
class ViewGame extends View
{
    public function __construct(Layout $layout)
    {
        parent::__construct($layout);

        $this->title = 'Jeu';
//		$this->content = '';
        $this->content = '<iframe src="/PlanetGame/index.html" width="800" height="600"></iframe>';
    }
}