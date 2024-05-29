<?php

namespace control;

use DateTime;

class ControllerJoueurs
{
    public function getJoueurs($data)
    {
        return $data->getJoueurs();
    }

    public function getParties($data)
    {
        return $data->getParties();
    }

    public function getReponsesUsers($data)
    {
        return $data->getReponsesUsers();
    }

    public function generateChart($joueurs)
    {
        // Préparer les données pour le graphique
        $platformCount = [];
        foreach ($joueurs as $joueur) {
            if ($joueur['Plateforme'] == null) {
                $platform = 'Non spécifié';
            }else {
                $platform = $joueur['Plateforme'];
            }

            if (!isset($platformCount[$platform])) {
                $platformCount[$platform] = 0;
            }
            $platformCount[$platform]++;
        }

        $labels = array_keys($platformCount);
        $data = array_values($platformCount);

        // Convertir les tableaux en chaînes JSON pour JavaScript
        $labelsJSON = json_encode($labels);
        $dataJSON = json_encode($data);

        // Générer le code HTML et JavaScript pour le graphique
        $chart = '
            <canvas id="myChart"></canvas>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                const ctx = document.getElementById(\'myChart\').getContext(\'2d\');
                new Chart(ctx, {
                    type: \'pie\',
                    data: {
                        labels: ' . $labelsJSON . ',
                        datasets: [{
                            label: \'Nombre de joueurs par plateforme\',
                            data: ' . $dataJSON . ',
                            backgroundColor: [
                                \'rgba(255, 99, 132, 0.75)\',
                                \'rgba(54, 162, 235, 0.75)\',
                                \'rgba(255, 206, 86, 0.75)\',
                                \'rgba(75, 192, 192, 0.75)\',
                                \'rgba(153, 102, 255, 0.75)\',
                                \'rgba(255, 159, 64, 0.75)\'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        plugins: {
                            legend: {
                                labels: {
                                    color: \'#ffffff\' // Couleur des étiquettes de la légende
                                }
                            },
                            tooltip: {
                                backgroundColor: \'rgba(0,0,0,0.8)\',
                                titleFontColor: \'#ffffff\',
                                bodyFontColor: \'#ffffff\',
                                footerFontColor: \'#ffffff\'
                            }
                        }
                    }
                });
            </script>
        ';

        return $chart;
    }

    public function generateTable($data)
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

    public function calculateTotalAbandons($reponseUser)
    {
        // Compter le nombre total d'abandons
        $totalAbandons = 0;
        foreach ($reponseUser as $user) {
            $totalAbandons += $user['Abandon'] ?? 0;
        }
        return $totalAbandons;
    }

    public function calculateTempsMin($reponseUser)
    {
        $totalMaxTemps = 0;
        foreach ($reponseUser as $user) {
            if (isset($user['Date_Deb']) && isset($user['Date_Fin']) && $user['Date_Fin'] != null && $user['Date_Deb'] != null){
                $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $user['Date_Deb']);
                $dateTime2 = DateTime::createFromFormat('Y-m-d H:i:s', $user['Date_Fin']);

                $interval = $dateTime->diff($dateTime2);
                $totalMaxTemps2 = $interval->format('%H') * 3600 + $interval->format('%I') * 60 + $interval->format('%S');

                if ($totalMaxTemps2 < $totalMaxTemps){
                    $totalMaxTemps = $totalMaxTemps2;
                }
            }
        }
        return gmdate("H:i:s", $totalMaxTemps);
    }

    public function calculateTempsMax($reponseUser)
    {
        $totalMaxTemps = 0;
        foreach ($reponseUser as $user) {
            if (isset($user['Date_Deb']) && isset($user['Date_Fin']) && $user['Date_Fin'] != null && $user['Date_Deb'] != null){
                $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $user['Date_Deb']);
                $dateTime2 = DateTime::createFromFormat('Y-m-d H:i:s', $user['Date_Fin']);

                $interval = $dateTime->diff($dateTime2);
                $totalMaxTemps2 = $interval->format('%H') * 3600 + $interval->format('%I') * 60 + $interval->format('%S');

                if ($totalMaxTemps2 > $totalMaxTemps){
                    $totalMaxTemps = $totalMaxTemps2;
                }
            }
        }
        return gmdate("H:i:s", $totalMaxTemps);
    }

}