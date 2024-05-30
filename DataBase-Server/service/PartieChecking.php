<?php

namespace service;

use data\DataAccess;
use domain\{Interaction, Joueur, Partie, Qcu, Quesinterac, UserAnswer, VraiFaux};


class PartieChecking
{

    /**
     * @param string $nomInteract
     * @param float $valeurInteract
     * @param int $isEval
     * @param string $ipJoueur
     * @param string $dateInterac
     * @param $data
     * @return Interaction|False
     */
    public function addInteraction(string $nomInteract, float $valeurInteract, int $isEval, string $ipJoueur, string $dateInterac, DataAccess $data): Interaction|False{
        return $data->addInteraction($nomInteract, $valeurInteract, $isEval, $ipJoueur, $dateInterac);
    }

    /**
     * @param string $ip
     * @param string $plateforme
	 * @param string $username
     * @param $data
     * @return Joueur|False
     */
    public function addJoueur(string $ip, string $plateforme, string $username, DataAccess $data): Joueur|False {
        return $data->addJoueur($ip, $plateforme, $username);
    }

	/**
	 * @param string $ip
	 * @param string $plateforme
	 * @param string $username
	 * @param $data
	 * @return Joueur|False
	 */
	public function updateJoueur(string $ip, string $plateforme, string $username, DataAccess $data): Joueur|False {
		return $data->updateJoueur($ip, $plateforme, $username);
	}

    /**
     * @param string $ip
     * @param $data
     * @return bool
     */
    public function verifyJoueurExists(string $ip, DataAccess $data): bool{
        return $data->verifyJoueurExists($ip);
    }

    /**
     * @param string $ipJoueur
     * @param string $dateDeb
     * @param $data
     * @return Partie|False
     */
    public function addNewPartie(string $ipJoueur, string $dateDeb, DataAccess $data): Partie|False{
        return $data->addNewPartie($ipJoueur, $dateDeb);
    }

    /**
     * @param string $ipJoueur
     * @param $data
     * @return void
     */
    public function deleteOnGoingPartie(string $ipJoueur, DataAccess $data): void{
        $data->deleteOnGoingPartie($ipJoueur);
    }

    /**
     * @param string $ipJoueur
     * @param $data
     * @return void
     */
    public function abortOnGoingPartie(string $ipJoueur, DataAccess $data): void{
        $data->abortOnGoingPartie($ipJoueur);
    }

    /**
     * @param string $ipJoueur
     * @param string $dateFin
     * @param $data
     * @return Partie
     */
    public function endPartie(string $ipJoueur, string $dateFin, DataAccess $data): Partie{
        return $data->endPartie($ipJoueur, $dateFin);
    }

    /**
     * @param string $ipJoueur
     * @param $data
     * @return Partie|False
     */
    public function getPartieInProgress(string $ipJoueur, DataAccess $data): Partie|False{
        return $data->getPartieInProgress($ipJoueur);
    }

    /**
     * @param string $ipJoueur
     * @param $data
     * @return bool
     */
    public function verifyPartieInProgress(string $ipJoueur, DataAccess $data): bool{
        return $data->verifyPartieInProgress($ipJoueur);
    }

    /**
     * @param int $numQues
     * @param int $idPartie
     * @param $data
     * @return UserAnswer
     */
    public function getQuestionCorrect(int $numQues, int $idPartie, DataAccess $data):  UserAnswer{
        return $data->getQuestionCorrect($numQues, $idPartie);
    }

    /**
     * @param int $idPartie
     * @param $data
     * @return float|False
     */
    public function getPartyScore(int $idPartie, DataAccess $data): float|False{
        return $data->getPartyScore($idPartie);
    }

    /**
     * @param int $numQues
     * @param int $idParty
     * @param string $dateDeb
     * @param string $dateFin
     * @param bool $isCorrect
     * @param $data
     * @return void
     */
    public function addQuestionAnswer(int $numQues, int $idParty, string $dateDeb, string $dateFin, bool $isCorrect, DataAccess $data): void{
        $data->addQuestionAnswer($numQues, $idParty, $dateDeb, $dateFin, $isCorrect);
    }

    /**
     * @param int $numQues
     * @param $data
     * @return array|False
     */
    public function getQBasics(int $numQues, DataAccess $data): array|False{
        return $data->getQBasics($numQues);
    }

    /**
     * @param int $numQues
     * @param $data
     * @return QCU | VraiFaux | QuesInterac| False
     */
    public function getQAttributes(int $numQues, DataAccess $data): QCU | VraiFaux | QuesInterac| False{
        return $data->getQAttributes($numQues);
    }

    /**
     * @param int $howManyQCM
     * @param int $howManyInterac
     * @param int $howManyVraiFaux
     * @param $data
     * @return array
     */
    public function getRandomQs(int $howManyQCM, int $howManyInterac, int $howManyVraiFaux, DataAccess $data): array{
        return $data->getRandomQs($howManyQCM, $howManyInterac, $howManyVraiFaux);
    }

    /**
     * @param int $numQues
     * @param $data
     * @return Qcu|False
     */
    public function getQQCU(int $numQues, DataAccess $data): Qcu|False{
        return $data->getQQCU($numQues);
    }

    public function getAllQ(DataAccess $data): array{
        return $data->getAllQ();
    }

    /**
     * @param int $howManyQCM
     * @param DataAccess $data
     * @return array
     */
    public function getRandomQQCU(int $howManyQCM, DataAccess $data): array{
        return $data->getRandomQQCU($howManyQCM);
    }

    /**
     * @param int $numQues
     * @param DataAccess $data
     * @return Quesinterac|False
     */
    public function getQInteraction(int $numQues, DataAccess $data): Quesinterac|False{
        return $data->getQInteraction($numQues);
    }

    /**
     * @param int $howManyInterac
     * @param $data
     * @return array
     */
    public function getRandomQInterac(int $howManyInterac, DataAccess $data): array{
        return $data->getRandomQInterac($howManyInterac);
    }

    /**
     * @param int $numQues
     * @param $data
     * @return VraiFaux|False
     */
    public function getQVraiFaux(int $numQues, DataAccess $data): VraiFaux|False{
        return $data->getQVraiFaux($numQues);
    }

    /**
     * @param int $howManyVraiFaux
     * @param DataAccess $data
     * @return array
     */
    public function getRandomQVraiFaux(int $howManyVraiFaux, DataAccess $data): array{
        return $data->getRandomQVraiFaux($howManyVraiFaux);
    }

    public function updateQCU(int $numQues, string $question, string $option1, string $option2, string $option3, string $option4, string $correct, DataAccess $data): void{
        $data->updateQCU($numQues, $question, $option1, $option2, $option3, $option4, $correct);
    }

    public function updateQVraiFaux(mixed $Num_Ques, mixed $question, string $orbit, string $rotation, mixed $correct, DataAccess $data): void
    {
        $data->updateQVraiFaux($Num_Ques, $question, $orbit, $rotation, $correct);
    }

    public function updateQInterac(mixed $Num_Ques, mixed $question, string $orbit, string $rotation, $rotationMargin, $orbitMargin, DataAccess $data): void
    {
        $data->updateQInterac($Num_Ques, $question, $orbit, $rotation, $rotationMargin, $orbitMargin);
    }

    public function deleteQuestion(int $numQues, mixed $data): void
    {
        $data->deleteQuestion($numQues);
    }

    public function addQVraiFaux(string $question, ?string $orbit, ?string $rotation, string $correct, DataAccess $data): void
    {
        $data->addQVraiFaux($question, $orbit, $rotation, $correct);
    }
}


