<?php
header('Access-Control-Allow-Origin: * ');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once "../../models/saveur.php";
    require_once "../../database/database.php";

    $database = new Database();
    $connexion = $database->GetConnection();

    $aliment = new Saveur($connexion);

    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->nom, $data->id)) {
        http_response_code(400);
        echo json_encode(["message" => "Impossible de créer l'aliment. Les données sont incomplètes"]);
        exit();
    }

    $aliment->nom = $data->nom;
    $aliment->id = $data->id;

    if ($aliment->create()) {
        http_response_code(201);
        echo json_encode(["message" => "Saveur créé"]);
    } else {
        http_response_code(503);
        echo json_encode(["message" => "Impossible de créer la saveur"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Méthode non autorisée"]);
}
