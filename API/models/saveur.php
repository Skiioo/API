<?php

class Saveur
{
    public $id;
    public $nom;

    private $connexion;
    private $table = __CLASS__;

    function __construct($connexion)
    {
        $this->connexion = $connexion;
    }

    public function create()
    {
        $sql = "INSERT INTO $this->table (nom) VALUES (:nom)";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindParam(':nom', $this->nom);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function read()
    {
        $sql = "SELECT * FROM $this->table";
        $stmt = $this->connexion->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    public function readOne()
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt;
    }

    public function update()
    {
        $sql = "UPDATE $this->table SET nom = :nom WHERE id = :id";
        $stmt = $this->connexion->prepare($sql);


        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':id', $this->id);

        $stmt->execute();
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}




/** 
 * CREATE TABLE IF NOT EXISTS `saveur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
 */
