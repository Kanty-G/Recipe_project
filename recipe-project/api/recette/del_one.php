<?php

require ('./../../includes/headers.php');
require ('./../../includes/db.php');
require ('../objects/recette.php');
$db = new DB();
$pdo = $db->getDb();

$recette = new Recette($pdo);

if (isset($_GET['user_id'])) {
    if (isset($_GET['recette_id'])) {
        // Récup la recette
        $recette->id = $_GET['recette_id'];
        $recette->read_one();
        // Check si user_id = owner de la recette ou admin
        if ($recette->owner_id != $_GET['user_id'] && $_GET['user_id'] != 1) {
            http_response_code(404);
            echo json_encode(
                array("message" => "Vous n'avez pas la permission")
            );
        }
        $recette->del_one();
        http_response_code(200);
        echo json_encode(
            array("message" => "Recette supprimée")
        );
    } else {
        http_response_code(404);
        echo json_encode(
            array("message" => "Recette invalide")
        );
    }

} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "Utilisateur invalide")
    );
}