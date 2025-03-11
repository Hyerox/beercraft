<?php

session_start();
require_once "db.php";
include_once "./includes/header.php";

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
  <title>Ajout de bière</title>
  <script>
    function capitalizeFirstLetter(string) {
      if (!string) return '-';
      return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function updatePreview() {
      // Image preview
      let imageUrl = document.getElementById('image').value;
      let imagePreview = document.getElementById('preview_image');
      imagePreview.src = imageUrl || 'https://via.placeholder.com/400x300?text=Image+de+la+bière';
      imagePreview.style.display = 'block';

      // Text content preview
      document.getElementById('preview_name').innerText = capitalizeFirstLetter(document.getElementById('beer_name').value) || '-';
      document.getElementById('preview_origin').innerText = capitalizeFirstLetter(document.getElementById('origin').value) || '-';
      document.getElementById('preview_alcohol').innerText = (document.getElementById('alcohol').value || '-') + '%';
      document.getElementById('preview_description').innerText = capitalizeFirstLetter(document.getElementById('description').value) || '-';
      document.getElementById('preview_price').innerText = (document.getElementById('price').value || '-') + '€';
    }

    // Initialiser la prévisualisation au chargement
    window.onload = updatePreview;
  </script>
</head>

<body style="background-image: url('../images/beer_bg.jpg');" class="bg-cover bg-center min-h-screen bg-fixed">
  <div class="min-h-[calc(100vh-7rem)] flex items-center justify-center w-full py-10" style="background: linear-gradient(to bottom, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.5) 100%);">
    <div class="container mx-auto px-4 flex flex-col lg:flex-row justify-around items-start gap-8">
      <!-- Formulaire d'ajout -->
      <div class="w-full lg:w-1/2">
        <div class="bg-black/30 backdrop-blur-sm text-white rounded-xl p-8">
          <h3 class="text-3xl font-bold mb-8 text-center">Ajouter une bière</h3>
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
      </div>
    </div>
  </div>
</body>

</html>