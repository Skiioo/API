<?php

class Menu
{
    public $id;
    public $nom;
    public $pieces;
    public $prix;
    public $img;

    public $aliment_nom;
    public $aliment_quantite;
    public $saveur_nom;
    public $saveur_quantite;

    private $connexion;

    private $table = __CLASS__;

    function __construct($connexion)
    {
        $this->connexion = $connexion;
    }

    /**
     * Méthode serveur:POST
     */

    public function create($data)
    {

        // Requête pour insérer le menu
        $sql = "INSERT INTO $this->table (nom, pieces, prix, img) VALUES (:nom, :pieces, :prix, :img)";
        $stmt_menu = $this->connexion->prepare($sql);
        $stmt_menu->bindParam(':nom', $data['nom']);
        $stmt_menu->bindParam(':pieces', $data['pieces']);
        $stmt_menu->bindParam(':prix', $data['prix']);
        $stmt_menu->bindParam(':img', $data['img']);

        $stmt_menu->execute();


        // Récupération de l'ID du menu inséré
        $menu_id = $this->connexion->lastInsertId();

        // Insérer les aliments spécifiés
        foreach ($data['aliments'] as $alimentId) {
            $sql_insert_aliment = "INSERT INTO contenir (id_aliment, id_menu) VALUES (:id_aliment, :id_menu)";
            $stmt_insert_aliment = $this->connexion->prepare($sql_insert_aliment);
            $stmt_insert_aliment->bindParam(':id_menu', $menu_id);
            $stmt_insert_aliment->bindParam(':id_aliment', $alimentId);
            $stmt_insert_aliment->execute();
        }

        foreach ($data['saveurs'] as $saveurId) {
            $sql_insert_saveur = "INSERT INTO commander (id_saveur, id_menu) VALUES (:id_saveur, :id_menu)";
            $stmt_insert_saveur = $this->connexion->prepare($sql_insert_saveur);
            $stmt_insert_saveur->bindParam(':id_menu', $menu_id);
            $stmt_insert_saveur->bindParam(':id_saveur', $saveurId);
            $stmt_insert_saveur->execute();
        }



        return true; // Succès

    }


    /**
     * Méthode serveur:GET
     */
    public function read() // Méthode pour lire tous les menus
    {
        $sql = " 
        SELECT 
        menu.id, 
        menu.nom, 
        menu.prix, 
        menu.img, 
        menu.pieces, 
        aliment.nom as aliment_nom, 
        COUNT(contenir.id_aliment) as aliment_quantite, 
        GROUP_CONCAT(DISTINCT saveur.nom) as saveur_nom, 
        GROUP_CONCAT(DISTINCT saveur.quantite) as saveur_quantite 
    FROM 
        menu 
    JOIN 
        contenir ON contenir.id_menu = menu.id 
    JOIN 
        aliment ON contenir.id_aliment = aliment.id 
    JOIN 
        commander ON commander.id_menu = menu.id 
    JOIN 
        saveur ON commander.id_saveur = saveur.id 
    GROUP BY 
        menu.id, 
        aliment.id,
        saveur.id
        
        ";

        $stmt = $this->connexion->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    public function readOne()
    {
        $sql = "SELECT DISTINCT saveur.id, saveur.nom, saveur.quantite FROM menu, commander, saveur WHERE menu.id = commander.id_menu AND saveur.id = commander.id_saveur AND menu.id = :id;";
        $sql2 = " SELECT DISTINCT aliment.id,aliment.nom,aliment.quantite FROM menu, aliment, contenir WHERE menu.id = contenir.id_menu AND aliment.id = contenir.id_aliment AND menu.id = :id;";
        $sql3 = "SELECT DISTINCT menu.id, menu.nom, menu.prix, menu.img, menu.pieces FROM menu WHERE id = :id;";
        /**
         *  saveur
         */
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        /**
         * aliment
         */

        $stmt2 = $this->connexion->prepare($sql2);
        $stmt2->bindParam(':id', $this->id);
        $stmt2->execute();

        /**
         * menu
         */
        $stmt3 = $this->connexion->prepare($sql3);
        $stmt3->bindParam(':id', $this->id);
        $stmt3->execute();

        return array($stmt, $stmt2, $stmt3);
    }

    public function readByNom() // Méthode pour lire un menu par son nom
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
    public function update()
    {
        $sql = "UPDATE $this->table SET nom = :nom, pieces = :pieces, prix = :prix, img = :img WHERE id = :id";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':pieces', $this->pieces);
        $stmt->bindParam(':prix', $this->prix);
        $stmt->bindParam(':img', $this->img);
        $stmt->execute();
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function delete() // Méthode pour supprimer un menu
    {
        $menu_id = $this->id; // Utilisez l'ID du menu défini dans la requête DELETE

        // Supprimer les aliments associés au menu
        $sql_delete_aliment = "DELETE FROM contenir WHERE id_menu = :id_menu";
        $stmt_delete_aliment = $this->connexion->prepare($sql_delete_aliment);
        $stmt_delete_aliment->bindParam(':id_menu', $menu_id);
        $stmt_delete_aliment->execute();

        // Supprimer les saveurs associées au menu
        $sql_delete_saveur = "DELETE FROM commander WHERE id_menu = :id_menu";
        $stmt_delete_saveur = $this->connexion->prepare($sql_delete_saveur);
        $stmt_delete_saveur->bindParam(':id_menu', $menu_id);
        $stmt_delete_saveur->execute();

        // Supprimer le menu
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $stmt_menu = $this->connexion->prepare($sql);
        $stmt_menu->bindParam(':id', $menu_id);
        $stmt_menu->execute();

        // Vérifiez si le menu a été supprimé
        if ($stmt_menu->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
/*
   * CREATE TABLE IF NOT EXISTS `menu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) DEFAULT NULL,
  `pieces` int DEFAULT NULL,
  `prix` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
   
   */