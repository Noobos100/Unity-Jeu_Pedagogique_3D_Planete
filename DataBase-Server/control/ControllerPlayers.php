<?php

namespace control;

include_once 'gui/charts/ViewPlatform.php';

use DateTime;
use gui\charts\ViewPlatform;

class ControllerPlayers
{
    public function getPlayers($data)
    {
        return $data->getPlayers();
    }

    public function generateTable($data): string
    {
        if (empty($data)) {
            return ''; // Si la liste est vide, retourner une chaîne vide
        }

        // Récupérer les clés (identifiants) du premier élément de la liste
        $keys = array_keys($data[0]);

        // Générer le tableau HTML
        $table = '<h3>Données</h3>';
        $table .= '<table border="1"><tr>';
        foreach ($keys as $key) {
            $table .= "<th>$key</th>";
        }
        $table .= '</tr>';
        foreach ($data as $item) {
            $table .= '<tr>';
            foreach ($keys as $key) {
                $table .= "<td>{$item[$key]}</td>";
            }
            $table .= '</tr>';
        }
        $table .= '</table>';

        return $table;
    }

    public function generateChartPlatforme($joueurs): string
	{
        // Préparer les données pour le graphique
        $platformCount = [];
        foreach ($joueurs as $joueur) {
            if ($joueur['Plateforme'] == null) {
                $platform = 'Non spécifié';
            } else {
                $platform = $joueur['Plateforme'];
            }

            if (!isset($platformCount[$platform])) {
                $platformCount[$platform] = 0;
            }
            $platformCount[$platform]++;
        }
		// Générer le code HTML et JavaScript pour le graphique
		return (new ViewPlatform($platformCount))->render();
    }
}