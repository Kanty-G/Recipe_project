<?php
setcookie("logged_out", "true", time() + 3600, '/');
session_start();

require ("./includes/session_handler.php");
require ("./includes/db.php");
$db = new DB();
$pdo = $db->getDb();


if (isset($_SESSION['id'])) {

    $session_handler = new Session_Handler($pdo);
    $session_status = $session_handler->verify($_SESSION['id'], session_id());
    var_dump($session_status );
    switch($session_status) {
        case -1:
            $_SESSION['errorLog'] = "Session Expirée";
            break;
        case 0:
            $_SESSION['errorLog'] = "Session Inexistante";
    }
    if($_SESSION['errorLog']) {
        header("Location: landing.php");
        exit();
    }
    

}

$session_handler->delete_session($_SESSION['id']);

session_destroy();
header("Location: pages/landing.php");
exit();