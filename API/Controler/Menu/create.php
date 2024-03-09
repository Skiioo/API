<?php
header('Access-Control-Allow-Origin: * ');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once "../../models/menu.php";
    require_once "../../database/database.php";

    $database = new Database();
    $connexion = $database->GetConnection();

    $menu = new Menu($connexion);

    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['nom'], $data['pieces'], $data['prix'], $data['img'], $data['aliments'], $data['saveurs'])) {
        http_response_code(400);
        echo json_encode(["message" => "Impossible de créer le menu. Les données sont incomplètes"]);
        exit();
    }

    if ($menu->create($data)) {
        http_response_code(201);
        echo json_encode(["message" => "Menu créé"]);
    } else {
        http_response_code(503);
        echo json_encode(["message" => "Impossible de créer le menu"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Méthode non autorisée"]);
}
