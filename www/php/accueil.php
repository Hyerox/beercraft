<?php
session_start();
require_once __DIR__ . "/db.php";

// Configuration de la pagination
$beers_per_page = 12;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$selected_category = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$selected_origin = isset($_GET['origin']) ? $_GET['origin'] : '';

try {
    // Récupération des origines uniques
    $stmt = $pdo->query("SELECT DISTINCT origin FROM Beer WHERE origin IS NOT NULL ORDER BY origin");
    $origins = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Récupération des catégories
    $stmt = $pdo->query("SELECT * FROM Category ORDER BY name");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Compter le nombre total de bières avec filtres
    $countQuery = "SELECT COUNT(DISTINCT b.id) as total FROM Beer b";
    $whereConditions = [];
    $params = [];

    if ($selected_category > 0) {
        $countQuery .= " JOIN Beer_Category bc ON b.id = bc.beer_id";
        $whereConditions[] = "bc.category_id = :category";
        $params[':category'] = $selected_category;
    }

    if ($selected_origin) {
        $whereConditions[] = "b.origin = :origin";
        $params[':origin'] = $selected_origin;
    }

    if (!empty($whereConditions)) {
        $countQuery .= " WHERE " . implode(' AND ', $whereConditions);
    }

    $stmt = $pdo->prepare($countQuery);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_beers = (int)$result['total'];
    $total_pages = ceil($total_beers / $beers_per_page);

    // Vérifier que la page courante est valide
    if ($current_page < 1) $current_page = 1;
    if ($current_page > $total_pages) $current_page = $total_pages;

    // Calculer l'offset
    $offset = ($current_page - 1) * $beers_per_page;

    // Récupération des bières avec filtres
    $query = "SELECT DISTINCT b.* FROM Beer b";
    if ($selected_category > 0) {
        $query .= " JOIN Beer_Category bc ON b.id = bc.beer_id";
    }

    if (!empty($whereConditions)) {
        $query .= " WHERE " . implode(' AND ', $whereConditions);
    }

    $query .= " ORDER BY b.origin, b.created_at DESC LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($query);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':limit', $beers_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $beers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    error_log("Pagination: Total={$total_beers}, Pages={$total_pages}, Current={$current_page}, Offset={$offset}");
} catch (PDOException $e) {
    error_log($e->getMessage());
    $beers = [];
    $total_pages = 1;
    $total_beers = 0;
}

include_once "./includes/header.php";
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
            if (!string) return '-';
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        function partager() {
            if (navigator.share) {
                navigator.share({
                    title: document.title,
                    text: "Découvrez cette page !",
                    url: window.location.href
                }).then(() => {
                    console.log('Partage réussi');
                }).catch((error) => {
                    console.error('Erreur de partage :', error);
                });
            } else {
                alert("Le partage n'est pas pris en charge sur ce navigateur.");
            }
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
                                            Origine: <span class="font-medium"><?= ucfirst(htmlspecialchars($beer['origin'])) ?></span>
                                        </p>
                                        <p class="text-gray-700 text-sm line-clamp-2 mb-2">
                                            <?= nl2br(htmlspecialchars($beer['description'])) ?>
                                        </p>
                                        <a href="./info_beer_comment.php?id=<?= htmlspecialchars($beer['id']) ?>" class="bg-amber-500 text-white px-4 py-2 rounded-lg hover:bg-amber-600 transition-colors cursor-pointer">
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

    <!-- Ajouter les filtres avant la grille des bières -->
    <div class="container mx-auto p-8">
        <div class="mb-6 bg-black/30 backdrop-blur-sm rounded-xl">
            <form action="" method="GET" class="flex items-center justify-center gap-4 p-6">
                <div class="flex items-center gap-4">
                    <label for="origin" class="text-white">Type :</label>
                    <select name="origin" id="origin" class="rounded-lg px-4 py-2 bg-amber-500/80 text-white">
                        <option value="">Toutes les origines</option>
                        <?php foreach ($origins as $origin): ?>
                            <option value="<?= htmlspecialchars($origin) ?>"
                                <?= $selected_origin === $origin ? 'selected' : '' ?>>
                                <?= ucfirst(htmlspecialchars($origin)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

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
                                Origine: <span class="font-medium"><?= ucfirst(htmlspecialchars($beer['origin'])) ?></span>
                            </p>
                            <!-- DESCRIPTION -->
                            <p class="text-gray-700 text-sm h-20 line-clamp-4">
                                <?= nl2br(htmlspecialchars($beer['description'])) ?>
                            </p>

                            <!-- Action Buttons -->
                            <div class="mt-4 flex justify-between items-center">
                                <a href="./info_beer_comment.php?id=<?= htmlspecialchars($beer['id']) ?>" class="bg-amber-500 text-white px-4 py-2 rounded-lg hover:bg-amber-600 transition-colors">
                                    Voir détails
                                </a>
                                <div class="flex items-center gap-2">
                                    <button class="text-gray-600 hover:text-amber-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </button>
                                    <button class="text-gray-600 hover:text-amber-500" onclick="partager()">
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

        <!-- Pagination améliorée -->
        <?php if ($total_pages > 1): ?>
            <div class="mt-12 bg-black/30 backdrop-blur-sm rounded-xl p-6">
                <div class="flex flex-col items-center gap-4">
                    <h3 class="text-white text-xl">Navigation</h3>
                    <div class="flex flex-wrap justify-center items-center gap-2">
                        <!-- Première page -->
                        <?php if ($current_page > 1): ?>
                            <a href="?page=1" class="px-4 py-2 bg-amber-500/80 text-white rounded-lg hover:bg-amber-600">
                                Première
                            </a>
                        <?php endif; ?>

                        <!-- Pages numérotées -->
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?page=<?= $i ?>"
                                class="px-4 py-2 <?= $i === $current_page ? 'bg-amber-600' : 'bg-amber-500/80 hover:bg-amber-600' ?> text-white rounded-lg">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <!-- Dernière page -->
                        <?php if ($current_page < $total_pages): ?>
                            <a href="?page=<?= $total_pages ?>" class="px-4 py-2 bg-amber-500/80 text-white rounded-lg hover:bg-amber-600">
                                Dernière
                            </a>
                        <?php endif; ?>
                    </div>
                    <p class="text-white text-sm">
                        Page <?= $current_page ?> sur <?= $total_pages ?> (<?= $total_beers ?> bières)
                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>