<?php
require ('./../../includes/headers.php');
require ('./../../includes/db.php');
require ('../objects/recette.php');
$db = new DB();
$pdo = $db->getDb();

$recette = new Recette($pdo);


$input = json_decode(file_get_contents('php://input'));

// Vérif
if (
    isset($input->nom) and
    isset($input->date) and
    isset($input->categorie) and
    isset($input->description) and
    isset($input->ingredients) and
    isset($input->user_id) and
    isset($input->imageURL)
) {
    // Passe les valeurs
    $recette->nom = $input->nom;
    $recette->date_creation = $input->date;
    $recette->categorie = $input->categorie;
    $recette->description = $input->description;
    $recette->ingredients = $input->ingredients;
    $recette->image = $input->imageURL;

    $recette->owner_id = $input->user_id;
    
    // On l'ajoute
    $res = $recette->ajout();
    if($res) {
        http_response_code(201);
        echo json_encode(array(
            "message" => "Recette ajoutée"
        ));
    } else {
        http_response_code(500);
        echo json_encode(array(
            "message" => "Erreur"
        ));
    }
} else {
    http_response_code(400);
    echo json_encode(array(
        "message" => "Champ(s) invalide(s)"
    ));
}