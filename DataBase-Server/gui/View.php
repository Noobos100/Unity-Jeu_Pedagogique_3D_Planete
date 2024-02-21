<?php

namespace gui;

include_once "Layout.php";

class View
{
    protected $title = '';
    protected $content = '';
    protected $layout;
    public function __construct($layout){
        $this->layout = $layout;
    }
    public function display(){
        $this->layout->display($this->title, $this->content);
    }

}