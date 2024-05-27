<?php

namespace gui;

include_once "View.php";

class ViewLogin extends View
{
/**
* Constructs a new ViewLogin instance.
*
* @param Layout $layout The layout to use for displaying content.
*/
public function __construct($layout)
{
parent::__construct($layout);

$this->title = 'Connexion';

// Formulaire de connexion
$this->content .= '
<div class="login-container">
    <form action="/Login" method="post" class="login-form">
        <label for="username">Nom d\'utilisateur:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password" required>
        <input type="submit" value="Se connecter">
    </form>
</div>';
}
}
