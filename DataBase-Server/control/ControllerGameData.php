<?php

namespace control;

use data\DataAccess;
use DateTime;
use gui\charts\ViewApparition;
use gui\charts\ViewPercentage;
use gui\charts\ViewMoyQuestions;

/**
 * Class ControllerGameData
 * @package control
 * This class is responsible for handling the data related to the game.
 */
class ControllerGameData
{
    /**
     * @param DataAccess $data
     * @return array
     */
    public function getParties(DataAccess $data): array
    {
		return $data->getParties();
	}

    /**
     * @param DataAccess $data
     * @return array
     */
    public function getReponsesUsers(DataAccess $data): array
    {
		return $data->getReponsesUsers();
	}

    /**
     * @param DataAccess $data
     * @return array
     */
    public function getPartiesAsc(DataAccess $data): array
    {
		return $data->getPartiesAsc();
	}

    /**
     * @param DataAccess $data
     * @return array
     */
    public function getQuestionNb(DataAccess $data): array
    {
		return $data->getQuestionNb();
	}

    /**
     * @param DataAccess $data
     * @return array
     */
    public function getBestUsers(DataAccess $data): array
    {
        return $data->getBestUsers();
    }

	// Methods

    /**
     * @param array $reponseUser
     * @return int
     */
    public function calculateTotalAbandons(array $reponseUser): int
	{
		// Compter le nombre total d'abandons
		$totalAbandons = 0;
		foreach ($reponseUser as $user) {
			$totalAbandons += $user['Abandon'] ?? 0;
		}
		return $totalAbandons;
	}

    /**
     * @param array $reponseUser
     * @return string
     */
    public function calculateTempsMin(array $reponseUser): string
	{
		$totalMinTemps = PHP_INT_MAX;
		foreach ($reponseUser as $user) {
			if (isset($user['Date_Deb']) && isset($user['Date_Fin']) && $user['Date_Fin'] != null && $user['Date_Deb'] != null) {
				$totalMinTemps2 = $this->calculateTime($user);

				if ($totalMinTemps2 < $totalMinTemps) {
					$totalMinTemps = $totalMinTemps2;
				}
			}
		}
		return gmdate("H:i:s", $totalMinTemps);
	}

    /**
     * @param array $reponseUser
     * @return string
     */
    public function calculateTempsMax(array $reponseUser): string
	{
		$totalMaxTemps = 0;
		foreach ($reponseUser as $user) {
			if (isset($user['Date_Deb']) && isset($user['Date_Fin']) && $user['Date_Fin'] != null && $user['Date_Deb'] != null) {
				$totalMaxTemps2 = $this->calculateTime($user);
				if ($totalMaxTemps2 > $totalMaxTemps) {
					$totalMaxTemps = $totalMaxTemps2;
				}
			}
		}
		return gmdate("H:i:s", $totalMaxTemps);
	}

	// Charts

    /**
     * @param array $reponsesUsers
     * @return string
     */
    public function generatePercentageChart(array $reponsesUsers): string
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

    /**
     * @param array $parties
     * @return string
     */
    public function generateChartMoyQuestion(array $parties): string
	{
		// Préparer les données pour le graphique
		$moyQuestionsData = [];
        foreach ($parties as $partie) {
            if ($partie['Abandon'] == 1 || (!isset($partie['Moy_Questions']) && $partie['Abandon'] == 0)) {
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

    /**
     * @param array $questionData
     * @return string
     */
    public function generateChartApparitions(array $questionData): string
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
	public function calculateTime(mixed $user): int|float
	{
		$dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $user['Date_Deb']);
		$dateTime2 = DateTime::createFromFormat('Y-m-d H:i:s', $user['Date_Fin']);

		$interval = $dateTime->diff($dateTime2);
        return ((int)$interval->format('%H')) * 3600 + ((int)$interval->format('%I')) * 60 + ((int)$interval->format('%S'));
	}
}