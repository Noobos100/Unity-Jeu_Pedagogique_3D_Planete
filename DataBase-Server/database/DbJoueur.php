<?php

namespace database;

class DbJoueur
{

    private string $dbName = "JOUEUR";

    private \mysqli $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }

    public function addJoueur(string $ip, string $plateforme): void{
        $query = "INSERT INTO " . $this->dbName .
            " (Ip, Plateforme) VALUES ('$ip', '$plateforme')";
        $this->conn->query($query);
    }

}