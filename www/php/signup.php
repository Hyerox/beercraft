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

// READ ////////////////////////////////
try {
    $stmt = $pdo->prepare("SELECT id, first_name, last_name, email, role FROM User");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $users = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/signup.php">
    <title>Gestion des utilisateurs</title>
</head>
<body>

<h2>Inscription</h2>

<?php
if (isset($_SESSION['error'])) {
    echo "<p style='color:red'>" . $_SESSION['error'] . "</p>";
    unset($_SESSION['error']);
}
?>

<form method="post" action="signup_traitement.php">
    <label for="first_name">Prénom:</label>
    <input type="text" id="first_name" name="first_name" required><br>

    <label for="last_name">Nom:</label>
    <input type="text" id="last_name" name="last_name" required><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br>

    <label for="password">Mot de passe:</label>
    <input type="password" id="password" name="password" required><br>

    <label for="role">Rôle:</label>
    <select id="role" name="role">
        <option value="member">Membre</option>
        <option value="admin">Admin</option>
    </select><br>

    <input type="submit" value="S'inscrire">
</form>
</body>
</html>


