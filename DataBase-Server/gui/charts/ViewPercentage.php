<?php

namespace gui\charts;

include_once "ViewChart.php";

class ViewPercentage extends ViewChart
{
    /**
     * Constructs a new ViewPercentage instance.
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
        <canvas id="percentageChart"></canvas>
        <script>
          let percentageCtx = document.getElementById('percentageChart').getContext('2d');
          let percentageData = {
            labels: <?php echo $this->getDatasetKey() ?>,
            datasets: [{
              type: 'bar',
              label: 'Taux de réussite par question (%)',
              data: <?php echo $this->getDatasetValue() ?>,
              backgroundColor: 'rgba(75, 192, 255, 0.75)',
              borderWidth: 1
            }]
          };
          const percentageOptions = {
            scales: {
              x: {
                title: {
                  display: true,
                  text: 'Questions',
                  color: '#ffffff' // Couleur des étiquettes de l'axe X
                },
                ticks: {
                  color: '#ffffff' // Couleur des étiquettes de l'axe X
                },
              },
              y: {
                title: {
                  display: true,
                  text: 'Taux de réussite (%)',
                  color: '#ffffff' // Couleur des étiquettes de l'axe Y
                },
                ticks: {
                  color: '#ffffff' // Couleur des étiquettes de l'axe Y
                },
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
          new Chart(percentageCtx, {
            type: 'bar',
            data: percentageData,
            options: percentageOptions
          });
        </script>
		<?php
		return ob_get_clean();
	}

}