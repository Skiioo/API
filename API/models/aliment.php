<?php

class Aliment
{
    public $id;
    public $nom;

    private $connexion;

    private $table = __CLASS__;

    function __construct($connexion)
    {
        $this->connexion = $connexion;
    }

    /**
     * Méthode serveur:POST
     */
    public function create()  // Méthode pour ajouter un aliment
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

    /**
     * Méthode serveur:GET
     */
    public function read() // Méthode pour lire tous les aliments
    {
        $sql = "SELECT * FROM $this->table";
        $stmt = $this->connexion->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() // Méthode pour lire un aliment
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt;
    }

    public function readByNom() // Méthode pour lire un aliment par son nom
    {
        $sql = "SELECT * FROM $this->table WHERE nom = :nom";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindParam(':nom', $this->nom);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Méthode serveur:PUT
     */
    public function update() // Méthode pour modifier un aliment
    {
        $sql = "UPDATE $this->table SET nom = :nom WHERE id = :id";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':nom', $this->nom);
        $stmt->execute();
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Méthode serveur:DELETE
     */
    public function delete() // Méthode pour supprimer un aliment
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
