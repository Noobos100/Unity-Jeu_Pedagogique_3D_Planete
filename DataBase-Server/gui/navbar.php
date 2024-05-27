<?php

if (isset($_SESSION['loggedin'])) {
    return '    
        <button class="sidebar-button" onclick="location.href=\'/Game\'">Jeu</button>
        <button class="sidebar-button" onclick="location.href=\'/index.php/ManageQuestions\'">Questions</button>
        <button class="sidebar-button" onclick="location.href=\'/index.php/DonneesJeu\'">Données de jeu</button>
        <button class="sidebar-button" onclick="location.href=\'/index.php/Joueurs\'">Joueurs</button>
        <button class="sidebar-button" onclick="location.href=\'/Logout\'">Déconnexion</button>';

} else {
    return ' 
        <button class="sidebar-button" onclick="location.href=\'/Game\'">Jeu</button>
        <button class="sidebar-button" onclick="location.href=\'/Login\'">Connexion</button>';
}
