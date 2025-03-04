<?php

// Connexion à la base de données
$host = 'mysql';  
$dbname = 'mydb';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion à la base de données.");
}

// Ca fetch les utilisateurs de la base de données //
try {
    $stmt = $pdo->prepare("SELECT id, first_name, last_name, email, role FROM User");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Erreur SQL : " . $e->getMessage();
    $users = [];
}
?>