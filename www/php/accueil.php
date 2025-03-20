<?php
session_start();
require_once __DIR__ . "/db.php";
include "./tools/tools.php";

// On récupère le filtre et on le trim. Sinon on met une chaine vide "".
$selected_origin = isset($_GET['origin']) ? trim($_GET['origin']) : '';

try {
    // Récupération des origines uniques pour le filtre avec "DISTINCT" et on range par ordre alphabétique
    $stmt = $pdo->query("SELECT DISTINCT origin FROM Beer WHERE origin IS NOT NULL AND origin != '' ORDER BY origin");
    $origins = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Récupération des bières avec filtrage
    $query = "SELECT * FROM Beer";
    $params = [];

    if (!empty($selected_origin)) {
        $query .= " WHERE origin = :origin";
        $params[':origin'] = $selected_origin;
    }

    $query .= " ORDER BY created_at DESC";

    $stmt = $pdo->prepare($query);
    if (!empty($selected_origin)) {
        $stmt->bindParam(':origin', $selected_origin, PDO::PARAM_STR);
    }
    $stmt->execute();
    $beers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $total_beers = count($beers);
} catch (PDOException $e) {
    error_log($e->getMessage());
    $beers = [];
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
        <div class="text-white p-6 bg-black/50 backdrop-blur-sm rounded-xl w-1/4">
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
                                <button onclick="sendMail(this)" class="text-amber-300 hover:text-amber-200 underline transition-colors font-medium">
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

        <section class="text-white p-6 bg-black/50 backdrop-blur-sm rounded-xl w-1/4">
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
                                            <?= ucfirst(htmlspecialchars($beer['description'])) ?>
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

    </div>
    <?php include_once "./includes/footer.php"; ?>
</body>

</html>