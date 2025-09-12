<?php


require ('./../../includes/headers.php');

require ('./../../includes/db.php');
require ('../objects/recette.php');
$db = new DB();
$pdo = $db->getDb();

if (isset($_GET['id'])) {
    if (isset($_GET['page'])) {
        $user_id = $_GET['id'];
        $page = $_GET['page'];

        $recette = new Recette($pdo);
        $recette->owner_id = $user_id;
        $res = $recette->read_user($page);
        $row_num = $res->rowCount();

        $recette_arr = array();
        $recette_arr['count'] = array();

        $total_recettes = $recette->read_user_total();

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
                    "etapes" => $etapes,
                    "categorie" => $categorie,
                    "image" => $image
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
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "Utilisateur invalide")
    );
}