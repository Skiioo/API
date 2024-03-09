<?php
class Inscription{

    public $id;
    public $civilite;
    public $prenom;
    public $nom;
    public $adresse;
    public $ville;
    public $code_postal;
    public $telephone;
    public $email;
    public $password;

   private $connexion; 

   private $table = __CLASS__;

   function __construct($connexion){
       $this->connexion = $connexion;
   }


   /**
    * Méthode serveur:POST
    */
    public function create(){
         $sql = "INSERT INTO $this->table (civilite, prenom, nom, adresse, ville, code_postal, telephone, email, password) VALUES (:civilite, :prenom, :nom, :adresse, :ville, :code_postal, :telephone, :email, :password)";
         $stmt = $this->connexion->prepare($sql);
         $stmt->bindParam(':civilite', $this->civilite);
         $stmt->bindParam(':prenom', $this->prenom);
         $stmt->bindParam(':nom', $this->nom);
         $stmt->bindParam(':adresse', $this->adresse);
         $stmt->bindParam(':ville', $this->ville);
         $stmt->bindParam(':code_postal', $this->code_postal);
         $stmt->bindParam(':telephone', $this->telephone);
         $stmt->bindParam(':email', $this->email);
         $stmt->bindParam(':password', $this->password);
         $stmt->execute();
    }

    /**
     * Méthode serveur:GET
     */
    public function read(){
        $sql = "SELECT * FROM $this->table";
        $stmt = $this->connexion->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    public function readOne(){
        $sql = "SELECT * FROM $this->table WHERE id = :id";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Méthode serveur:PUT
     */
   public function update(){
         $sql = "UPDATE $this->table SET civilite = :civilite, prenom = :prenom, nom = :nom, adresse = :adresse, ville = :ville, code_postal = :code_postal, telephone = :telephone, email = :email, password = :password WHERE id = :id";
         $stmt = $this->connexion->prepare($sql);
         $stmt->bindParam(':id', $this->id);
         $stmt->bindParam(':civilite', $this->civilite);
         $stmt->bindParam(':prenom', $this->prenom);
         $stmt->bindParam(':nom', $this->nom);
         $stmt->bindParam(':adresse', $this->adresse);
         $stmt->bindParam(':ville', $this->ville);
         $stmt->bindParam(':code_postal', $this->code_postal);
         $stmt->bindParam(':telephone', $this->telephone);
         $stmt->bindParam(':email', $this->email);
         $stmt->bindParam(':password', $this->password);
         $stmt->execute();
    
   }

   /**
    * Méthode serveur:DELETE
    */
    public function delete(){
          $sql = "DELETE FROM $this->table WHERE id = :id";
          $stmt = $this->connexion->prepare($sql);
          $stmt->bindParam(':id', $this->id);
          $stmt->execute();
    }






}
