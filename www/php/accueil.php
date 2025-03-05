<?php
session_start();
require_once "db.php";
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Accueil</title>
</head>

<body style="background-image: url('../images/beer_bg.jpg');" class="bg-cover bg-center h-screen flex flex-col items-center">

    <div class="w-full flex justify-between items-center px-8">
        <?php if (isset($_SESSION['user_id'])): ?>
            <h2 class="text-xl ml-2">
                Bienvenue <?php echo htmlspecialchars(ucfirst($_SESSION['first_name'])); ?>
            </h2>
        <?php else: ?>
            <div class="w-40"></div>
        <?php endif; ?>

        <div class="text-center">
            <h1 class="text-4xl">Beercraft</h1>
            <h2 class="italic text-2xl">Chaque bière a une histoire, partagez la vôtre !</h2>
        </div>

        <div class="w-40"></div>
    </div>

    <!-- Navigation en haut à droite -->
    <div class="absolute top-0 right-0 mt-4 mr-4 text-white">
        <?php if (!isset($_SESSION['user_id'])): ?>
            <nav class="flex gap-2">
                <li class="bg-gray-700 list-none py-2 px-4 rounded-xl hover:bg-gray-800 active:bg-black"><a href="login.php">Se connecter</a></li>
                <li class="bg-gray-700 list-none py-2 px-4 rounded-xl hover:bg-gray-800 active:bg-black"><a href="signup.php">S'inscrire</a></li>
            </nav>
        <?php else: ?>
            <nav class="flex gap-2">
                <li class="bg-gray-700 list-none py-2 px-4 rounded-xl hover:bg-gray-800 active:bg-black"><a href="add_beer.php">Ajoutez des bières</a></li>
                <li class="bg-gray-700 list-none py-2 px-4 rounded-xl hover:bg-gray-800 active:bg-black"><a href="logout.php">Se déconnecter</a></li>
            </nav>
        <?php endif; ?>
    </div>

</body>

</html>