<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

</head>

<body>
    <?php include 'connexion.php'; ?>
    <?php include 'inscription.php'; ?>
    <script src="../js/landing.js"></script>
    <?php
    if ($_SESSION['errorIns']) {
        // Swap back a inscription
        echo "<script>toggleHidden(inscriptionForm); toggleHidden(connexionForm);</script>";
        unset($_SESSION['errorIns']);
    }
    ?>
</body>