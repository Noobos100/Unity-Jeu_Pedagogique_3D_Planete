<?php

if (isset($_SESSION['loggedin'])) {
    return '    
        <button class="sidebar-button" onclick="location.href=\'/game\'">Jeu</button>
        <button class="sidebar-button" onclick="location.href=\'/manage-questions\'">Questions</button>
        <button class="sidebar-button" onclick="location.href=\'/game-data\'">Données de jeu</button>
        <button class="sidebar-button" onclick="location.href=\'/players\'">Joueurs</button>
        <button class="sidebar-button" onclick="location.href=\'/logout\'">Déconnexion</button>';

} else {
    return ' 
        <button class="sidebar-button" onclick="location.href=\'/game\'">Jeu</button>
        <button class="sidebar-button" onclick="location.href=\'/login\'">Connexion</button>';
}
