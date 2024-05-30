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
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
          let ctx3 = document.getElementById('myChart3').getContext('2d');
          let data3 = {
            labels: <?php echo $this->getDatasetKey() ?>,
            datasets: [{
              type: 'bar',
              label: 'Nombre de fois où chaque moyenne apparaît',
              data: <?php echo $this->getDatasetValue() ?>,
              backgroundColor: 'rgba(75, 192, 192, 0.75)',
              borderWidth: 1
            }]
          };
          const options3 = {
            scales: {
              x: {
                title: {
                  display: true,
                  text: 'Moyennes des questions et Abandons',
                  color: '#ffffff' // Couleur des étiquettes de l'axe X
                },
                ticks: {
                  color: '#ffffff' // Couleur des étiquettes de l'axe X
                }
              },
              y: {
                title: {
                  display: true,
                  text: 'Nombre',
                  color: '#ffffff' // Couleur des étiquettes de l'axe Y
                },
                ticks: {
                  color: '#ffffff' // Couleur des étiquettes de l'axe Y
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
          new Chart(ctx3, {
            type: 'bar',
            data: data3,
            options: options3
          });
        </script>
		<?php
		return ob_get_clean();
	}
}