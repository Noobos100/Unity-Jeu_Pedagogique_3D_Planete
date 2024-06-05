<?php

use data\DataAccess;

class _score
{
	private array $bestUsers;

	public function __construct(DataAccess $data)
	{
		$this->bestUsers = $data->getBestUsers(10);
	}

	public function render(): bool|string
	{
		ob_start();
		?>
		<div id="score-container">
			<h1 class="h1-title">Meilleurs scores</h1>
			<table id="score-table">
				<tr>
					<th>Position</th>
					<th>Nom</th>
					<th>Score</th>
					<th>Temps de réponse</th>
				</tr>
				<?php
				$position = 1;
				foreach ($this->bestUsers as $user) {
					?>
					<tr>
						<td><?= $position ?></td>
						<td><?= htmlspecialchars($user['Username'], ENT_QUOTES, 'UTF-8') ?></td>
						<td><?= htmlspecialchars($user['Moy_Questions'], ENT_QUOTES, 'UTF-8') ?></td>
						<td><?= htmlspecialchars($user['Temps_Reponse'], ENT_QUOTES, 'UTF-8') ?></td>
					</tr>
					<?php
					$position++;
				}
				?>
			</table>
		</div>
		<?php
		return ob_get_clean();
	}
}