<?php

class Recette
{
    private $pdo;
    private $table = 'recettes';
    public $id;
    public $nom;
    public $ingredients;
    public $description;
    public $categorie;
    public $image;
    public $date_creation;
    public $nombre;
    public $owner_id;

    public $admin_id = 1;

    function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    function read($page)
    {
        // Lecture de toutes les recettes
        $req = "SELECT * FROM `" . $this->table . "` ORDER BY date_creation ASC LIMIT 15 OFFSET " . $page * 15;
        $res = $this->pdo->prepare($req);

        $res->execute();

        return $res;
    }

    function recherche($filtres)
    {

        if ($filtres['type'] == 0) {
            // Recherche globale

            $req = "SELECT * FROM `" . $this->table . "` WHERE `nom` LIKE '%" . $filtres['keyword'] . "%' AND (" . $filtres['categorie'] . " = 0 OR `categorie` = " . $filtres['categorie'] . ") ORDER BY date_creation ASC LIMIT 15 OFFSET " . $filtres['page'] * 15;
            $res = $this->pdo->prepare($req);
            $res->execute();


            $req_count = "SELECT * FROM `" . $this->table . "` WHERE `nom` LIKE '%" . $filtres['keyword'] . "%' AND (" . $filtres['categorie'] . " = 0 OR `categorie` = " . $filtres['categorie'] . ")";
            $res_count = $this->pdo->prepare($req_count);

            $res_count->execute();
            $this->nombre = $res_count->rowCount();

            return $res;
        } else {
            // Recherche user
            return $this->search_user($filtres);
        }


    }

    function search_user($filtres)
    {
        // Lecture d'une page de recettes associées à un user
        // MAX par page = 15
        $req = "SELECT * FROM `" . $this->table . "` WHERE `owner_id` = :accounts_id AND `nom` like '%" . $filtres['keyword'] . "%'  AND (" . $filtres['categorie'] . " = 0 OR `categorie` = " . $filtres['categorie'] . ") ORDER BY date_creation ASC LIMIT 15 OFFSET " . $filtres['page'] * 15;
        $res = $this->pdo->prepare($req);
        $val = array(":accounts_id" => $filtres['id']);

        $res->execute($val);

        $req_count = "SELECT * FROM `" . $this->table . "` WHERE `owner_id` = :accounts_id AND `nom` like '%" . $filtres['keyword'] . "%'  AND (" . $filtres['categorie'] . " = 0 OR `categorie` = " . $filtres['categorie'] . ")";
        $res_count = $this->pdo->prepare($req_count);

        $res_count->execute($val);
        $this->nombre = $res_count->rowCount();

        return $res;
    }

    function read_one()
    {
        // Lecture d'une seule recette par id
        $req = "SELECT * FROM `" . $this->table . "` WHERE `id` = :id";
        $res = $this->pdo->prepare($req);
        $val = array(":id" => $this->id);

        $res->execute($val);

        if (!$res->rowCount()) {
            return;
        }

        $row = $res->fetch(PDO::FETCH_ASSOC);
        extract($row);
        $this->id = $id;
        $this->nom = $nom;
        $this->ingredients = $ingredients;
        $this->description = $description;
        $this->categorie = $categorie;
        $this->image = $image;
        $this->date_creation = $date_creation;
        $this->owner_id = $owner_id;
    }

    function read_total()
    {
        // Nombre total de recettes.
        $req = "SELECT COUNT(*) as nombre FROM " . $this->table;
        $res = $this->pdo->prepare($req);

        $res->execute();
        $count = $res->fetch(PDO::FETCH_ASSOC);
        return $count['nombre'];
    }

    function read_user_total()
    {
        // Nombre total de recettes associées à un compte.
        $req = "SELECT COUNT(*) as nombre FROM `" . $this->table . "`owner_id` = :accounts_id;";
        $res = $this->pdo->prepare($req);
        $val = array(":accounts_id" => $this->owner_id);

        $res->execute($val);
        $count = $res->fetch(PDO::FETCH_ASSOC);
        return $count['nombre'];
    }

    function del_one()
    {
        // Suppresion d'une recette de la liste d'un utilisateur.
        $req = "DELETE FROM " . $this->table . " WHERE id = :recette_id;";
        $res = $this->pdo->prepare($req);
        $val = array(":recette_id" => $this->id);
        $res->execute($val);


    }


    function ajout()
    {
        try {
            // Table de recettes
            $req = "INSERT INTO " . $this->table . " (`nom`, `image`, `ingredients`, `description`, `categorie`, `date_creation`, `owner_id`) VALUES (:nom, :image, :ingredients, :description, :categorie, :date_creation, :owner_id )";
            $res = $this->pdo->prepare($req);
            $val = array(':nom' => $this->nom, ':image' => $this->image, ':ingredients' => $this->ingredients, 'description' => $this->description, ':categorie' => $this->categorie, ':date_creation' => $this->date_creation, ':owner_id' => $this->owner_id);
            $res->execute($val);


            // Association du compte avec la nouvelle recette
            $req = "SELECT LAST_INSERT_ID() AS nombre";
            $res = $this->pdo->prepare($req);
            $res->execute();
            $id = $res->fetch(PDO::FETCH_ASSOC)['nombre'];


            return $id;
        } catch (Exception $e) {
            return false;
        }

    }

    function modify()
    {
        // Modification recette, on change seulement les champs qui ont été modifiés
        try {
            $req = "UPDATE " . $this->table . " SET `nom` = COALESCE(:new_nom, nom), 
            `ingredients` = COALESCE(:new_ingredients, ingredients), 
            `description` = COALESCE(:new_description, description), 
            `categorie` = COALESCE(:new_categorie, categorie),
            `image` = COALESCE(:new_image, image),
            `date_creation` = COALESCE(:new_date_creation, date_creation)
            WHERE `id` = :recette_id";
            $res = $this->pdo->prepare($req);
            $val = array(
                ':recette_id' => $this->id,
                ':new_nom' => $this->nom,
                ':new_ingredients' => $this->ingredients,
                ':new_description' => $this->description,
                ':new_categorie' => $this->categorie,
                ':new_image' => $this->image,
                ':new_date_creation' => $this->date_creation
            );
            $res->execute($val);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    function last_updated()
    {
        // Retourne l'heure exacte de la dernière modification de la table
        // Utile pour savoir si les utilisateurs ont présentement la version la plus récent affichée
        $req = "SELECT UPDATE_TIME FROM information_schema.tables WHERE TABLE_SCHEMA = 'projet2' AND TABLE_NAME = 'recettes'";
        $res = $this->pdo->prepare($req);
        $res->execute();

        return $res->fetch(PDO::FETCH_ASSOC)['UPDATE_TIME'];
    }
}