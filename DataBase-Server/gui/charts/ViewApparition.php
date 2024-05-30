<?php

namespace gui\charts;

class ViewApparition extends ViewChart
{
	public function __construct($dataset)
	{
		parent::__construct($dataset);
	}

	public function render(): string
	{
		ob_start();
		?>
        <canvas id="apparitionChart"></canvas>
        <script>
          let ctx4 = document.getElementById('apparitionChart').getContext('2d');
          let data4 = {
            labels: <?php echo $this->getDatasetKey() ?>,
            datasets: [{
              type: 'bar',
          label: 'Nombre d\'apparitions par question',
          data: <?php echo $this->getDatasetValue() ?>,
            backgroundColor
          : 'rgba(75, 192, 192, 0.75)',
          borderWidth: 1
          }]
          }
          const options4 = {
            scales: {
              x: {
                title: {
                  display: true,
                  text: 'Questions',
          color: 
          '#ffffff' // Couleur des étiquettes de l'axe X
          },
          ticks: {
            color: 
            '#ffffff' // Couleur des étiquettes de l'axe X
          }
          },
          y: {
            title: {
              display: true,
                text
            : 'Nombre d\'apparitions',
              color: 
              '#ffffff' // Couleur des étiquettes de l'axe Y
            }
          ,
            ticks: {
              color: 
              '#ffffff' // Couleur des étiquettes de l'axe Y
            }
          }
          },
          plugins: {
            legend: {
              labels: {
                color: 
                '#ffffff' // Couleur des étiquettes de la légende
              }
            }
          ,
            tooltip: {
              backgroundColor: 
              'rgba(0,0,0,0.8)',
              titleFontColor: 
              '#ffffff',
              bodyFontColor: 
              '#ffffff',
              footerFontColor: 
              '#ffffff'
            }
          }
          }
          ;
          new Chart(ctx4, {
            type: 'bar',
          data: data4,
            options
          :
          options4
          })
          ;
        </script>
		<?php
		return ob_get_clean();
	}
}