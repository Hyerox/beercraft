<?php
session_start();
require_once __DIR__ . "/db.php";
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Accueil</title>
    <script>
        function sendMail() {
            const subject = encodeURIComponent("Ajout d'une nouvelle bière");
            const body = encodeURIComponent(`Bonjour,

Je souhaite ajouter une nouvelle bière :

Nom de la bière :
Origine :
Degré d'alcool :
Prix moyen :
Description :
URL de l'image :

Cordialement,`);

            const mailtoLink = `mailto:beercraft@outlook.com?subject=${subject}&body=${body}`;
            window.open(mailtoLink, '_blank');
        }

        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
    </script>
    <style>
        .slider-container {
            overflow: hidden;
            position: relative;
            width: 100%;
        }

        .slider {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .slide {
            flex: 0 0 100%;
            width: 100%;
        }
    </style>
</head>

<body style="background-image: url('../../images/beer_bg.jpg');" class="bg-cover bg-center h-screen bg-fixed">

    <div class="w-full flex justify-between items-center px-8">
        <img src="../../images/Logo-beercraft-removebg-preview.png" class="w-32 h-32 object-center brightness-50 saturate-200">

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
                <li class="bg-gray-700 list-none py-2 px-4 rounded-xl hover:bg-gray-800 active:bg-black"><a href="./login.php">Se connecter</a></li>
                <li class="bg-gray-700 list-none py-2 px-4 rounded-xl hover:bg-gray-800 active:bg-black"><a href="./signup.php">S'inscrire</a></li>
            </nav>
        <?php else: ?>
            <nav class="flex gap-2">
                <li class="bg-gray-700 list-none py-2 px-4 rounded-xl hover:bg-gray-800 active:bg-black"><a href="./add_beer.php">Ajoutez des bières</a></li>
                <li class="bg-gray-700 list-none py-2 px-4 rounded-xl hover:bg-gray-800 active:bg-black"><a href="./logout.php">Se déconnecter</a></li>
            </nav>
        <?php endif; ?>
    </div>

    <div class="flex justify-around">
        <div class="text-white p-4 bg-black bg-opacity-40 rounded-lg w-1/4">
            <article class="flex flex-col items-center text-white">
                <h1 class="text-xl">Bienvenue sur le site Beercraft</h1>
                <p class="text-center ">Beercraft est un site crée par les passionnés, pour les passionnés. Vous y retrouverez une multitudes de bières aussi uniques les unes que les autres. Vous pourrez discuter de bières avec d'autres internautes, laisser votre opinion sur celles qui vous ont marqués, et même la partager sur vos réseaux !</p>
                <hr class="w-2/3 h-px bg-gradient-to-r from-transparent via-black to-transparent my-2 mx-auto border-0">

                <h2>Le fonctionnement de beercraft</h2>
                <ul class="px-2">
                    <li>• Connectez vous pour pouvoir commenter une bière dans la partie "Voir détails" </li>
                    <li>• Si vous voulez ajouter une bière, <button onclick="sendMail()" class="text-blue-300 hover:text-blue-400 underline">envoyez-nous un mail</button> avec toutes les informations nécessaires</li>
                </ul>
            </article>

        </div>
        <div class="text-white p-4 bg-black bg-opacity-40 rounded-lg w-1/4">
            <h2 class="text-xl font-semibold mb-2">Découvrez nos nouveautés !</h2>
            <p class="mb-3">Explorez les nouvelles saveurs sélectionnées spécialement par notre équipe de passionnés.</p>
            <div class="flex justify-end"><a href="#nouveautes" class="inline-block bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded flex">Voir les nouveautés</a></div>
            <div class="flex flex-col items-center">
                <h2 class=" text-xl font-semibold mb-2">Découvrez les bières favorites des utilisateurs</h2>

                <div class="slider-container w-96">
                    <div class="slider">
                        <?php
                        $favoriteBeers = array_slice($beers, 0, 5); // Prend les 5 premières bières pour l'exemple
                        foreach ($favoriteBeers as $beer):
                        ?>
                            <div class="slide">
                                <div class="bg-white text-black rounded-lg overflow-hidden shadow-lg">
                                    <div class="w-full h-48 overflow-hidden bg-black">
                                        <img src="<?= htmlspecialchars($beer['image']) ?>"
                                            alt="<?= htmlspecialchars($beer['name']) ?>"
                                            class="w-full h-full object-contain">
                                    </div>
                                    <div class="p-4">
                                        <div class="flex justify-between items-center mb-2">
                                            <h4 class="text-xl font-bold"><?= ucfirst(htmlspecialchars($beer['name'])) ?></h4>
                                            <div class="flex gap-2">
                                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm">
                                                    <?= htmlspecialchars($beer['average_price']) ?>€
                                                </span>
                                                <span class="bg-amber-100 text-amber-800 px-2 py-1 rounded-full text-sm">
                                                    <?= htmlspecialchars($beer['alcohol']) ?>%
                                                </span>
                                            </div>
                                        </div>
                                        <p class="text-gray-600 text-sm mb-2">
                                            Origine: <span class="font-medium"><?= htmlspecialchars($beer['origin']) ?></span>
                                        </p>
                                        <p class="text-gray-700 text-sm line-clamp-2 mb-2">
                                            <?= nl2br(htmlspecialchars($beer['description'])) ?>
                                        </p>
                                        <a href="./info_beer.php?id=<?= htmlspecialchars($beer['id']) ?>" class="bg-amber-500 text-white px-4 py-2 rounded-lg hover:bg-amber-600 transition-colors cursor-pointer">
                                            Voir détails
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <script>
                const slider = document.querySelector('.slider');
                const slides = document.querySelectorAll('.slide');
                let currentSlide = 0;

                function nextSlide() {
                    currentSlide = (currentSlide + 1) % slides.length;
                    slider.style.transform = `translateX(-${currentSlide * 100}%)`;
                }

                // Change de slide toutes les 3 secondes
                setInterval(nextSlide, 3000);
            </script>
        </div>
    </div>

    <?php
    try {
        $stmt = $pdo->query("SELECT * FROM Beer ORDER BY created_at DESC");
        $beers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erreur de lecture : " . $e->getMessage();
    }
    ?>

    <!-- Grille des bières -->
    <div class=" container mx-auto p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($beers as $beer): ?>
                <div class="bg-stone-500/80 rounded-xl p-4">
                    <!-- Card Preview -->
                    <div class="bg-white text-black rounded-lg overflow-hidden shadow-lg">
                        <!-- Image Container -->
                        <div class="w-full h-48 overflow-hidden bg-black">
                            <img src="<?= htmlspecialchars($beer['image']) ?>"
                                alt="<?= htmlspecialchars($beer['name']) ?>"
                                class="w-full h-full object-contain">
                        </div>

                        <!-- Content Container -->
                        <div class="p-4">
                            <div class="flex justify-between items-center mb-2">
                                <!-- NAME -->
                                <h4 class="text-xl font-bold"><?= ucfirst(htmlspecialchars($beer['name'])) ?></h4>
                                <div class="flex gap-2">
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm">
                                        <?= htmlspecialchars($beer['average_price']) ?>€
                                    </span>
                                    <span class="bg-amber-100 text-amber-800 px-2 py-1 rounded-full text-sm">
                                        <?= htmlspecialchars($beer['alcohol']) ?>%
                                    </span>
                                </div>
                            </div>
                            <!-- ORIGINE -->
                            <p class="text-gray-600 text-sm mb-2">
                                Origine: <span class="font-medium"><?= htmlspecialchars($beer['origin']) ?></span>
                            </p>
                            <!-- DESCRIPTION -->
                            <p class="text-gray-700 text-sm h-20 line-clamp-4">
                                <?= nl2br(htmlspecialchars($beer['description'])) ?>
                            </p>

                            <!-- Action Buttons -->
                            <div class="mt-4 flex justify-between items-center">
                                <a href="./info_beer.php?id=<?= htmlspecialchars($beer['id']) ?>" class="bg-amber-500 text-white px-4 py-2 rounded-lg hover:bg-amber-600 transition-colors">
                                    Voir détails
                                </a>
                                <div class="flex items-center gap-2">
                                    <button class="text-gray-600 hover:text-amber-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </button>
                                    <button class="text-gray-600 hover:text-amber-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>