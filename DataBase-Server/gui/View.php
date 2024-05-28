<?php

namespace gui;

include_once "Layout.php";

class View
{
    protected string $title = '';
    protected string $content = '';
    protected Layout $layout;


    /**
     * Constructs a new View instance.
     *
     * @param Layout $layout The layout to use for displaying content.
     */
    public function __construct(Layout $layout)
    {
        $this->layout = $layout;


    }

    /**
     * Displays the view using the layout.
     *
     * @return void
     */
    public function display(): void
	{
        $this->layout->display($this->title, $this->content);
    }
}
