<?php
class Database
{
    private $serveur = "localhost";
    private $utilisateur = "root";
    private $mot_de_passe  = "";
    private $base_de_donnees = "testapi3";
    public $connexion;



    public function GetConnection()
    {
        $this->connexion = null;

        try {
            $this->connexion = new PDO("mysql:host=" . $this->serveur . ";dbname=" . $this->base_de_donnees, $this->utilisateur, $this->mot_de_passe);
            $this->connexion->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Erreur de connexion : " . $exception->getMessage();
        }

        return $this->connexion;
    }

    public function close()
    {
        $this->connexion->close();
    }

    public function query($sql)
    {
        return $this->connexion->query($sql);
    }

    public function real_escape_string($string)
    {
        return $this->connexion->real_escape_string($string);
    }
}
