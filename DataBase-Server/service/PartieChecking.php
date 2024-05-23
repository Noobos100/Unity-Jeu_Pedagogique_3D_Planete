<?php

namespace service;

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
    public function addInteraction(string $nomInteract, float $valeurInteract, int $isEval, string $ipJoueur, string $dateInterac, $data): Interaction|False{
        return $data->addInteraction($nomInteract, $valeurInteract, $isEval, $ipJoueur, $dateInterac);
    }

    /**
     * @param string $ip
     * @param string $plateforme
     * @param $data
     * @return Joueur|False
     */
    public function addJoueur(string $ip, string $plateforme, $data): Joueur|False{
        return $data->addJoueur($ip, $plateforme);
    }

    /**
     * @param string $ip
     * @param $data
     * @return bool
     */
    public function verifyJoueurExists(string $ip, $data): bool{
        return $data->verifyJoueurExists($ip);
    }

    /**
     * @param string $ipJoueur
     * @param string $dateDeb
     * @param $data
     * @return Partie|False
     */
    public function addNewPartie(string $ipJoueur, string $dateDeb, $data): Partie|False{
        return $data->addNewPartie($ipJoueur, $dateDeb);
    }

    /**
     * @param string $ipJoueur
     * @param $data
     * @return void
     */
    public function deleteOnGoingPartie(string $ipJoueur, $data): void{
        $data->deleteOnGoingPartie($ipJoueur);
    }

    /**
     * @param string $ipJoueur
     * @param $data
     * @return void
     */
    public function abortOnGoingPartie(string $ipJoueur, $data): void{
        $data->abortOnGoingPartie($ipJoueur);
    }

    /**
     * @param string $ipJoueur
     * @param string $dateFin
     * @param $data
     * @return Partie
     */
    public function endPartie(string $ipJoueur, string $dateFin, $data): Partie{
        return $data->endPartie($ipJoueur, $dateFin);
    }

    /**
     * @param string $ipJoueur
     * @param $data
     * @return Partie|False
     */
    public function getPartieInProgress(string $ipJoueur, $data): Partie|False{
        return $data->getPartieInProgress($ipJoueur);
    }

    /**
     * @param string $ipJoueur
     * @param $data
     * @return bool
     */
    public function verifyPartieInProgress(string $ipJoueur, $data): bool{
        return $data->verifyPartieInProgress($ipJoueur);
    }

    /**
     * @param int $numQues
     * @param int $idPartie
     * @param $data
     * @return UserAnswer
     */
    public function getQuestionCorrect(int $numQues, int $idPartie, $data):  UserAnswer{
        return $data->getQuestionCorrect($numQues, $idPartie);
    }

    /**
     * @param int $idPartie
     * @param $data
     * @return float|False
     */
    public function getPartyScore(int $idPartie, $data): float|False{
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
    public function addQuestionAnswer(int $numQues, int $idParty, string $dateDeb, string $dateFin, bool $isCorrect, $data): void{
        $data->addQuestionAnswer($numQues, $idParty, $dateDeb, $dateFin, $isCorrect);
    }

    /**
     * @param int $numQues
     * @param $data
     * @return array|False
     */
    public function getQBasics(int $numQues, $data): array|False{
        return $data->getQBasics($numQues);
    }

    /**
     * @param int $numQues
     * @param $data
     * @return QCU | VraiFaux | QuesInterac| False
     */
    public function getQAttributes(int $numQues, $data): QCU | VraiFaux | QuesInterac| False{
        return $data->getQAttributes($numQues);
    }

    /**
     * @param int $howManyQCM
     * @param int $howManyInterac
     * @param int $howManyVraiFaux
     * @param $data
     * @return array
     */
    public function getRandomQs(int $howManyQCM, int $howManyInterac, int $howManyVraiFaux, $data): array{
        return $data->getRandomQs($howManyQCM, $howManyInterac, $howManyVraiFaux);
    }

    /**
     * @param int $numQues
     * @param $data
     * @return Qcu|False
     */
    public function getQQCU(int $numQues, $data): Qcu|False{
        return $data->getQQCU($numQues);
    }

    public function getAllQ($data): array{
        return $data->getAllQ();
    }

    /**
     * @param int $howManyQCM
     * @param $data
     * @return array
     */
    public function getRandomQQCU(int $howManyQCM, $data): array{
        return $data->getRandomQQCU($howManyQCM);
    }

    /**
     * @param int $numQues
     * @param $data
     * @return Quesinterac|False
     */
    public function getQInteraction(int $numQues, $data): Quesinterac|False{
        return $data->getQInteraction($numQues);
    }

    /**
     * @param int $howManyInterac
     * @param $data
     * @return array
     */
    public function getRandomQInterac(int $howManyInterac, $data): array{
        return $data->getRandomQInterac($howManyInterac);
    }

    /**
     * @param int $numQues
     * @param $data
     * @return VraiFaux|False
     */
    public function getQVraiFaux(int $numQues, $data): VraiFaux|False{
        return $data->getQVraiFaux($numQues);
    }

    /**
     * @param int $howManyVraiFaux
     * @param $data
     * @return array
     */
    public function getRandomQVraiFaux(int $howManyVraiFaux, $data): array{
        return $data->getRandomQVraiFaux($howManyVraiFaux);
    }
}


