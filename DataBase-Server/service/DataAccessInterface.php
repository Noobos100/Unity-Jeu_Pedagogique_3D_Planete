<?php

namespace service;

use domain\{Interaction, Joueur, Partie, Qcu, Quesinterac, UserAnswer, VraiFaux};

/**
 * Interface DataAccessInterface
 * @package service
 */
interface DataAccessInterface
{
    /**
     * @param string $name
     * @param string $pwd
     * @return bool
     */
    public function authenticateUser(string $name, string $pwd): bool;

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
	 * @param string $username
     * @return Joueur|False
     */
    public function addJoueur(string $ip, string $plateforme, string $username): Joueur|False;

	/**
	 * @param string $ip
	 * @param string $plateforme
	 * @param string $username
	 * @return Joueur|False
	 */
	public function updateJoueur(string $ip, string $plateforme, string $username): Joueur|False;

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

    /**
     * @return array
     */
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

	/**
	 * @return array
	 */
	public function getPlayers(): array;

    /**
     * @return array
     */
    public function getParties(): array;

    /**
     * @return array
     */
    public function getQuestionNb(): array;

    /**
     * @return array
     */
    public function getPartiesAsc(): array;

    /**
     * @return array
     */
    public function getReponsesUsers(): array;

    /**
     * @return array
     */
    public function getBestUsers(): array;

    /**
     * @param int $numQues
     * @param string $question
     * @param string $rep1
     * @param string $rep2
     * @param string $rep3
     * @param string $rep4
     * @param string $bonneRep
     * @return void
     */
    public function updateQCU(int $numQues, string $question, string $rep1, string $rep2, string $rep3, string $rep4, string $bonneRep): void;

    /**
     * @param int $numQues
     * @param string $question
     * @param string $orbite
     * @param string $rotation
     * @param string $correct
     * @return void
     */
    public function updateQVraiFaux(int $numQues, string $question, string $orbite, string $rotation, string $correct): void;

    /**
     * @param int $numQues
     * @param string $question
     * @param string $orbite
     * @param string $rotation
     * @param string $rotationMargin
     * @param string $orbitMargin
     * @return void
     */
    public function updateQInterac(int $numQues, string $question, string $orbite, string $rotation, string $rotationMargin, string $orbitMargin): void;

    /**
     * @param int $numQues
     * @return void
     */
    public function deleteQuestion(int $numQues): void;

    /**
     * @param string $enonce
     * @param string|null $valeur_orbit
     * @param string|null $valeur_rotation
     * @param string $bonneRep
     * @return void
     */
    public function addQVraiFaux(string $enonce, ?string $valeur_orbit, ?string $valeur_rotation, string $bonneRep): void;

    /**
     * @param string $enonce
     * @param string $bonneRepValeur_orbit
     * @param string $marge_Orbit
     * @param string $bonneRepValeur_rotation
     * @param string $marge_Rotation
     * @return int
     */
    public function addQInterac(string $enonce, string $bonneRepValeur_orbit, string $marge_Orbit, string $bonneRepValeur_rotation, string $marge_Rotation): int;

    /**
     * @param string $enonce
     * @param string $rep1
     * @param string $rep2
     * @param string $rep3
     * @param string $rep4
     * @param string $bonneRep
     * @return int
     */
    public function addQCU(string $enonce, string $rep1, string $rep2, string $rep3, string $rep4, string $bonneRep): int;

    /**
     * @param string $name
     * @param string $pwd
     * @return bool
     */
    public function createUser(string $name, string $pwd): bool;
}
