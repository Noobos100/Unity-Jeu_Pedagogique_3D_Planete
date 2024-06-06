<?php

if (isset($_SESSION['loggedin'])) {
    return '    
        <button class="sidebar-button" onclick="location.href=\'/game\'">Voyage autour du Soleil</button>
        <button class="sidebar-button" onclick="location.href=\'/manage-questions\'">Questions</button>
        <button class="sidebar-button" onclick="location.href=\'/game-data\'">Données de jeu</button>
        <button class="sidebar-button" onclick="location.href=\'/players\'">Joueurs</button>
        <button class="sidebar-button" onclick="location.href=\'/logout\'">Déconnexion</button>';
// ajouter bouton pour ouvrir le manuel d'utilisation du site une fois qu'il sera terminé
} else {
    return ' 
        <button class="sidebar-button" onclick="location.href=\'/game\'">Voyage autour du Soleil</button>
        <button class="sidebar-button" onclick="location.href=\'/login\'">Connexion</button>';
}
