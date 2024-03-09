<?php
header('Access-Control-Allow-Origin: * ');
header('Content-Type: application/json');

header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


if ($_SERVER["REQUEST_METHOD"] == "GET") {
    include_once "../../models/saveur.php";
    require_once "../../database/database.php";

    $database = new Database();
    $connexion = $database->GetConnection();

    $saveur = new Saveur($connexion);
    $stmt = $saveur->read();
    $num = $stmt->rowCount();



    if ($num > 0) {
        $saveur_arr = [];
        $saveur_arr["Saveurs"] = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $saveur_item = [
                "id" => $id,
                "nom" => $nom,
            ];
            array_push($saveur_arr["Saveurs"], $saveur_item);
        }
        http_response_code(200);
        echo json_encode($saveur_arr);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "Aucune saveur trouvée"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
