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

    public function getQuestionNb($data)
    {
        return $data->getQuestionNb();
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

    public function generateChartPlatforme($joueurs)
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
                    
                });
            </script>
        ';

        return $chart;
    }

    public function generateChartPourcentage($reponsesUsers)
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
        $successRates = [];
        foreach ($questionData as $numQues => $data) {
            $labels[] = "Question $numQues";
            $successRate = ($data['correct'] / $data['attempts']) * 100;
            $successRates[] = $successRate;
        }

        // Convertir les tableaux en chaînes JSON pour JavaScript
        $labelsJSON = json_encode($labels);
        $successRatesJSON = json_encode($successRates);

        // Générer le code HTML et JavaScript pour le graphique
        $chart = '
        <canvas id="myChart2"></canvas>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            let ctx2 = document.getElementById(\'myChart2\').getContext(\'2d\');
            let data2 = {
                labels: ' . $labelsJSON . ',
                datasets: [{
                    type: \'bar\',
                    label: \'Pourcentage de réussite par question\',
                    data: ' . $successRatesJSON . ',
                    backgroundColor: \'rgba(75, 192, 255, 0.75)\',
                    borderWidth: 1
                }]
            };
            const options2 = {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: \'Questions\',
                            color: \'#ffffff\' // Couleur des étiquettes de l\'axe X
                        },
                        ticks: {
                            color: \'#ffffff\' // Couleur des étiquettes de l\'axe X
                        },
                    },
                    y: {
                        title: {
                            display: true,
                            text: \'Pourcentage de réussite (%)\',
                            color: \'#ffffff\' // Couleur des étiquettes de l\'axe Y
                        },
                        ticks: {
                            color: \'#ffffff\' // Couleur des étiquettes de l\'axe Y
                        },
                    }
                },
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


    public function generateChartMoyQuestion($parties)
    {
        // Préparer les données pour le graphique
        $moyQuestionsData = [];
        foreach ($parties as $partie) {
            $moyQuestions = $partie['Moy_Questions'];
            if (!isset($moyQuestionsData[$moyQuestions])) {
                $moyQuestionsData[$moyQuestions] = 0;
            }
            $moyQuestionsData[$moyQuestions]++;
        }

        // Trier les moyennes de questions par ordre croissant
        ksort($moyQuestionsData);

        $labels = array_keys($moyQuestionsData);
        $moyQuestionsCounts = array_values($moyQuestionsData);

        // Convertir les tableaux en chaînes JSON pour JavaScript
        $labelsJSON = json_encode($labels);
        $moyQuestionsCountsJSON = json_encode($moyQuestionsCounts);

        // Générer le code HTML et JavaScript pour le graphique
        $chart = '
        <canvas id="myChart3"></canvas>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            let ctx3 = document.getElementById(\'myChart3\').getContext(\'2d\');
            let data3 = {
                labels: ' . $labelsJSON . ',
                datasets: [{
                    type: \'bar\',
                    label: \'Nombre de fois où chaque moyenne apparaît\',
                    data: ' . $moyQuestionsCountsJSON . ',
                    backgroundColor: \'rgba(75, 192, 192, 0.75)\',
                    borderWidth: 1
                }]
            };
            const options3 = {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: \'Moyennes des questions et Abandons\',
                            color: \'#ffffff\' // Couleur des étiquettes de l\'axe X
                        },
                        ticks: {
                            color: \'#ffffff\' // Couleur des étiquettes de l\'axe X
                        },
                        grid: {
                            color: \'#ffffff\' // Couleur de la grille de l\'axe X
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: \'Nombre\',
                            color: \'#ffffff\' // Couleur des étiquettes de l\'axe Y
                        },
                        ticks: {
                            color: \'#ffffff\' // Couleur des étiquettes de l\'axe Y
                        },
                        grid: {
                            color: \'#ffffff\' // Couleur de la grille de l\'axe Y
                        }
                    }
                },
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
            };
            new Chart(ctx3, {
                type: \'bar\',
                data: data3,
                options: options3
            });
        </script>
    ';

        return $chart;
    }

    public function generateChartApparitions($questionData)
    {
        $labels = [];
        $appearances = [];
        foreach ($questionData as $data) {
            $labels[] = "Question " . $data['Num_Ques'];
            $appearances[] = $data['Apparitions'];
        }

        // Convertir les tableaux en chaînes JSON pour JavaScript
        $labelsJSON = json_encode($labels);
        $appearancesJSON = json_encode($appearances);

        // Générer le code HTML et JavaScript pour le graphique
        $chart = '
    <canvas id="myChart4"></canvas>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let ctx4 = document.getElementById(\'myChart4\').getContext(\'2d\');
        let data4 = {
            labels: ' . $labelsJSON . ',
            datasets: [{
                type: \'bar\',
                label: \'Nombre d\'apparitions par question\',
                data: ' . $appearancesJSON . ',
                backgroundColor: \'rgba(75, 192, 192, 0.75)\',
                borderWidth: 1
            }]
        }
        const options4 = {
            scales: {
                x: {
                    title: {
                        display: true,
                        text: \'Questions\',
                        color: \'#ffffff\' // Couleur des étiquettes de l\'axe X
                    },
                    ticks: {
                        color: \'#ffffff\' // Couleur des étiquettes de l\'axe X
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: \'Nombre d\'apparitions\',
                        color: \'#ffffff\' // Couleur des étiquettes de l\'axe Y
                    },
                    ticks: {
                        color: \'#ffffff\' // Couleur des étiquettes de l\'axe Y
                    }
                }
            },
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
        };
        new Chart(ctx4, {
            type: \'bar\',
            data: data4,
            options: options4
        });
    </script>
    ';

        return $chart;
    }





}