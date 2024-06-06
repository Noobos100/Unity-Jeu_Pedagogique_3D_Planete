<?php

namespace gui\charts;

include_once "ViewChart.php";

class ViewMoyQuestions extends ViewChart
{
	public function __construct($dataset)
	{
		parent::__construct($dataset);
	}

	public function render(): string
	{
		ob_start();
		?>
        <canvas id="myChart3"></canvas>
        <script>
          let moyQuestionCtx = document.getElementById('myChart3').getContext('2d');
          let moyQuestionData = {
            labels: <?php echo $this->getDatasetKey() ?>,
            datasets: [{
              type: 'bar',
              label: 'Score des participants',
              data: <?php echo $this->getDatasetValue() ?>,
              backgroundColor: 'rgba(75,114,192,0.75)',
              borderWidth: 1
            }]
          };
          const moyQuestionOptions = {
            scales: {
              x: {
                title: {
                  display: true,
                  text: 'Scores',
                  color: '#ffffff' // Couleur des étiquettes de l'axe X
                },
                ticks: {
                  color: '#ffffff' // Couleur des étiquettes de l'axe X
                }
              },
              y: {
                title: {
                  display: true,
                  text: 'Nombre de participants ayant obtenu ce score',
                  color: '#ffffff' // Couleur des étiquettes de l'axe Y
                },
                ticks: {
                  color: '#ffffff', // Couleur des étiquettes de l'axe Y
                    stepSize: 1

                }
              }
            },
            plugins: {
              legend: {
                labels: {
                  color: '#ffffff' // Couleur des étiquettes de la légende
                }
              },
              tooltip: {
                backgroundColor: 'rgba(0,0,0,0.8)',
                titleFontColor: '#ffffff',
                bodyFontColor: '#ffffff',
                footerFontColor: '#ffffff'
              }
            }
          };
          new Chart(moyQuestionCtx, {
            type: 'bar',
            data: moyQuestionData,
            options: moyQuestionOptions
          });
        </script>
		<?php
		return ob_get_clean();
	}
}