<?php
session_start();

require ("./includes/db.php");
$db = new DB();
$pdo = $db->getDb();

var_dump($_POST);

// Vérification des champs

if (!$_POST['email'] or !$_POST['password'] or !$_POST['prenom'] or !$_POST['nom']) {
    $_SESSION['errorIns'] = "Tous les champs sont requis";
    header("Location: pages/inscription.php");
    exit();
}

// On regarde si le compte existe déjà

$email = $_POST['email'];
$prenom = $_POST['prenom'];
$nom = $_POST['nom'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);


// Si le user a manipulé le form et send une adresse courriel invalide
$pattern = "/^[a-z0-9._\-]+@[a-z0-9.-]+\.[a-z]{2,}$/i";

if(!preg_match($pattern, $email)) {
    $_SESSION['errorIns'] = "Adresse Courriel invalide";
    header("Location: pages/landing.php");
    exit();
}

$table = "accounts";

$req = $pdo->prepare("SELECT * FROM `accounts` WHERE `email` = :email");
$val = array(":email" => $email);

$req->execute($val);
$row = $req->fetch(PDO::FETCH_ASSOC);

if ($row) {
    // Compte existe déjà
    $_SESSION['errorIns'] = "Compte déjà existant";
    header("Location: pages/landing.php");
    exit();
} else {
    $req = $pdo->prepare("INSERT INTO `accounts` (`email`, `password`, `prenom`, `nom`) VALUES (:email, :pass, :prenom, :nom)");
    $val = array(":email" => $email, ":pass" => $password, ":prenom" => $prenom, ":nom" => $nom);
    $req->execute($val);

    unset($_SESSION['errorLog']);
    unset($_SESSION['errorIns']);
    $_SESSION['success'] = "Inscription réussie!";
    header("Location: pages/landing.php");
    exit();

}