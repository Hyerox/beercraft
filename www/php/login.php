<?php

session_start();

require_once "db.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Login</title>
</head>

<body style="background-image: url('../images/beer_bg.jpg');" class="h-screen bg-cover bg-center bg-gray-200 flex justify-center items-center flex-col">
    <h2 class="flex justify-center text-3xl mt-20">Connexion</h2>
    <?php
    if (isset($_SESSION['error'])) {
        echo "<p class='flex justify-center text-red-500 mt-6'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    }
    ?>
    <form action="login_traitement.php" method="post" class="flex flex-col mt-12 items-center h-screen">
        <label for="email">Email:</label>
        <input type="text" name="email" required class="border border-black w-64"><br>
        <label for="password">Mot de passe:</label>
        <input type="password" name="password" required class="border border-black w-64"><br>
        <input type="submit" value="Se connecter" class="bg-gray-300 px-6 py-2 rounded hover:bg-gray-700 hover:text-white active:bg-gray-900">
    </form>
</body>

</html>