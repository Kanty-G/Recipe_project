<?php
session_start();

require ("./includes/session_handler.php");
require ("./includes/db.php");
$db = new DB();
$pdo = $db->getDb();


// Vérification des champs

$email = $_POST['email'];
$password = $_POST['password'];
$pattern = "/^[a-z0-9._\-]+@[a-z0-9.-]+\.[a-z]{2,}$/i";

if (!$email or !$password) {
    $_SESSION['errorLog'] = "Adresse Courriel et Password sont requis";
    header("Location: pages/landing.php");
    exit();
}

if(!preg_match($pattern, $email)) {
    $_SESSION['errorLog'] = "Adresse Courriel invalide";
    header("Location: pages/landing.php");
    exit();
}

// On regarde si le compte existe

$table = "accounts";

$req = $pdo->prepare("SELECT * FROM accounts WHERE email = :email");
$val = array(":email" => $email);

$req->execute($val);
$row = $req->fetch(PDO::FETCH_ASSOC);

if ($row) {
    // Si mdp valide
    if (password_verify($password, $row['password'])) {
        $_SESSION['errorLog'] = null;
        $_SESSION['success'] = null;

        $id = $row['id'];

        $session_handler = new Session_Handler($pdo);
        $session_handler->create_session($id, session_id());

        $_SESSION['id'] = $id;
        header("Location: pages/main.php");
        exit();
    } else {
        $_SESSION['errorLog'] = "Mot de passe invalide";
        header("Location: pages/landing.php");
        exit();
    }
} else {
    $_SESSION['errorLog'] = "Compte inexistant";
    header("Location: pages/landing.php");
    exit();
}