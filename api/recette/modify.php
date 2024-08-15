<?php

require ('./../../includes/headers.php');
require ('./../../includes/db.php');
require ('../objects/recette.php');

$db = new DB();
$pdo = $db->getDb();

$recette = new Recette($pdo);

$input = json_decode(file_get_contents('php://input'));

// Récup la recette
$recette->id = $input->id;
$recette->read_one();
// Check si user_id = owner de la recette ou si c'est l'admin
if ($recette->owner_id != $input->user_id && $input->user_id != 1) {
    http_response_code(404);
    echo json_encode(
        array("message" => "Vous n'avez pas la permission")
    );
}
// Sinon on set les potentiels nouveaux champs
$recette->nom = $input->nom;
$recette->date_creation = $input->date_creation;
$recette->categorie = $input->categorie;
$recette->description = $input->description;
$recette->ingredients = $input->ingredients;
$recette->image = $input->image;

$res = $recette->modify();

if($res) {
    http_response_code(200);
    echo json_encode(
        array("message" => "Recette modifiée")
    );
} else {
    http_response_code(500);
    echo json_encode(
        array("message" => "Erreur")
    );
}

