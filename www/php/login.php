<?php

session_start();

require_once "db.php";

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Connexion - BeerCraft</title>
</head>

<body style="background-image: url('../images/beer_bg.jpg');" class="bg-cover bg-center bg-fixed min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md p-8 bg-black/50 backdrop-blur-sm rounded-xl shadow-2xl">
        <h2 class="text-3xl font-bold text-center mb-8 bg-gradient-to-r from-amber-200 to-amber-400 bg-clip-text text-transparent">
            Connexion à BeerCraft
        </h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-500/80 text-white p-4 rounded-lg mb-6 text-center">
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="login_traitement.php" method="post" class="space-y-6" accept-charset="UTF-8">
            <div class="space-y-2">
                <label for="email" class="block text-white">Email</label>
                <input type="email" id="email" name="email" required
                    class="w-full px-4 py-2 bg-white/10 border border-white/20 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>

            <div class="space-y-2">
                <label for="password" class="block text-white">Mot de passe</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-2 bg-white/10 border border-white/20 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white py-3 rounded-lg transition-all duration-300 shadow-lg hover:shadow-amber-500/50">
                    Se connecter
                </button>
            </div>

            <p class="text-center text-white/80 mt-4">
                Pas encore membre ?
                <a href="signup.php" class="text-amber-400 hover:text-amber-300 underline">Inscrivez-vous</a>
            </p>
        </form>
    </div>
</body>

</html>