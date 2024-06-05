<?php

if (isset($_SESSION['loggedin'])) {
    return '    
        <button class="sidebar-button" onclick="location.href=\'/game\'">Voyage autour du Soleil</button>
        <button class="sidebar-button" onclick="location.href=\'/manage-questions\'">Questions</button>
        <button class="sidebar-button" onclick="location.href=\'/game-data\'">Données de jeu</button>
        <button class="sidebar-button" onclick="location.href=\'/players\'">Joueurs</button>
        <button class="sidebar-button" onclick="location.href=\'/logout\'">Déconnexion</button>';

} else {
    return ' 
        <button class="sidebar-button" onclick="location.href=\'/game\'">Voyage autour du Soleil</button>
        <p class="sidebar-text" >Bienvenue sur Voyage autour du Soleil dans ce jeu vous pourrait découvrit en vous amusant </p>
        <button class="sidebar-button" onclick="location.href=\'/login\'">Connexion</button>';
}
