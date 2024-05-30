<?php

namespace gui\charts;

include_once "ViewChart.php";

class ViewPlatform extends ViewChart
{
    public function __construct($dataset)
	{
		parent::__construct($dataset);
	}

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
              }]
            };
          new Chart(platformCtx, {
            type: 'pie',
            data: platformData
          });
        </script>
		<?php
		return ob_get_clean();
	}
}