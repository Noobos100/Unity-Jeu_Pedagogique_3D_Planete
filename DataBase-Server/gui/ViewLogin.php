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
    <form action="/index.php" method="post" class="login-form">
        <label for="username">Nom d\'utilisateur:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password" required>
        <input type="submit" value="Se connecter">
    </form>
</div>';


$this->content .= '<style>
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        background: url("../Assets/Image/espace.jpg") no-repeat center center fixed;
        background-size: cover;
    }
    .login-container {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 300px;
        padding: 20px;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }
    .login-form {
        display: flex;
        flex-direction: column;
    }
    .login-form label {
        color: white;
        margin-bottom: 10px;
        font-size: 18px;
    }
    .login-form input[type="text"],
    .login-form input[type="password"] {
        padding: 10px;
        margin-bottom: 10px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
    }
    .login-form input[type="submit"] {
        padding: 10px;
        border: none;
        border-radius: 5px;
        background-color: #1a1a1a;
        color: white;
        font-size: 18px;
        cursor: pointer;
    }
    .login-form input[type="submit"]:hover {
        background-color: #333;
    }
</style>';
}
}
