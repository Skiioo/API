<?php

header('Access-Control-Allow-Origin: * ');
header('Content-Type: application/json');

header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../../database/database.php";
require_once "../../models/menu.php";

// Créer une nouvelle instance de la base de données
$database = new Database();
$connexion = $database->GetConnection();

// Créer une nouvelle instance de Menu
$menu = new Menu($connexion);

// Définir l'ID du menu à lire
if (isset($_GET['id'])) {
    $menu->id = $_GET['id'];
} else {
    die(json_encode(['message' => 'No ID provided']));
}

// Lire le menu
$stmt = $menu->readOne();

/**
 *  saveur part
 */
$saveur = [];
$saveur_tab = [];
while ($row = $stmt[0]->fetch(PDO::FETCH_ASSOC)) {
    foreach ($row as $key => $value) {
        $saveur[$key] = $value;
    }
    array_push($saveur_tab, $saveur);
}

/**
 * aliment part
 */
$aliment = [];
$aliment_tab = [];
while ($row = $stmt[1]->fetch(PDO::FETCH_ASSOC)) {
    foreach ($row as $key => $value) {
        $aliment[$key] = $value;
    }
    array_push($aliment_tab, $aliment);
}

/**
 * menu part
 */
$menu = [];
while ($row = $stmt[2]->fetch(PDO::FETCH_ASSOC)) {
    foreach ($row as $key => $value) {
        $menu[$key] = $value;
    }
}

$menu['aliment'] = $aliment_tab;
$menu['saveur'] = $saveur_tab;
http_response_code(200);
echo json_encode($menu);
