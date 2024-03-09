<?php

header('Access-Control-Allow-Origin: * ');
header('Content-Type: application/json');

header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    include_once "../../models/menu.php";
    require_once "../../database/database.php";

    $database = new Database();
    $connexion = $database->GetConnection();

    $menu = new Menu($connexion);
    $stmt = $menu->read();

    $num = $stmt->rowCount();

    $menu_arr = []; // Définir $menu_arr avant la condition

    if ($num > 0) {
        $menu_arr = [];

        $menus = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            if ($num > 0) {
                $menus = [];
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);

                    $aliment = [
                        "id" => $id, // Ajouter l'ID de l'aliment
                        "nom" => $aliment_nom,
                        "quantité" => $aliment_quantite
                    ];
                    $saveur = $saveur_nom;

                    if (!isset($menus[$id])) {
                        $menus[$id] = [
                            "id" => $id,
                            "nom" => $nom,
                            "pieces" => $pieces,
                            "prix" => $prix,
                            "img" => $img,
                            "aliments" => [],
                            "saveurs" => []
                        ];
                    }

                    // Vérifiez si l'aliment est déjà dans le menu. Si ce n'est pas le cas, ajoutez-le.
                    if (!in_array($aliment, $menus[$id]["aliments"])) {
                        $menus[$id]["aliments"][] = $aliment;
                    }

                    // Vérifiez si la saveur est déjà dans le menu. Si ce n'est pas le cas, ajoutez-la.
                    if (!in_array($saveur, $menus[$id]["saveurs"])) {
                        $menus[$id]["saveurs"][] = $saveur;
                    }
                }

                foreach ($menus as $menu) {
                    array_push($menu_arr, $menu);
                }

                http_response_code(200);
                echo json_encode($menu_arr, JSON_PRETTY_PRINT);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Aucun menu trouvé"]);
            }
        }
    }
}
