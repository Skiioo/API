<?php

header('Access-Control-Allow-Origin: * ');
header('Content-Type: application/json');


header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER["REQUEST_METHOD"] == "GET"){
    include_once "../../models/aliment.php";
    require_once "../../database/database.php";

    $database = new Database(); 
    $connexion = $database->GetConnection();

    $aliment = new Aliment($connexion);
    $stmt = $aliment->read();
    $num = $stmt->rowCount();

    if($num > 0){
        $aliment_arr = [];
        $aliment_arr["Aliments"] = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $aliment_item = [
                "id" => $id,
                "nom" => $nom,
            ];
            array_push($aliment_arr["Aliments"], $aliment_item);
        }
        http_response_code(200);
        echo json_encode($aliment_arr);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "Aucun aliment trouvé"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}