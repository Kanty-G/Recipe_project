<?php


require ('./../../includes/headers.php');

require ('./../../includes/db.php');
require ('../objects/recette.php');
$db = new DB();
$pdo = $db->getDb();

$recette = new Recette($pdo);

if(isset($_GET['id'])) {
    $recette->id = $_GET['id'];


$recette->read_one();

if ($recette->nom) {
    
    $recette_arr = array(
        "id" => $recette->id,
        "nom" => $recette->nom,
        "ingredients" => $recette->ingredients,
        "descriptiom" => $recette->description,
        "categorie" => $recette->categorie,
        "image" => $recette->image,
        "date_creation" => $recette->date_creation,
        "owner_id" => $recette->owner_id,
    );

    http_response_code(200);
    echo json_encode($recette_arr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "Recette inexistante")
    );
}

} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "Recette invalide")
    );
}
