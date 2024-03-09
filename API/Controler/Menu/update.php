<?php

header('Access-Control-Allow-Origin: * ');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    include_once "../../models/menu.php";
    require_once "../../database/database.php";

    $database = new Database();
    $connexion = $database->GetConnection();

    $menu = new Menu($connexion);

    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->nom) && !empty($data->pieces) && !empty($data->prix) && !empty($data->img) && !empty($data->id)) { // Corrected here{
        $menu->id = $data->id;
        $menu->nom = $data->nom;
        $menu->pieces = $data->pieces;
        $menu->prix = $data->prix;
        $menu->img = $data->img;
    }

    $menu->id = isset($data->id) ? $data->id : null;
    $menu->nom = isset($data->nom) ? $data->nom : null;
    $menu->pieces = isset($data->pieces) ? $data->pieces : null; // Corrected here
    $menu->prix = isset($data->prix) ? $data->prix : null;
    $menu->img = isset($data->img) ? $data->img : null;

    if ($menu->update()) {
        http_response_code(200);
        echo json_encode(["message" => "Menu mis à jour"]);
    } else {
        http_response_code(503);
        echo json_encode(["message" => "Impossible de mettre à jour le menu"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Impossible de mettre à jour le menu. Les données sont incomplètes"]);
}
