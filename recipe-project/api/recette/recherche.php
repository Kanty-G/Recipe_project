<?php


require ('./../../includes/headers.php');
require ('./../../includes/db.php');
require ('../objects/recette.php');
$db = new DB();
$pdo = $db->getDb();

if (isset($_GET['page'])) {
    $page = $_GET['page'];
    if (isset($_GET['keyword'])) {

        $keyword = $_GET['keyword'];
        // Possiblement un ID, sinon on recherche globalement
        $id = 0;
        $type = 0;
        $categorie = 0;

        if (isset($_GET['categorie'])) {
            // Si aucune categorie on default à all
            $categorie = $_GET['categorie'];
        }

        if (isset($_GET['type'])) {
            $type = $_GET['type'];
            if ($type == 1) {
                // Si type est recette d'un user, on doit avoir un user_id
                // Sinon erreur
                if (!isset($_GET['id'])) {
                    http_response_code(404);
                    echo json_encode(
                        array("message" => "User invalide")
                    );
                    exit();
                }

                $id = $_GET['id'];
            }

        }
        // Filtres de recherche
        $filtres = array("type" => $type, "page" => $page, "id" => $id, "keyword" => $keyword, "categorie" => $categorie);

        $recette = new Recette($pdo);

        $res = $recette->recherche($filtres);

        $recette_arr = array();
        $recette_arr['count'] = array();

        $total_recettes = $recette->nombre;

        array_push($recette_arr['count'], $total_recettes);

        if ($total_recettes > 0) {

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
                    "owner_id" => $owner_id,
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
            array("message" => "Keyword invalide")
        );
    }


} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "Page invalide")
    );
}


