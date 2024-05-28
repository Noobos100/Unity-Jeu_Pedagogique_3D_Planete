<?php

namespace gui;

include_once "View.php";
class ViewGame extends View
{
    public function __construct(Layout $layout)
    {
        parent::__construct($layout);

        $this->title = 'Jeu';
        $this->content = '<p>Vous êtes dans le jeu</p>';
    }
}