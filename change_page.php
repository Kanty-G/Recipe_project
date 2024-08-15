<?

// Sélecteur de page pour les recettes. Si aucune page on retourne à main.
if(isset($_GET['page'])) {
    header("Location: pages/main.php?page=" . $_GET['page']);
    exit();
} else {
    header("Location: pages/main.php");
    exit();
}