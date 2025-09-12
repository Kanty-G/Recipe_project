<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// On autorise une seule session par compte.

class Session_handler
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function create_session($id, $session_id)
    {
        // Création d'une entrée session dans la DB, on overwrite l'ancienne si déjà existante.
        $req = $this->pdo->prepare("INSERT INTO `sessions` (`accounts_id`, `session_id`, `last_use`) VALUES (:accounts_id, :session_id, :last_use) ON DUPLICATE KEY UPDATE
        `last_use` = VALUES(`last_use`), `session_id` = VALUES(`session_id`)");
        $val = array(":accounts_id" => $id, ":session_id" => $session_id, ":last_use" => date('Y-m-d H:i:s'));

        $req->execute($val);

    }
    public function delete_session($session_id)
    {
        // Supression de la session lors d'une déconnexion
        $req = $this->pdo->prepare("DELETE FROM `sessions` WHERE `accounts_id` = :session_id");
        $val = array(":session_id" => $session_id);

        $req->execute($val);
    }


    // -1 - Session Expirée
    // 0 - Session Inexistante
    // 1 - Session Valide

    function verify($id, $session_id)
    {
        // On vérifie si la session courante correspond à celle dans notre DB
        $req = $this->pdo->prepare("SELECT * FROM `sessions` WHERE accounts_id = :id");
        $val = array(":id" => $id);

        $req->execute($val);
        $row = $req->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $last_use_p60 = new DateTime($row['last_use']);
            $last_use_p60->add(new DateInterval('PT60M'));

            // Plus de 1h après la dernière utilisation
            if ($last_use_p60 < new DateTime()) {
                return -1;
            } else if ($row['session_id'] != $session_id) {
                // Pas la même session
                return 0;
            } else {
                return 1;
            }

        } else {
            return 0;
        }
    }
}



