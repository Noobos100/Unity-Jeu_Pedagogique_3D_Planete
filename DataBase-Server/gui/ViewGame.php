<?php

namespace gui;

include_once "View.php";
class ViewGame extends View
{
    public function __construct($layout)
    {
        parent::__construct($layout);

        $this->title = 'Jeu';
        $this->content = '<p>Vous êtes dans le jeu</p>';

        $this->content .= '<style>
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        background: url("../Assets/Image/image2.jpg") no-repeat center center fixed;
        background-size: cover;
    }
    
    </style>';

    }

}