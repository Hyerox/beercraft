<?php
session_start();
require_once "db.php";

mb_internal_encoding('UTF-8');
header('Content-Type: text/html; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Nettoyage et encodage des données en UTF-8
        $first_name = mb_convert_encoding(trim($_POST['first_name']), 'UTF-8', mb_detect_encoding($_POST['first_name']));
        $last_name = mb_convert_encoding(trim($_POST['last_name']), 'UTF-8', mb_detect_encoding($_POST['last_name']));
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (!$first_name || !$last_name || !$email || empty($password)) {
            throw new Exception("Tous les champs sont obligatoires.");
        }

        // Vérifier si l'email existe déjà
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM User WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception("Cet email est déjà utilisé.");
        }

        // Définir le rôle en fonction de l'email
        $role = $email === 'admin@admin.com' ? 'admin' : 'member';

        // Hachage du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insérer l'utilisateur
        $stmt = $pdo->prepare("INSERT INTO User (first_name, last_name, email, password, role) 
                               VALUES (:first_name, :last_name, :email, :password, :role)");
        $stmt->execute([
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':role' => $role
        ]);

        // Redirection vers la page d'accueil
        header("Location: accueil.php");
        exit;
    } catch (Exception $e) {
        // Utiliser une variable de session pour afficher le message d'erreur sur la page d'inscription
        $_SESSION['error'] = "Erreur : " . mb_convert_encoding($e->getMessage(), 'UTF-8');
        header("Location: signup.php");
        exit;
    }
}
