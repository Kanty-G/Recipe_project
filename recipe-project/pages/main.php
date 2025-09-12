<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

// Vérification de la session
require ("../includes/session_handler.php");
require ("../includes/db.php");
$db = new DB();
$pdo = $db->getDb();

if (isset($_SESSION['id'])) {

    $session_handler = new Session_Handler($pdo);
    $session_status = $session_handler->verify($_SESSION['id'], session_id());

    switch ($session_status) {
        case -1:
            $_SESSION['errorLog'] = "Session Expirée";
            break;
        case 0:
            $_SESSION['errorLog'] = "Session Inexistante";
    }
    if ($_SESSION['errorLog']) {
        header("Location: landing.php");
        exit();
    }

    // Session valide

    // Update last use
    $session_handler->create_session($_SESSION['id'], session_id());

    //Récuperer infos de l'utilisateur
    $req = $pdo->prepare("SELECT prenom FROM accounts WHERE id = ?");
    $req ->execute([$_SESSION['id']]);
    $user = $req->fetch(PDO::FETCH_ASSOC);

    $prenom = $user['prenom'];

    // On récupère la page
    $page = 1;
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    }
} else {
    $_SESSION['errorLog'] = "Session Inexistante";
    header("Location: landing.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recettes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/main.css">
    <script>const user_id =
            <?php
            echo $_SESSION['id']
                ?>
    </script>
    <script src="../js/delete_recette.js" defer></script>
    <script src="../js/set_recettes.js" defer></script>
    <script src="../js/recherche.js" defer></script>
    <script src="../js/refresh_async.js" defer></script>

</head>

<body>

    <div class="list container-fluid">
        <?php
        echo "<strong>Bonjour, "  . htmlspecialchars($prenom) . "</strong>";
        ?>

        <div id="main" class="row">
            <div id="filtres" class="col-2">
                <form>

                    <fieldset>
                    <legend>Filtres</legend>
                        <label for="recherche" class="form-label">Rechercher:</label>
                        <input type="text" id="recherche" name="recherche" class="form-control">
                    </fieldset>
                    <br>
                    <fieldset>
                        <input type="checkbox" class="form-check-input" value="state" id="switch_recettes">
                        <label for="switch_recettes" class="form-check-label">Mes Recettes</label>
                    </fieldset>
                    <br>
                    <fieldset>
                        <select name="categorie" id="categorie_select" class="form-select">
                            <option value="0">Sélectionnez une catégorie</option>
                            <option value="1">Entrée</option>
                            <option value="2">Plat Principal</option>
                            <option value="3">Dessert</option>
                        </select>
                    </fieldset>
                </form>
            </div>
            <div id="recette_view" class="row justify-content-around col-10">

            </div>
        </div>

    </div>
    <div class="row">
        <form action="../logout.php" method="post" class="col-2">
            <button type="submit">Logout</button>
        </form>
        <div id="nav_pag" class="col-10">

        </div>


    </div>


    <script>
        <?php
        echo "document.addEventListener('DOMContentLoaded', function() { init_recette(" . $page . "); });";
        ?>
    </script>

</body>

</html>
