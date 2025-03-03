<?php
session_start();

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $last_name  = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        $role = in_array($_POST['role'], ['member', 'admin']) ? $_POST['role'] : 'member';

        if (!$first_name || !$last_name || !$email || empty($password)) {
            throw new Exception("Tous les champs sont obligatoires.");
        }

        // Vérifier si l'email existe déjà
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM User WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception("Cet email est déjà utilisé.");
        }

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
        $_SESSION['error'] = "Erreur : " . $e->getMessage();
        header("Location: signup.php");
        exit;
    }
}
?>
