<?php
session_start();
require_once "db.php";
include "./tools/tools.php";

// Vérification du rôle uniquement si l'utilisateur est connecté
if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'member') {
  $_SESSION['error'] = "Vous n'avez pas les droits pour ajouter une bière";
  header('Location: accueil.php');
  exit;
}

include_once "./includes/header.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
  <title>Ajout de bière</title>

</head>

<body style="background-image: url('../images/beer_bg.jpg');" class="bg-cover bg-center min-h-screen bg-fixed">
  <div class="min-h-[calc(100vh-7rem)] flex items-center justify-center w-full py-10" style="background: linear-gradient(to bottom, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.5) 100%);">
    <div class="container mx-auto px-4 flex flex-col lg:flex-row justify-around items-start gap-8">
      <!-- Formulaire d'ajout -->
      <div class="w-full lg:w-1/2">
        <div class="bg-black/30 backdrop-blur-sm text-white rounded-xl p-8">
          <h3 class="text-3xl font-bold mb-8 text-center">Ajouter une bière</h3>
          <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="bg-red-500/80 text-white p-4 rounded-lg mb-6">
              <p class="text-center">Vous devez être connecté pour ajouter une bière.</p>
              <div class="flex justify-center mt-4">
                <a href="login.php" class="bg-white text-red-500 px-6 py-2 rounded-lg hover:bg-gray-100">Se connecter</a>
              </div>
            </div>
          <?php else: ?>
            <form action="add_beer_traitement.php" method="post" class="space-y-6">
              <!-- Image URL -->
              <div class="space-y-2">
                <label for="image" class="block text-lg">URL de l'image</label>
                <input type="text" id="image" name="image" class="w-full bg-white/10 border border-white/20 text-white rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-amber-500" placeholder="https://..." oninput="updatePreview()">
              </div>

              <!-- Nom de la bière -->
              <div class="space-y-2">
                <label for="beer_name" class="block text-lg">Nom de la bière</label>
                <input type="text" id="beer_name" name="beer_name" required class="w-full bg-white/10 border border-white/20 text-white rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-amber-500" oninput="updatePreview()">
              </div>

              <!-- Origine -->
              <div class="space-y-2">
                <label for="origin" class="block text-lg">Origine</label>
                <input type="text" id="origin" name="origin" required class="w-full bg-white/10 border border-white/20 text-white rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-amber-500" oninput="updatePreview()">
              </div>

              <div class="flex gap-4">
                <!-- Alcool -->
                <div class="space-y-2 w-1/2">
                  <label for="alcohol" class="block text-lg">% Alcool</label>
                  <input type="number" step=".1" min="0" max="67" id="alcohol" name="alcohol" required class="w-full bg-white/10 border border-white/20 text-white rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-amber-500" oninput="updatePreview()">
                </div>

                <!-- Prix -->
                <div class="space-y-2 w-1/2">
                  <label for="price" class="block text-lg">Prix</label>
                  <input type="number" step=".01" min="0" id="price" name="price" required class="w-full bg-white/10 border border-white/20 text-white rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-amber-500" oninput="updatePreview()">
                </div>
              </div>

              <!-- Description -->
              <div class="space-y-2">
                <label for="description" class="block text-lg">Description</label>
                <textarea id="description" name="description" rows="5" required class="w-full bg-white/10 border border-white/20 text-white rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-amber-500" placeholder="Décrivez cette bière..." oninput="updatePreview()"></textarea>
              </div>

              <div class="flex justify-center pt-4">
                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-8 py-3 rounded-lg transition-all duration-300 shadow-lg hover:shadow-amber-500/50">
                  Ajouter la bière
                </button>
              </div>
            </form>
          <?php endif; ?>
        </div>
      </div>

      <!-- Prévisualisation -->
      <div class="w-full lg:w-1/2">
        <div class="bg-black/30 backdrop-blur-sm rounded-xl p-8">
          <h3 class="text-2xl font-bold mb-6 text-white text-center">Prévisualisation</h3>
          <div class="bg-white rounded-lg overflow-hidden shadow-xl">
            <!-- Image Container -->
            <div class="w-full h-64 bg-gray-900 flex items-center justify-center overflow-hidden">
              <img id="preview_image" src="" alt="Prévisualisation" class="w-full h-full object-contain">
            </div>

            <!-- Content Container -->
            <div class="p-6">
              <div class="flex justify-between items-center mb-2">
                <!-- NAME -->
                <h4 id="preview_name" class="text-xl font-bold">-</h4>
                <div class="flex gap-2">
                  <span id="preview_price" class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm">-€</span>
                  <span id="preview_alcohol" class="bg-amber-100 text-amber-800 px-2 py-1 rounded-full text-sm">-%</span>
                </div>
              </div>
              <!-- ORIGINE -->
              <p class="text-gray-600 text-sm mb-2">
                Origine: <span id="preview_origin" class="font-medium">-</span>
              </p>
              <!-- DESCRIPTION -->
              <p id="preview_description" class="text-gray-700 text-sm h-20 line-clamp-4">-</p>

              <!-- Action Buttons -->
              <div class="mt-4 flex justify-between items-center">
                <button class="bg-amber-500 text-white px-4 py-2 rounded-lg hover:bg-amber-600 transition-colors">
                  Voir détails
                </button>
                <div class="flex items-center gap-2">
                  <button class="text-gray-600 hover:text-amber-500" onclick="partager()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php
  include_once "./includes/footer.php";
  ?>
</body>

</html>