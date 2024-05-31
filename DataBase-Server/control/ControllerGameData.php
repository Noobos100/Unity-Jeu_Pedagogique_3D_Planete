<?php

namespace control;

use DateTime;
use gui\charts\ViewApparition;
use gui\charts\ViewPercentage;
use gui\charts\ViewMoyQuestions;

class ControllerGameData
{

	// Getter
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

	// Methods
	public function calculateTotalAbandons($reponseUser): int
	{
		// Compter le nombre total d'abandons
		$totalAbandons = 0;
		foreach ($reponseUser as $user) {
			$totalAbandons += $user['Abandon'] ?? 0;
		}
		return $totalAbandons;
	}

	public function calculateTempsMin($reponseUser): string
	{
		$totalMinTemps = PHP_INT_MAX;
		foreach ($reponseUser as $user) {
			if (isset($user['Date_Deb']) && isset($user['Date_Fin']) && $user['Date_Fin'] != null && $user['Date_Deb'] != null) {
				$totalMinTemps2 = $this->getF($user);

				if ($totalMinTemps2 < $totalMinTemps) {
					$totalMinTemps = $totalMinTemps2;
				}
			}
		}
		return gmdate("H:i:s", $totalMinTemps);
	}

	public function calculateTempsMax($reponseUser): string
	{
		$totalMaxTemps = 0;
		foreach ($reponseUser as $user) {
			if (isset($user['Date_Deb']) && isset($user['Date_Fin']) && $user['Date_Fin'] != null && $user['Date_Deb'] != null) {
				$totalMaxTemps2 = $this->getF($user);
				if ($totalMaxTemps2 > $totalMaxTemps) {
					$totalMaxTemps = $totalMaxTemps2;
				}
			}
		}
		return gmdate("H:i:s", $totalMaxTemps);
	}

	// Charts
	public function generatePercentageChart($reponsesUsers): string
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

		$dataset = [];
		foreach ($questionData as $numQues => $data) {
			$dataset["Question $numQues"] = ($data['correct'] / $data['attempts']) * 100;
		}

		return (new ViewPercentage($dataset))->render();
	}

	public function generateChartMoyQuestion($parties): string
	{
		// Préparer les données pour le graphique
		$moyQuestionsData = [];
        foreach ($parties as $partie) {
            if ($partie['Abandon']) {
                continue;
            }
            if (!isset($moyQuestionsData[$partie['Moy_Questions']])) {
                $moyQuestionsData[$partie['Moy_Questions']] = 1;
            } else {
                $moyQuestionsData[$partie['Moy_Questions']]++;
            }
        }

		// Trier les moyennes de questions par ordre croissant
		ksort($moyQuestionsData);

		return (new ViewMoyQuestions($moyQuestionsData))->render();
	}

	public function generateChartApparitions($questionData): string
	{
		$dataset = [];
		foreach ($questionData as $data) {
			$dataset["Question " . $data['Num_Ques']] = $data['Apparitions'];
		}

		return (new ViewApparition($dataset))->render();
	}

	/**
	 * @param mixed $user
	 * @return float|int
	 */
	public function getF(mixed $user): int|float
	{
		$dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $user['Date_Deb']);
		$dateTime2 = DateTime::createFromFormat('Y-m-d H:i:s', $user['Date_Fin']);

		$interval = $dateTime->diff($dateTime2);
		$totalMaxTemps2 = ((int)$interval->format('%H')) * 3600 + ((int)$interval->format('%I')) * 60 + ((int)$interval->format('%S'));
		return $totalMaxTemps2;
	}
}