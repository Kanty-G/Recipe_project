<?php

require __DIR__ .DIRECTORY_SEPARATOR.'pwd.php';

// Connexion à la base de données
class DB {
    private $user = "root";
    private $host = "localhost";
    private $name = "projet2";
    
    private $pdo = NULL;
    function getDb() {
        global $db_password;
        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->name;
        try {
            $this->pdo = new PDO($dsn, $this->user, $db_password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die();
        }

        return $this->pdo;
    }
}







