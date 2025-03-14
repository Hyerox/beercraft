<?php
session_start();
require_once __DIR__ . "/db.php";
include "./tools/tools.php";

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
        <div class="text-white p-6 bg-black/30 backdrop-blur-sm rounded-xl w-1/4">
            <section class="flex flex-col gap-4">
                <!-- En-tête de l'article -->
                <div class="space-y-4 text-center">
                    <h1 class="text-2xl font-bold text-white mb-4">
                        Bienvenue sur le site
                        <span class="bg-gradient-to-r from-amber-200 to-amber-400 bg-clip-text text-transparent">
                            Beercraft
                        </span>
                    </h1>
                    <p class="text-white leading-relaxed">
                        Beercraft est un site créé par les passionnés, pour les passionnés.
                        <br><br>
                        Vous y retrouverez une multitude de bières aussi uniques les unes que les autres, et pourrez interagir avec une communauté passionnée.
                    </p>
                </div>

                <hr class="border-t border-white/20 my-4">

                <!-- Section fonctionnement -->
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-center mb-4">
                        <span class="bg-gradient-to-r from-amber-200 to-amber-400 bg-clip-text text-transparent">
                            Le fonctionnement de Beercraft
                        </span>
                    </h2>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3 group">
                            <div class="mt-1 p-1 rounded-full bg-amber-500/20 group-hover:bg-amber-500/30 transition-colors">
                                <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-white leading-relaxed">
                                Connectez-vous pour pouvoir commenter une bière dans la partie "Voir détails"
                            </p>
                        </li>
                        <li class="flex items-start gap-3 group">
                            <div class="mt-1 p-1 rounded-full bg-amber-500/20 group-hover:bg-amber-500/30 transition-colors">
                                <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="text-white leading-relaxed">
                                Vous souhaitez ajouter une bière ?
                                <button onclick="sendMail()" class="text-amber-300 hover:text-amber-200 underline transition-colors font-medium">
                                    Contactez-nous par mail
                                </button>
                            </p>

                        </li>
                        <li class="flex items-start gap-3 group">
                            <div class="mt-1 p-1 rounded-full bg-amber-500/20 group-hover:bg-amber-500/30 transition-colors">
                                <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                </svg>
                            </div>
                            <p class="text-white leading-relaxed">
                                Partagez vos bières préférées sur vos réseaux !
                            </p>
                        </li>
                    </ul>
                </div>
            </section>
        </div>

        <section class="text-white p-6 bg-black/30 backdrop-blur-sm rounded-xl w-1/4">
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
        </section>

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
        </section>
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
            <?php foreach ($beers as $beer):
                include "./includes/card.php" ?>
            <?php endforeach; ?>
        </div>

        <h2>Vos bières favorites</h2>
        <!-- Pagination améliorée -->
        <?php if ($total_pages > 1): ?>
            <article class="mt-12 bg-black/30 backdrop-blur-sm rounded-xl p-6">
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
            </article>
        <?php endif; ?>
    </div>
    <?php include_once "./includes/footer.php"; ?>
</body>

</html>