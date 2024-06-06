<?php

namespace gui\charts;

include_once "ViewChart.php";

class ViewPlatform extends ViewChart
{
    /**
     * Constructs a new ViewPlatform instance.
     *
     * @param array $dataset The dataset to display.
     */
	public function __construct($dataset)
	{
		parent::__construct($dataset);
	}

    /**
     * Renders the chart.
     *
     * @return string The rendered chart.
     */
	public function render(): string
	{
		ob_start();
		?>
        <canvas id="platformChart"></canvas>
        <script>
          let platformCtx = document.getElementById('platformChart').getContext('2d');
          let platformData =
            {
              labels: <?php echo $this->getDatasetKey() ?>,
              datasets: [{
                label: 'Nombre de joueurs',
                data: <?php echo $this->getDatasetValue() ?>,
                backgroundColor: [
                  'rgba(60,227,99,0.75)',
                  'rgba(255, 99, 132, 0.75)',
                  'rgba(54, 162, 235, 0.75)',
                  'rgba(255, 206, 86, 0.75)',
                ],
                  borderColor : [
                    'rgba(60,227,99,1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                  ],
                    borderWidth: 1,
                width: 500,
                height: 500
              }]
            };
          let platformOptions = {
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
          }
          new Chart(platformCtx, {
              type: 'pie',
              data: platformData,
              options: {
                  ...platformOptions,
                  responsive: true, // Le graphique s'adaptera à la taille de son conteneur
                  maintainAspectRatio: false // Permet de modifier la hauteur et la largeur indépendamment
              }
          });
        </script>
		<?php
		return ob_get_clean();
	}
}