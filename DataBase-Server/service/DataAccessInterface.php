<?php

namespace service;

use domain\{Interaction, Joueur, Partie, Qcu, Quesinterac, UserAnswer, VraiFaux};

interface DataAccessInterface
{
    /**
     * @param string $nomInteract
     * @param float $valeurInteract
     * @param int $isEval
     * @param string $ipJoueur
     * @param string $dateInteract
     * @return Interaction|False
     */
    public function addInteraction(string $nomInteract, float $valeurInteract, int $isEval, string $ipJoueur, string $dateInteract): Interaction|False;

    /**
     * @param string $ip
     * @param string $plateforme
     * @return Joueur|False
     */
    public function addJoueur(string $ip, string $plateforme): Joueur|False;

    /**
     * @param string $ip
     * @return bool
     */
    public function verifyJoueurExists(string $ip): bool;

    /**
     * @param string $ipJoueur
     * @param string $dateDeb
     * @return Partie|False
     */
    public function addNewPartie(string $ipJoueur, string $dateDeb): Partie|False;

    /**
     * @param string $ipJoueur
     * @return void
     */
    public function deleteOnGoingPartie(string $ipJoueur): void;

    /**
     * @param string $ipJoueur
     * @return void
     */
    public function abortOnGoingPartie(string $ipJoueur): void;

    /**
     * @param string $ipJoueur
     * @param string $dateFin
     * @return Partie|False
     */
    public function endPartie(string $ipJoueur, string $dateFin): Partie|False;

    /**
     * @param string $ipJoueur
     * @return Partie|False
     */
    public function getPartieInProgress(string $ipJoueur): Partie|False;

    /**
     * @param string $ipJoueur
     * @return bool
     */
    public function verifyPartieInProgress(string $ipJoueur): bool;

    /**
     * @param int $numQues
     * @param int $idPartie
     * @return UserAnswer
     */
    public function getQuestionCorrect(int $numQues, int $idPartie):  UserAnswer;

    /**
     * @param int $idPartie
     * @return float|False
     */
    public function getPartyScore(int $idPartie): float|False;

    /**
     * @param int $numQues
     * @param int $idParty
     * @param string $dateDeb
     * @param string $dateFin
     * @param bool $isCorrect
     * @return void
     */
    public function addQuestionAnswer(int $numQues, int $idParty, string $dateDeb, string $dateFin, bool $isCorrect): void;

    /**
     * @param int $numQues
     * @return array|False
     */
    public function getQBasics(int $numQues): array|False;

    /**
     * @param int $numQues
     * @return QCU | VraiFaux | QuesInterac| False
     */
    public function getQAttributes(int $numQues): QCU | VraiFaux | QuesInterac| False;

    /**
     * @param int $howManyQCU
     * @param int $howManyInterac
     * @param int $howManyVraiFaux
     * @return array
     */
    public function getRandomQs(int $howManyQCU, int $howManyInterac, int $howManyVraiFaux): array;

    /**
     * @param int $numQues
     * @return Qcu|False
     */
    public function getQQCU(int $numQues): Qcu|False;

    public function getAllQ(): array;

    /**
     * @param int $howManyQCU
     * @return array
     */
    public function getRandomQQCU(int $howManyQCU): array;

    /**
     * @param int $numQues
     * @return Quesinterac|False
     */
    public function getQInteraction(int $numQues): Quesinterac|False;

    /**
     * @param int $howManyInterac
     * @return array
     */
    public function getRandomQInterac(int $howManyInterac): array;

    /**
     * @param int $numQues
     * @return VraiFaux|False
     */
    public function getQVraiFaux(int $numQues): VraiFaux|False;

    /**
     * @param int $howManyVraiFaux
     * @return array
     */
    public function getRandomQVraiFaux(int $howManyVraiFaux): array;
}
