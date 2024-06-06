<?php

namespace gui;

include_once "View.php";

/**
 * Represents a view in the MVC pattern.
 */
class ViewRandomQuestion extends View
{
    /**
     * Constructs a new ViewRandomQuestion instance.
     *
     * @param Layout $layout The layout to use for displaying content.
     * @param string $jsonRandQ The random question data to display.
     */
    public function __construct($layout, string $jsonRandQ)
    {
        parent::__construct($layout);

        $this->title = 'Random question';
        $this->content = $jsonRandQ;
    }
}
