<?php 

session_start();

require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        
        if (!$email || empty($password)) {
            throw new Exception("Tous les champs sont obligatoires.");
        }

        $stmt = $pdo->prepare("SELECT id, first_name, password FROM User WHERE email = :email");
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user["password"])) {
            throw new Exception("Email ou mot de passe incorrect.");
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['first_name'] = $user['first_name'];

        // Redirection vers la page d'accueil
        header("Location: accueil.php");
        exit;
    } catch (Exception $e) {
        // Utiliser une variable de session pour afficher le message d'erreur sur la page de connexion
        $_SESSION['error'] = "Erreur : " . $e->getMessage();
        header("Location: login.php");
        exit;
    }
}
?>