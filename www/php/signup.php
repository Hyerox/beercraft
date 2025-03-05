<?php
session_start();

require_once "db.php";
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <title>Gestion des utilisateurs</title>
</head>

<body class="h-screen bg-gray-200 flex items-center justify-center flex-col">

    <h2 class="flex justify-center text-3xl mt-20">Inscription</h2>

    <?php
    if (isset($_SESSION['error'])) {
        echo "<p class='flex justify-center text-red-500 mt-6'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    }
    ?>

    <form method="post" action="signup_traitement.php" class="flex flex-col mt-12 items-center h-screen">
        <label for="first_name">Prénom:</label>
        <input type="text" id="first_name" name="first_name" required class="border border-black w-64 bg-white"><br>

        <label for="last_name">Nom:</label>
        <input type="text" id="last_name" name="last_name" required class="border border-black w-64 bg-white"><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required class="border border-black w-64 bg-white"><br>

        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password" required class="border border-black w-64 bg-white"><br>

        <label for="role">Rôle:</label>
        <select id="role" name="role" class="border border-black w-64 bg-white">
            <option value="member">Membre</option>
            <option value="admin">Admin</option>
        </select><br>

        <input type="submit" value="S'inscrire" class="bg-gray-300 px-6 py-2 rounded hover:bg-gray-700 hover:text-white active:bg-gray-900">
    </form>
</body>

</html>