<?php

namespace gui;

include_once "View.php";

class ViewInteractions extends View
{
    /**
     * Constructs a new ViewInteractions instance.
     *
     * @param Layout $layout The layout to use for displaying content.
     * @param string $interaction The interaction to display in the view.
     */
    public function __construct(Layout $layout, string $interaction)
    {
        parent::__construct($layout);

        $this->title = 'Ajout Interaction';

        $this->content = "<p>L'interaction $interaction a bien été enregistrée</p>";
    }
}
