<?php
// Connexion à la base de données
$host = getenv('DB_HOST') ?: 'mysql';
$port = getenv('DB_PORT') ?: '3306';
$dbname = getenv('DB_NAME') ?: 'mydb';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: 'root';

try {
    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $dbname);

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];

    if (defined('PDO::MYSQL_ATTR_INIT_COMMAND')) {
        $options[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci';
    }

    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    error_log('Erreur de connexion à la base de données: ' . $e->getMessage());
    die('Erreur de connexion à la base de données');
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
