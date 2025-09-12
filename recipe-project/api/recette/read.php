<?php


require ('./../../includes/headers.php');
require ('./../../includes/db.php');
require ('../objects/recette.php');
$db = new DB();
$pdo = $db->getDb();

// Check si page
if (isset($_GET['page'])) {
    $page = $_GET['page'];

    $recette = new Recette($pdo);

    // On récupère chaque recette
    $res = $recette->read($page);
    $row_num = $res->rowCount();

    $recette_arr = array();
    $recette_arr['count'] = array();

    // Nombre total de recettes dans la page
    $total_recettes = $recette->read_total();

    array_push($recette_arr['count'], $total_recettes);

    if ($row_num > 0) {

        $recette_arr['recettes'] = array();

        $rows = $res->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            extract($row);

            $recette_item = array(
                "id" => $id,
                "nom" => $nom,
                "ingredients" => $ingredients,
                "description" => $description,
                "categorie" => $categorie,
                "image" => $image,
                "date_creation" => $date_creation,
                "owner_id" => $recette->owner_id,
            );

            array_push($recette_arr["recettes"], $recette_item);
        }

        http_response_code(200);
        echo json_encode($recette_arr);
    } else {
        http_response_code(404);
        echo json_encode(
            array("message" => "Pas de recettes trouvées")
        );
    }

} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "Page invalide")
    );
}


