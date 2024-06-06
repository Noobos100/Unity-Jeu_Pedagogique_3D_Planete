<?php

if (isset($_SESSION['loggedin'])) {
    return '    
        <button class="sidebar-button" onclick="location.href=\'/game\'">Voyage autour du Soleil</button>
        <button class="sidebar-button" onclick="location.href=\'/manage-questions\'">Questions</button>
        <button class="sidebar-button" onclick="location.href=\'/game-data\'">Données de jeu</button>
        <button class="sidebar-button" onclick="location.href=\'/players\'">Joueurs</button>
        <button class="sidebar-button" onclick="window.open(\'https://jeupedagogique.pq.lu/manuals/Manuel_dutilisation_dashboard.pdf\', \'_blank\')">Manuel du dashboard</button>
        <button class="sidebar-button" onclick="location.href=\'/logout\'">Déconnexion</button>';

} else {
    return ' 
        <button class="sidebar-button" onclick="location.href=\'/game\'">Voyage autour du Soleil</button>
        <button class="sidebar-button" onclick="location.href=\'/login\'">Connexion</button>
        <button class="sidebar-button" onclick="window.open(\'https://jeupedagogique.pq.lu/manuals/Manuel_dutilisation_windows.pdf\', \'_blank\')">Manuel du jeu</button>';
}
