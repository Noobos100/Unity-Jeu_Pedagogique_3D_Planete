<?php

namespace gui;

/**
 * Represents a layout for displaying content.
 */
class Layout
{
    protected $templateFile;

    protected $navbar = '';

    /**
     * Constructs a new Layout instance.
     *
     * @param string $templateFile The path to the template file.
     */
    public function __construct($templateFile)
    {
        $this->templateFile = $templateFile;

        $this->navbar = require('navbar.php');
    }

    /**
     * Displays the layout with the specified title and content.
     *
     * @param string $title The title of the layout.
     * @param string $content The content to display.
     * @return void
     */
    public function display($title, $content)
    {
        $page = file_get_contents($this->templateFile);
        $page = str_replace(['%title%', '%content%', '%navbar%'], [$title, $content, $this->navbar], $page);
        echo $page;
    }
}
