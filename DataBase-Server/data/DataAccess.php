<?php

namespace data;

use domain\{Interaction, Joueur, Partie, Qcu, Quesinterac, UserAnswer, VraiFaux};
use service\{DataAccessInterface, CannotDoException};
use PDO;

include_once 'domain/Interaction.php';
include_once 'domain/Joueur.php';
include_once 'domain/Partie.php';
include_once 'domain/Qcu.php';
include_once 'domain/Quesinterac.php';
include_once 'domain/Question.php';
include_once 'domain/UserAnswer.php';
include_once 'domain/VraiFaux.php';

include_once 'service/DataAccessInterface.php';
include_once 'service/CannotDoException.php';

class DataAccess implements DataAccessInterface
{
    /**
     * @var PDO|null
     */
    protected PDO|null $dataAccess = null;

    /**
     * @param PDO $dataAccess
     */
    public function __construct(PDO $dataAccess)
    {
        $this->dataAccess = $dataAccess;
    }

    /**
     * Destructs the DataAccess instance.
     */
    public function __destruct()
    {
        $this->dataAccess = null;
    }

    /**
     * @param string $name
     * @param string $pwd
     * @return bool
     */
    public function utilisateur(string $name, string $pwd): bool
    {
        $query = "SELECT COUNT(*) AS Counter FROM UTILISATEUR WHERE NOM = ? AND MDP = ?";
        $stmt = $this->dataAccess->prepare($query);
        $stmt->execute([$name, $pwd]);
        $result = $stmt->fetch();

        return $result['Counter'] > 0;
    }


    /**
     * @param string $nomInteract
     * @param float $valeurInteract
     * @param int $isEval
     * @param string $ipJoueur
     * @param string $dateInteract
     * @return Interaction|False
     */
    public function addInteraction(string $nomInteract, float $valeurInteract, int $isEval, string $ipJoueur, string $dateInteract): Interaction|False{
        $query = "INSERT INTO INTERACTION (Nom_Inte, Valeur_Inte, Evaluation, Ip_Joueur, Date_Inte) VALUES ('$nomInteract', $valeurInteract,$isEval, '$ipJoueur', '$dateInteract')";
        if ($this->dataAccess->query($query)){
            return new Interaction($nomInteract, $valeurInteract,  $isEval, $ipJoueur, $dateInteract);
        }
        else return false;
    }

    /**
     * @param string $ip
     * @param string $plateforme
     * @return Joueur|False
     */
    public function addJoueur(string $ip, string $plateforme): Joueur|False{
        $query = "INSERT INTO JOUEUR (Ip, Plateforme) VALUES ('$ip', '$plateforme')";
        if($this->dataAccess->query($query)){
            return new Joueur($ip, $plateforme);
        }
        else return false;
    }

    /**
     * @param string $ip
     * @return bool
     */
    public function verifyJoueurExists(string $ip): bool{
        $query = "SELECT COUNT(*) AS Counter FROM JOUEUR WHERE Ip = '$ip'";
        return $this->dataAccess->query($query)->fetch(PDO::FETCH_ASSOC)["Counter"] > 0;
    }

    /**
     * @param string $ipJoueur
     * @param string $dateDeb
     * @return Partie|False
     */
    public function addNewPartie(string $ipJoueur, string $dateDeb): Partie|False{
        $query = "INSERT INTO PARTIE (Ip_Joueur, Date_Deb) VALUES ('$ipJoueur', '$dateDeb')";
        if($this->dataAccess->query($query)){
            return new Partie($dateDeb, $ipJoueur, 0);
        }
        else return false;
    }

    /**
     * @param string $ipJoueur
     * @return void
     */
    public function deleteOnGoingPartie(string $ipJoueur): void{
        $query = "DELETE FROM PARTIE WHERE Ip_Joueur = '$ipJoueur' AND Date_Fin IS NULL";
        $this->dataAccess->query($query);
    }

    /**
     * @param string $ipJoueur
     * @return void
     */
    public function abortOnGoingPartie(string $ipJoueur): void{
        $query = "UPDATE PARTIE SET Abandon = 1 WHERE Ip_Joueur = '$ipJoueur' AND Date_Fin IS NULL AND Abandon = 0";
        $this->dataAccess->query($query);
    }

    /**
     * @param string $ipJoueur
     * @param string $dateFin
     * @return Partie|False
     */
    public function endPartie(string $ipJoueur, string $dateFin): Partie|False{
        $partie = $this->getPartieInProgress($ipJoueur);
        $idGame = $partie->getIdPartie();
        try {
            $score = $this->getPartyScore($idGame);
        } catch (CannotDoException) {
            $score = 0;
        }
        $score = round($score, 2);
        $query = "UPDATE PARTIE SET Date_Fin = '$dateFin', Moy_Questions = $score "
            . "WHERE Id_Partie = $idGame";
        if($this->dataAccess->query($query)){
            $partie->setDateFin($dateFin);
            $partie->setMoyQuestions($score);
            return $partie;
        }
        else return false;
    }

    /**
     * @param string $ipJoueur
     * @return Partie|False
     */
    public function getPartieInProgress(string $ipJoueur): Partie|False{
        $query = "SELECT * FROM PARTIE WHERE Ip_Joueur = '$ipJoueur' AND Date_Fin IS NULL AND Abandon = 0";
        $result = $this->dataAccess->query($query);
        if ($result->rowCount() == 0){
            return false;
        }
        else {
            $partie = $result->fetch(PDO::FETCH_ASSOC);
            return new Partie($partie['Date_Deb'], $ipJoueur, 0, null, null, $partie['Id_Partie']);
        }
    }

    /**
     * @param string $ipJoueur
     * @return bool
     */
    public function verifyPartieInProgress(string $ipJoueur): bool{
        $query = "SELECT COUNT(*) AS Counter FROM PARTIE WHERE Ip_Joueur = '$ipJoueur' AND Date_Fin IS NULL AND Abandon = 0";
        return $this->dataAccess->query($query)->fetch(PDO::FETCH_ASSOC)["Counter"] > 0;
    }

    /**
     * @param int $numQues
     * @param int $idPartie
     * @return UserAnswer
     */
    public function getQuestionCorrect(int $numQues, int $idPartie):  UserAnswer{
        $query = "SELECT * FROM REPONSE_USER WHERE Num_Ques = $numQues AND Id_Partie = $idPartie";
        $result = $this->dataAccess->query($query)->fetch(PDO::FETCH_ASSOC);
        $Date_Fin = date('Y-m-d H:i:s');
        return new UserAnswer($numQues, $idPartie, $result['Date_Deb'], $Date_Fin, $result['Reussite']);
    }

    /**
     * @param int $idPartie
     * @return float|False
     * @throws CannotDoException
     */
    public function getPartyScore(int $idPartie): float|False {
        $query = "SELECT COUNT(*) AS Total, SUM(Reussite) AS Score FROM REPONSE_USER WHERE Id_Partie = $idPartie";
        $result = $this->dataAccess->query($query)->fetch(PDO::FETCH_ASSOC);
        if ($result){
            $count = $result['Total'];

            if ($count == 0){
                $target = "DataBase REPONSE_USER";
                $action = "Calculate score for game party.";
                $explanation = "Game party $idPartie has no questions answered.";
                throw new CannotDoException($target, $action, $explanation);
            }

            return $result['Score'] * (10 / $count);
        }
        else return False;
    }

    /**
     * @param int $numQues
     * @param int $idParty
     * @param string $dateDeb
     * @param string $dateFin
     * @param bool $isCorrect
     * @return void
     */
    public function addQuestionAnswer(int $numQues, int $idParty, string $dateDeb, string $dateFin, bool $isCorrect): void{
        if ($isCorrect){
            $correct = 1;
        }
        else {
            $correct = 0;
        }
        $query = "INSERT INTO REPONSE_USER VALUES ($numQues, $idParty, '$dateDeb', '$dateFin', $correct)";
        $this->dataAccess->query($query);
    }

    /**
     * @param int $numQues
     * @return array|False
     */
    public function getQBasics(int $numQues): array|False{
        $query = "SELECT * FROM QUESTION WHERE Num_Ques = $numQues";
        $result = $this->dataAccess->query($query)->fetch(PDO::FETCH_ASSOC);
        if ($result){
            return $result;
        }
        else return false;
    }

    /**
     * @param int $numQues
     * @return QCU | VraiFaux | QuesInterac| False
     */
    public function getQAttributes(int $numQues): QCU | VraiFaux | QuesInterac | False{
        $basics = $this->getQBasics($numQues);

        if ($basics['Type'] == 'QCU'){
            return $this->getQQCU($numQues);
        }
        else if ($basics['Type'] == 'QUESINTERAC'){
            return $this->getQInteraction($numQues);
        }
        else if ($basics['Type'] == 'VRAIFAUX'){
            return $this->getQVraiFaux($numQues);
        }
        else return false;
    }

    /**
     * @param int $howManyQCU
     * @param int $howManyInterac
     * @param int $howManyVraiFaux
     * @return array
     */
    public function getRandomQs(int $howManyQCU = 0, int $howManyInterac = 0, int $howManyVraiFaux = 0): array{
        return array_merge($this->getRandomQQCU($howManyQCU), $this->getRandomQInterac($howManyInterac), $this->getRandomQVraiFaux($howManyVraiFaux));
    }

    /**
     * @param int $numQues
     * @return Qcu|False
     */
    public function getQQCU(int $numQues): Qcu|False{
        $query = "SELECT * FROM QCU WHERE Num_Ques = $numQues";
        $result = $this->dataAccess->query($query)->fetch(PDO::FETCH_ASSOC);
        if($result){
            $basics = $this->getQBasics($numQues);
            return new Qcu(
                $numQues,
                $basics['Enonce'],
                $basics['Type'],
                $result['Rep1'],
                $result['Rep2'],
                $result['Rep3'],
                $result['Rep4'],
                $result['BonneRep']
            );
        }
        else return false;
    }

    /**
     * @param int $howManyQCU
     * @return array
     */
    public function getRandomQQCU(int $howManyQCU = 0): array{
        $query = "SELECT Num_Ques FROM QCU";
        $result = $this->dataAccess->query($query)->fetchAll();

        shuffle($result);
        $result = array_slice($result, 0, $howManyQCU);
        // Remove arrays of size 1
        for ($count = 0; $count < $howManyQCU; ++$count){
            $result[$count] = $result[$count]['Num_Ques'];
        }
        return $result;
    }

    /**
     * @param int $numQues
     * @return Quesinterac|False
     */
    public function getQInteraction(int $numQues): Quesinterac|False{
        $query = "SELECT * FROM QUESINTERAC WHERE Num_Ques = $numQues";
        $result = $this->dataAccess->query($query)->fetch(PDO::FETCH_ASSOC);
        if($result){
            $basics = $this->getQBasics($numQues);
            return new Quesinterac($numQues, $basics['Enonce'], $basics['Type'], $result['BonneRepValeur_orbit'], $result['Marge_Orbit'], $result['BonneRepValeur_rotation'], $result['Marge_Rotation']);
        }
        else return false;
    }

    /**
     * @param int $howManyInterac
     * @return array
     */
    public function getRandomQInterac(int $howManyInterac = 0): array{
        $query = "SELECT Num_Ques FROM QUESINTERAC";
        $result = $this->dataAccess->query($query)->fetchAll();
        shuffle($result);
        $result = array_slice($result, 0, $howManyInterac);
        // Remove arrays of size 1
        for ($count = 0; $count < $howManyInterac; ++$count){
            $result[$count] = $result[$count]['Num_Ques'];
        }
        return $result;
    }

    /**
     * @param int $numQues
     * @return VraiFaux|False
     */
    public function getQVraiFaux(int $numQues): VraiFaux|False{
        $query = "SELECT * FROM  VRAIFAUX WHERE Num_Ques = $numQues";
        $result = $this->dataAccess->query($query)->fetch(PDO::FETCH_ASSOC);
        if($result){
            $basics = $this->getQBasics($numQues);
            return new VraiFaux($numQues, $basics['Enonce'], $basics['Type'], $result['Valeur_orbit'], $result['Valeur_rotation'], $result['BonneRep']);
        }
        else return false;
    }

    /**
     * @param int $howManyVraiFaux
     * @return array
     */
    public function getRandomQVraiFaux(int $howManyVraiFaux = 0): array{
        $query = "SELECT Num_Ques FROM VRAIFAUX";
        $result = $this->dataAccess->query($query)->fetchAll();
            shuffle($result);
            $result = array_slice($result, 0, $howManyVraiFaux);
            // Remove arrays of size 1
            for ($count = 0; $count < $howManyVraiFaux; ++$count){
                $result[$count] = $result[$count]['Num_Ques'];
            }
            return $result;
    }

    public function getPDO()
    {
    }
}
