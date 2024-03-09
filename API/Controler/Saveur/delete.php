<?php
header('Access-Control-Allow-Origin: * ');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    include_once "../../models/saveur.php";
    require_once "../../database/database.php";

    $database = new Database();
    $connexion = $database->GetConnection();

    $nom = new Saveur($connexion);

    $data = json_decode(file_get_contents("php://input"));


    $nom->id = $data->id;

    if ($nom->delete()) {
        http_response_code(200);
        echo json_encode(["message" => "Saveur supprimé"]);
    } 
 else {
    http_response_code(400);
    echo json_encode(["message" => "Impossible de supprimer l'aliment. Les données sont incomplètes"]);
}
}