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

    public function getPartiesAsc($data)
    {
        return $data->getPartiesAsc();
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
        $totalMinTemps = PHP_INT_MAX;
        foreach ($reponseUser as $user) {
            if (isset($user['Date_Deb']) && isset($user['Date_Fin']) && $user['Date_Fin'] != null && $user['Date_Deb'] != null) {
                $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $user['Date_Deb']);
                $dateTime2 = DateTime::createFromFormat('Y-m-d H:i:s', $user['Date_Fin']);

                $interval = $dateTime->diff($dateTime2);
                $totalMinTemps2 = $interval->format('%H') * 3600 + $interval->format('%I') * 60 + $interval->format('%S');

                if ($totalMinTemps2 < $totalMinTemps) {
                    $totalMinTemps = $totalMinTemps2;
                }
            }
        }
        return gmdate("H:i:s", $totalMinTemps);
    }

    public function calculateTempsMax($reponseUser)
    {
        $totalMaxTemps = 0;
        foreach ($reponseUser as $user) {
            if (isset($user['Date_Deb']) && isset($user['Date_Fin']) && $user['Date_Fin'] != null && $user['Date_Deb'] != null) {
                $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $user['Date_Deb']);
                $dateTime2 = DateTime::createFromFormat('Y-m-d H:i:s', $user['Date_Fin']);

                $interval = $dateTime->diff($dateTime2);
                $totalMaxTemps2 = $interval->format('%H') * 3600 + $interval->format('%I') * 60 + $interval->format('%S');

                if ($totalMaxTemps2 > $totalMaxTemps) {
                    $totalMaxTemps = $totalMaxTemps2;
                }
            }
        }
        return gmdate("H:i:s", $totalMaxTemps);
    }

    public function generateChart($joueurs)
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

        $labels = array_keys($platformCount);
        $data = array_values($platformCount);

        // Convertir les tableaux en chaînes JSON pour JavaScript
        $labelsJSON = json_encode($labels);
        $dataJSON = json_encode($data);

        // Générer le code HTML et JavaScript pour le graphique
        $chart = '
            <canvas id="myChart"></canvas>
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

    public function generateChart2($reponsesUsers)
    {
        // Préparer les données pour le graphique
        $questionData = [];
        foreach ($reponsesUsers as $reponse) {
            $questionNum = $reponse['Num_Ques'];
            if (!isset($questionData[$questionNum])) {
                $questionData[$questionNum] = [
                    'attempts' => 0,
                    'correct' => 0
                ];
            }
            $questionData[$questionNum]['attempts']++;
            if ($reponse['Reussite']) {
                $questionData[$questionNum]['correct']++;
            }
        }

        $labels = [];
        $corrects = [];
        $attempts = []; // Ajout d'un tableau pour stocker le nombre de tentatives par question
        foreach ($questionData as $numQues => $data) {
            $labels[] = "Question $numQues";
            $corrects[] = $data['correct'];
            $attempts[] = $data['attempts']; // Ajouter le nombre de tentatives au tableau
        }

        // Convertir les tableaux en chaînes JSON pour JavaScript
        $labelsJSON = json_encode($labels);
        $correctsJSON = json_encode($attempts);
        $attemptsJSON = json_encode($corrects); // Convertir le tableau des tentatives en JSON

        // Générer le code HTML et JavaScript pour le graphique
        $chart = '
<canvas id="myChart2"></canvas>
<script>
    const ctx2 = document.getElementById(\'myChart2\').getContext(\'2d\');
    const data2 = {
        labels: ' . $labelsJSON . ',
        datasets: [{
            type: \'bar\',
            label: \'Nombre de tentatives par question\',
            data: ' . $correctsJSON . ',
            backgroundColor: \'rgba(50, 50, 255, 0.75)\',
            borderWidth: 1
        },
        {
            type: \'bar\',
            label: \'Nombre de bonnes réponses par question\',
            data: ' . $attemptsJSON . ',
            backgroundColor: \'rgba(50, 255, 50, 0.75)\', // Couleur de la ligne
            borderWidth: 1
        }]
    };
    const options2 = {
        scales: {
            x: {
                title: {
                    display: true,
                    color: \'#ffffff\'
                },
                ticks: {
                    color: \'#ffffff\'               
                }
            },
            y: {
                title: {
                    display: true,
                    color: \'#ffffff\'
                },
                ticks: {
                    color: \'#ffffff\'
                }
            }
        },
        plugins: {
            legend: {
                labels: {
                    color: \'#ffffff\'
                }
            },
            tooltip: {
                backgroundColor: \'rgba(0,0,0,0.8)\',
                titleFontColor: \'#ffffff\',
                bodyFontColor: \'#ffffff\',
                footerFontColor: \'#ffffff\'
            }
        }
    };
    new Chart(ctx2, {
        type: \'bar\',
        data: data2,
        options: options2
    });
</script>
';

        return $chart;
    }

    public function generateChart3($parties)
    {
        // Trier les parties par durée croissante
        usort($parties, function ($a, $b) {
            $dateTimeA = DateTime::createFromFormat('Y-m-d H:i:s', $a['Date_Deb']);
            $dateTimeB = DateTime::createFromFormat('Y-m-d H:i:s', $b['Date_Deb']);
            return $dateTimeA <=> $dateTimeB;
        });

        // Préparer les données pour le graphique
        $timeData = [];
        $averageQuestions = [];
        foreach ($parties as $partie) {
            if ($partie['Date_Fin'] == null) {
                continue;
            }
            $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $partie['Date_Deb']);
            $dateTime2 = DateTime::createFromFormat('Y-m-d H:i:s', $partie['Date_Fin']);

            $interval = $dateTime->diff($dateTime2);
            $durationInSeconds = $interval->format('%H') * 3600 + $interval->format('%I') * 60 + $interval->format('%S');
            $durationFormatted = gmdate("H:i:s", $durationInSeconds);

            $timeData[] = $durationFormatted;
            $averageQuestions[] = $partie['Moy_Questions'];
        }

        // Convertir les tableaux en chaînes JSON pour JavaScript
        $timeJSON = json_encode($timeData);
        $averageQuestionsJSON = json_encode($averageQuestions);

        // Générer le code HTML et JavaScript pour le graphique
        $chart = '
<canvas id="myChart3"></canvas>
<script>
    const ctx3 = document.getElementById(\'myChart3\').getContext(\'2d\');
    const data3 = {
        labels: ' . $timeJSON . ',
        datasets: [
        {
            type: \'line\',
            label: \'Moyenne des questions\',
            data: ' . $averageQuestionsJSON . ',
            borderColor: \'rgba(54, 162, 235, 0.75)\',
            fill: false
        }]
    };
    const options3 = {
        scales: {
            x: {
                title: {
                    display: true,
                    text: \'Parties\',
                    color: \'#ffffff\'
                },
                ticks: {
                    color: \'#ffffff\'
                },
                grid: {
                    color: \'#ffffff\'
                }
            },
            y: {
                title: {
                    display: true,
                    text: \'Valeurs\',
                    color: \'#ffffff\'
                },
                ticks: {
                    color: \'#ffffff\'
                },
                grid: {
                    color: \'#ffffff\'
                }
            }
        },
        plugins: {
            legend: {
                labels: {
                    color: \'#ffffff\'
                }
            },
            tooltip: {
                backgroundColor: \'rgba(0,0,0,0.8)\',
                titleFontColor: \'#ffffff\',
                bodyFontColor: \'#ffffff\',
                footerFontColor: \'#ffffff\'
            }
        }
    };
    new Chart(ctx3, {
        type: \'line\',
        data: data3,
        options: options3
    });
</script>
';
        return $chart;
    }


}