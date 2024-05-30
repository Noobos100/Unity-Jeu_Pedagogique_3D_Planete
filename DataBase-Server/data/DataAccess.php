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
     * @return array
     */
    /*
     *
     public function getPartiesAsc(): array
    {
        $query = "select * from PARTIE order by Date_Fin - Date_Deb = (select min(Date_Fin - Date_Deb) from PARTIE)";
        return $this->dataAccess->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }
*/

    /**
     * @return array
     */
    public function getParties(): array
    {
        $query = "SELECT * FROM PARTIE";
        return $this->dataAccess->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array
     */
    public function getReponsesUsers(): array
    {
        $query = "SELECT * FROM REPONSE_USER";
        return $this->dataAccess->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array
     */
    public function getJoueurs(): array
    {
        $query = "SELECT * FROM JOUEUR";
        return $this->dataAccess->query($query)->fetchAll(PDO::FETCH_ASSOC);
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
    public function addInteraction(string $nomInteract, float $valeurInteract, int $isEval, string $ipJoueur, string $dateInteract): Interaction|false
    {
        $query = "INSERT INTO INTERACTION (Nom_Inte, Valeur_Inte, Evaluation, Ip_Joueur, Date_Inte) VALUES ('$nomInteract', $valeurInteract,$isEval, '$ipJoueur', '$dateInteract')";
        if ($this->dataAccess->query($query)) {
            return new Interaction($nomInteract, $valeurInteract, $isEval, $ipJoueur, $dateInteract);
        } else return false;
    }

    /**
     * @param string $ip
     * @param string $plateforme
	 * @param string $username
     * @return Joueur|False
     */
    public function addJoueur(string $ip, string $plateforme, string $username): Joueur|False{
        $query = "INSERT INTO JOUEUR (Ip, Plateforme, Username) VALUES ('$ip', '$plateforme', '$username')";
        if($this->dataAccess->query($query)){
            return new Joueur($ip, $plateforme, $username);
        }
        else return false;
    }

	public function updateJoueur(string $ip, string $plateforme, string $username): Joueur|false
	{
		$query = "UPDATE JOUEUR SET Plateforme = '$plateforme', Username = '$username' WHERE Ip = '$ip'";
		if($this->dataAccess->query($query)) {
			return new Joueur($ip, $plateforme, $username);
		}
		else return false;
	}

	/**
     * @param string $ip
     * @return bool
     */
    public function verifyJoueurExists(string $ip): bool
    {
        $query = "SELECT COUNT(*) AS Counter FROM JOUEUR WHERE Ip = '$ip'";
        return $this->dataAccess->query($query)->fetch(PDO::FETCH_ASSOC)["Counter"] > 0;
    }

    /**
     * @param string $ipJoueur
     * @param string $dateDeb
     * @return Partie|False
     */
    public function addNewPartie(string $ipJoueur, string $dateDeb): Partie|false
    {
        $query = "INSERT INTO PARTIE (Ip_Joueur, Date_Deb) VALUES ('$ipJoueur', '$dateDeb')";
        if ($this->dataAccess->query($query)) {
            return new Partie($dateDeb, $ipJoueur, 0);
        } else return false;
    }

    /**
     * @param string $ipJoueur
     * @return void
     */
    public function deleteOnGoingPartie(string $ipJoueur): void
    {
        $query = "DELETE FROM PARTIE WHERE Ip_Joueur = '$ipJoueur' AND Date_Fin IS NULL";
        $this->dataAccess->query($query);
    }

    /**
     * @param string $ipJoueur
     * @return void
     */
    public function abortOnGoingPartie(string $ipJoueur): void
    {
        $query = "UPDATE PARTIE SET Abandon = 1 WHERE Ip_Joueur = '$ipJoueur' AND Date_Fin IS NULL AND Abandon = 0";
        $this->dataAccess->query($query);
    }

    /**
     * @param string $ipJoueur
     * @param string $dateFin
     * @return Partie|False
     */
    public function endPartie(string $ipJoueur, string $dateFin): Partie|false
    {
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
        if ($this->dataAccess->query($query)) {
            $partie->setDateFin($dateFin);
            $partie->setMoyQuestions($score);
            return $partie;
        } else return false;
    }

    /**
     * @param string $ipJoueur
     * @return Partie|False
     */
    public function getPartieInProgress(string $ipJoueur): Partie|false
    {
        $query = "SELECT * FROM PARTIE WHERE Ip_Joueur = '$ipJoueur' AND Date_Fin IS NULL AND Abandon = 0";
        $result = $this->dataAccess->query($query);
        if ($result->rowCount() == 0) {
            return false;
        } else {
            $partie = $result->fetch(PDO::FETCH_ASSOC);
            return new Partie($partie['Date_Deb'], $ipJoueur, 0, null, null, $partie['Id_Partie']);
        }
    }

    /**
     * @param string $ipJoueur
     * @return bool
     */
    public function verifyPartieInProgress(string $ipJoueur): bool
    {
        $query = "SELECT COUNT(*) AS Counter FROM PARTIE WHERE Ip_Joueur = '$ipJoueur' AND Date_Fin IS NULL AND Abandon = 0";
        return $this->dataAccess->query($query)->fetch(PDO::FETCH_ASSOC)["Counter"] > 0;
    }

    /**
     * @param int $numQues
     * @param int $idPartie
     * @return UserAnswer
     */
    public function getQuestionCorrect(int $numQues, int $idPartie): UserAnswer
    {
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
    public function getPartyScore(int $idPartie): float|false
    {
        $query = "SELECT COUNT(*) AS Total, SUM(Reussite) AS Score FROM REPONSE_USER WHERE Id_Partie = $idPartie";
        $result = $this->dataAccess->query($query)->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $count = $result['Total'];

            if ($count == 0) {
                $target = "DataBase REPONSE_USER";
                $action = "Calculate score for game party.";
                $explanation = "Game party $idPartie has no questions answered.";
                throw new CannotDoException($target, $action, $explanation);
            }

            return $result['Score'] * (10 / $count);
        } else return False;
    }

    public function addQuestion(string $enonce, string $type): int
    {
        $query = "INSERT INTO QUESTION (Enonce, Type) VALUES (:enonce, :type)";
        $stmt = $this->dataAccess->prepare($query);
        $stmt->bindParam(':enonce', $enonce);
        $stmt->bindParam(':type', $type);
        $stmt->execute();
        return $this->dataAccess->lastInsertId();
    }

    public function addQCU(int $numQues, string $rep1, string $rep2, string $rep3, string $rep4, int $bonneRep): int
    {
        $query = "INSERT INTO QCU (Num_Ques, Rep1, Rep2, Rep3, Rep4, BonneRep) VALUES (:numQues, :rep1, :rep2, :rep3, :rep4, :bonneRep)";
        $stmt = $this->dataAccess->prepare($query);
        $stmt->bindParam(':numQues', $numQues);
        $stmt->bindParam(':rep1', $rep1);
        $stmt->bindParam(':rep2', $rep2);
        $stmt->bindParam(':rep3', $rep3);
        $stmt->bindParam(':rep4', $rep4);
        $stmt->bindParam(':bonneRep', $bonneRep);
        $stmt->execute();
        return $this->dataAccess->lastInsertId();
    }

    public function addQInterac(int $numQues, float $bonneRepValeur_orbit, float $marge_Orbit, float $bonneRepValeur_rotation, float $marge_Rotation): int
    {
        $query = "INSERT INTO QUESINTERAC (Num_Ques, BonneRepValeur_orbit, Marge_Orbit, BonneRepValeur_rotation, Marge_Rotation) VALUES (:numQues, :bonneRepValeur_orbit, :marge_Orbit, :bonneRepValeur_rotation, :marge_Rotation)";
        $stmt = $this->dataAccess->prepare($query);
        $stmt->bindParam(':numQues', $numQues);
        $stmt->bindParam(':bonneRepValeur_orbit', $bonneRepValeur_orbit);
        $stmt->bindParam(':marge_Orbit', $marge_Orbit);
        $stmt->bindParam(':bonneRepValeur_rotation', $bonneRepValeur_rotation);
        $stmt->bindParam(':marge_Rotation', $marge_Rotation);
        $stmt->execute();
        return $this->dataAccess->lastInsertId();
    }

    public function addQVraiFaux(string $enonce, ?string $valeur_orbit, ?string $valeur_rotation, string $bonneRep): void
    {
        $query = "INSERT INTO QUESTION (Enonce, Type) VALUES (:enonce, 'VRAIFAUX')";
        $stmt = $this->dataAccess->prepare($query);
        $stmt->bindParam(':enonce', $enonce);
        $stmt->execute();

        $lastQID = $this->dataAccess->lastInsertId();

        if (empty($valeur_orbit)) {
            $valeur_orbit = null;
        }

        if (empty($valeur_rotation)) {
            $valeur_rotation = null;
        }

        $query2 = "INSERT INTO VRAIFAUX (Num_Ques, Valeur_orbit, Valeur_rotation, BonneRep) VALUES (:numQues, :valeur_orbit, :valeur_rotation, :bonneRep)";
        $stmt2 = $this->dataAccess->prepare($query2);
        $stmt2->bindParam(':numQues', $lastQID);
        $stmt2->bindParam(':valeur_orbit', $valeur_orbit);
        $stmt2->bindParam(':valeur_rotation', $valeur_rotation);
        $stmt2->bindParam(':bonneRep', $bonneRep);
        $stmt2->execute();
    }

    /**
     * @param int $numQues
     * @param int $idParty
     * @param string $dateDeb
     * @param string $dateFin
     * @param bool $isCorrect
     * @return void
     */
    public function addQuestionAnswer(int $numQues, int $idParty, string $dateDeb, string $dateFin, bool $isCorrect): void
    {
        if ($isCorrect) {
            $correct = 1;
        } else {
            $correct = 0;
        }
        $query = "INSERT INTO REPONSE_USER VALUES ($numQues, $idParty, '$dateDeb', '$dateFin', $correct)";
        $this->dataAccess->query($query);
    }

    /**
     * @param int $numQues
     * @return array|False
     */
    public function getQBasics(int $numQues): array|false
    {
        $query = "SELECT * FROM QUESTION WHERE Num_Ques = $numQues";
        $result = $this->dataAccess->query($query)->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result;
        } else return false;
    }

    /**
     * @param int $numQues
     * @return QCU | VraiFaux | QuesInterac| False
     */
    public function getQAttributes(int $numQues): QCU|VraiFaux|QuesInterac|false
    {
        $basics = $this->getQBasics($numQues);

        if ($basics['Type'] == 'QCU') {
            return $this->getQQCU($numQues);
        } else if ($basics['Type'] == 'QUESINTERAC') {
            return $this->getQInteraction($numQues);
        } else if ($basics['Type'] == 'VRAIFAUX') {
            return $this->getQVraiFaux($numQues);
        } else return false;
    }

    /**
     * @param int $howManyQCU
     * @param int $howManyInterac
     * @param int $howManyVraiFaux
     * @return array
     */
    public function getRandomQs(int $howManyQCU = 0, int $howManyInterac = 0, int $howManyVraiFaux = 0): array
    {
        return array_merge($this->getRandomQQCU($howManyQCU), $this->getRandomQInterac($howManyInterac), $this->getRandomQVraiFaux($howManyVraiFaux));
    }

    /**
     * @param int $numQues
     * @return Qcu|False
     */
    public function getQQCU(int $numQues): Qcu|false
    {
        $query = "SELECT * FROM QCU WHERE Num_Ques = $numQues";
        $result = $this->dataAccess->query($query)->fetch(PDO::FETCH_ASSOC);
        if ($result) {
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
        } else return false;
    }

    /**
     * @param int $howManyQCU
     * @return array
     */
    public function getRandomQQCU(int $howManyQCU = 0): array
    {
        $query = "SELECT Num_Ques FROM QCU";
        $result = $this->dataAccess->query($query)->fetchAll();

        shuffle($result);
        $result = array_slice($result, 0, $howManyQCU);
        // Remove arrays of size 1
        for ($count = 0; $count < $howManyQCU; ++$count) {
            $result[$count] = $result[$count]['Num_Ques'];
        }
        return $result;
    }

    /**
     * @param int $numQues
     * @return Quesinterac|False
     */
    public function getQInteraction(int $numQues): Quesinterac|false
    {
        $query = "SELECT * FROM QUESINTERAC WHERE Num_Ques = $numQues";
        $result = $this->dataAccess->query($query)->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $basics = $this->getQBasics($numQues);
            return new Quesinterac($numQues, $basics['Enonce'], $basics['Type'], $result['BonneRepValeur_orbit'], $result['Marge_Orbit'], $result['BonneRepValeur_rotation'], $result['Marge_Rotation']);
        } else return false;
    }

    /**
     * @param int $howManyInterac
     * @return array
     */
    public function getRandomQInterac(int $howManyInterac = 0): array
    {
        $query = "SELECT Num_Ques FROM QUESINTERAC";
        $result = $this->dataAccess->query($query)->fetchAll();
        shuffle($result);
        $result = array_slice($result, 0, $howManyInterac);
        // Remove arrays of size 1
        for ($count = 0; $count < $howManyInterac; ++$count) {
            $result[$count] = $result[$count]['Num_Ques'];
        }
        return $result;
    }

    /**
     * @param int $numQues
     * @return VraiFaux|False
     */
    public function getQVraiFaux(int $numQues): VraiFaux|false
    {
        $query = "SELECT * FROM VRAIFAUX WHERE Num_Ques = $numQues";
        $result = $this->dataAccess->query($query)->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $basics = $this->getQBasics($numQues);
            return new VraiFaux($numQues, $basics['Enonce'], $basics['Type'], $result['Valeur_orbit'], $result['Valeur_rotation'], $result['BonneRep']);
        } else return false;
    }

    /**
     * @param int $howManyVraiFaux
     * @return array
     */
    public function getRandomQVraiFaux(int $howManyVraiFaux = 0): array
    {
        $query = "SELECT Num_Ques FROM VRAIFAUX";
        $result = $this->dataAccess->query($query)->fetchAll();
        shuffle($result);
        $result = array_slice($result, 0, $howManyVraiFaux);
        // Remove arrays of size 1
        for ($count = 0; $count < $howManyVraiFaux; ++$count) {
            $result[$count] = $result[$count]['Num_Ques'];
        }
        return $result;
    }

    public function updateQCU(int $numQues, string $question, string $rep1, string $rep2, string $rep3, string $rep4, string $bonneRep): void
    {
        $query = "UPDATE QCU SET Rep1 = :rep1, Rep2 = :rep2, Rep3 = :rep3, Rep4 = :rep4 WHERE Num_Ques = :numQues";
        $query2 = "UPDATE QCU SET BonneRep = " . $bonneRep . " WHERE Num_Ques = :numQues";
        $query3 = "UPDATE QUESTION SET Enonce = :question WHERE Num_Ques = :numQues";

        $stmt = $this->dataAccess->prepare($query);
        $stmt2 = $this->dataAccess->prepare($query2);
        $stmt3 = $this->dataAccess->prepare($query3);

        $stmt->bindParam(':numQues', $numQues);
        $stmt->bindParam(':rep1', $rep1);
        $stmt->bindParam(':rep2', $rep2);
        $stmt->bindParam(':rep3', $rep3);
        $stmt->bindParam(':rep4', $rep4);
        $stmt->execute();

        $stmt2->bindParam(':numQues', $numQues);
        $stmt2->execute();

        $stmt3->bindParam(':question', $question);
        $stmt3->bindParam(':numQues', $numQues);
        $stmt3->execute();
    }

    public function updateQVraiFaux(int $numQues, string $question, string $orbite, string $rotation, string $correct): void
    {
        $orbite = empty($orbite) ? null : $orbite;
        $rotation = empty($rotation) ? null : $rotation;

        $query = "UPDATE QUESTION SET Enonce = :question WHERE Num_Ques = :numQues";
        $stmt = $this->dataAccess->prepare($query);
        $stmt->bindParam(':question', $question);
        $stmt->bindParam(':numQues', $numQues);
        $stmt->execute();

        $query2 = "UPDATE VRAIFAUX SET BonneRep = :correct, Valeur_orbit = :orbite, Valeur_rotation = :rotation WHERE Num_Ques = :numQues";
        $stmt2 = $this->dataAccess->prepare($query2);
        $stmt2->bindParam(':correct', $correct);
        $stmt2->bindParam(':orbite', $orbite, PDO::PARAM_NULL | PDO::PARAM_STR);
        $stmt2->bindParam(':rotation', $rotation, PDO::PARAM_NULL | PDO::PARAM_STR);
        $stmt2->bindParam(':numQues', $numQues);
        $stmt2->execute();
    }

    public function updateQInterac(int $numQues, string $question, string $orbite, string $rotation, string $rotationMargin, string $orbitMargin): void
    {
        $query = "UPDATE QUESTION SET Enonce = :question WHERE Num_Ques = :numQues";
        $stmt = $this->dataAccess->prepare($query);
        $stmt->bindParam(':question', $question);
        $stmt->bindParam(':numQues', $numQues);
        $stmt->execute();

        $query2 = "UPDATE QUESINTERAC SET BonneRepValeur_orbit = :orbite, BonneRepValeur_rotation = :rotation, Marge_Orbit = :orbitMargin, Marge_Rotation = :rotationMargin WHERE Num_Ques = :numQues";
        $stmt2 = $this->dataAccess->prepare($query2);
        $stmt2->bindParam(':orbite', $orbite);
        $stmt2->bindParam(':rotation', $rotation);
        $stmt2->bindParam(':orbitMargin', $orbitMargin);
        $stmt2->bindParam(':rotationMargin', $rotationMargin);
        $stmt2->bindParam(':numQues', $numQues);
        $stmt2->execute();
    }

    public function deleteQuestion(int $numQues): void
    {
        $query = "DELETE FROM QUESTION WHERE Num_Ques = :numQues";
        $stmt = $this->dataAccess->prepare($query);
        $stmt->bindParam(':numQues', $numQues, PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * @return array
     */
    public function getAllQ(): array{
        $query = "SELECT * FROM QUESTION ORDER BY Num_Ques DESC";
        return $this->dataAccess->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

}
