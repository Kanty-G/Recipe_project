<?php

require ('./../../includes/headers.php');
require ('./../../includes/db.php');
require ('../objects/recette.php');

$db = new DB();
$pdo = $db->getDb();

$recette = new Recette($pdo);


$res = $recette->last_updated();
echo json_encode( array("last_updated" => $res));
if($res) {
    http_response_code(200);
} else {
    http_response_code(201);
}