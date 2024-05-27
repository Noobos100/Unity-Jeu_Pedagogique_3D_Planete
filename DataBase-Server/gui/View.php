<?php

namespace gui;

include_once "Layout.php";

class View
{
    protected $title = '';
    protected $content = '';
    protected $layout;


    /**
     * Constructs a new View instance.
     *
     * @param Layout $layout The layout to use for displaying content.
     */
    public function __construct($layout)
    {
        $this->layout = $layout;


    }

    /**
     * Displays the view using the layout.
     *
     * @return void
     */
    public function display()
    {
        $this->layout->display($this->title, $this->content);
    }
}
