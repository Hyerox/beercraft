<?php
// Connexion à la base de données
$host = 'mysql';
$dbname = 'mydb';
$username = 'root';
$password = 'root';


try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
        ]
    );
} catch (PDOException $e) {
    error_log("Erreur de connexion à la base de données: " . $e->getMessage());
    die("Erreur de connexion à la base de données");
}

// Récupération des données
try {
    $stmt = $pdo->prepare("SELECT id, first_name, last_name, email, role FROM User");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erreur SQL : " . $e->getMessage());
    $users = [];
}

try {
    $stmt = $pdo->prepare("SELECT * FROM Beer");
    $stmt->execute();
    $beers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erreur SQL : " . $e->getMessage());
    $beers = [];
}
