<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

if (!isset($_GET['id'])) {
  header('Location: tab.php');
  exit;
}

$stmt = $pdo->prepare("SELECT * FROM Beer WHERE id = ?");
$stmt->execute([$_GET['id']]);
$beer = $stmt->fetch();

if (!$beer) {
  header('Location: tab.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
  <title>Modifier la bière</title>
  <script>
    function updatePreview() {
      // Même fonction que dans add_beer.php
      document.getElementById('preview_name').innerText = capitalizeFirstLetter(document.getElementById('beer_name').value);
      document.getElementById('preview_origin').innerText = capitalizeFirstLetter(document.getElementById('origin').value);
      document.getElementById('preview_alcohol').innerText = document.getElementById('alcohol').value + '%';
      document.getElementById('preview_description').innerText = capitalizeFirstLetter(document.getElementById('description').value);
      document.getElementById('preview_price').innerText = document.getElementById('price').value + '€';

      let imageUrl = document.getElementById('image').value;
      let imagePreview = document.getElementById('preview_image');
      if (imageUrl) {
        imagePreview.src = imageUrl;
        imagePreview.style.display = 'block';
      } else {
        imagePreview.src = "";
        imagePreview.style.display = 'none';
      }
    }

    function capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    }
  </script>
</head>

<body style="background-image: url('../images/beer_bg.jpg');" class="h-screen bg-cover bg-no-repeat bg-center flex items-center">
  <div class="flex justify-around w-full">
    <div class="w-1/2 flex justify-center">
      <div class="bg-stone-500/80 text-white flex flex-col w-3/4 items-center rounded-xl py-6">
        <h3 class="text-3xl mb-4">Modifier la bière</h3>
        <form action="edit_beer_traitement.php" method="post" class="flex flex-col items-center w-full">
          <input type="hidden" name="id" value="<?= $beer['id'] ?>">

          <label for="image" class="text-lg mb-2">Image</label>
          <input type="text" id="image" name="image" value="<?= htmlspecialchars($beer['image']) ?>" class="border border-black w-64 bg-white text-black rounded-lg mb-4 min-h-[28px]" oninput="updatePreview()">

          <label for="beer_name" class="text-lg mb-2">Nom de la bière</label>
          <input type="text" id="beer_name" name="beer_name" value="<?= htmlspecialchars($beer['name']) ?>" class="border border-black w-64 bg-white text-black rounded-lg mb-4 min-h-[28px]" required oninput="updatePreview()">

          <label for="origin" class="text-lg mb-2">Origine</label>
          <input type="text" id="origin" name="origin" value="<?= htmlspecialchars($beer['origin']) ?>" class="border border-black w-64 bg-white text-black rounded-lg mb-4 min-h-[28px]" required oninput="updatePreview()">

          <label for="alcohol" class="text-lg mb-2">% Alcool</label>
          <input type="number" step=".01" min="0" id="alcohol" name="alcohol" value="<?= htmlspecialchars($beer['alcohol']) ?>" class="border border-black w-64 bg-white text-black rounded-lg mb-4 min-h-[28px]" required oninput="updatePreview()">

          <label for="price" class="text-lg mb-2">Prix</label>
          <input type="number" step=".01" min="0" id="price" name="price" value="<?= htmlspecialchars($beer['average_price']) ?>" class="border border-black w-64 bg-white text-black rounded-lg mb-4 min-h-[28px]" required oninput="updatePreview()">

          <label for="description" class="text-lg mb-2">Description</label>
          <textarea id="description" name="description" class="border border-gray-500 bg-white text-black placeholder:text-black p-2 rounded-lg resize-none w-64 mb-4" required oninput="updatePreview()"><?= htmlspecialchars($beer['description']) ?></textarea>

          <div class="flex gap-4">
            <input type="submit" value="Modifier" class="bg-blue-500 px-6 py-2 border border-black rounded-lg hover:bg-blue-700 hover:text-white active:bg-blue-900 transition duration-200">
            <a href="tab.php" class="bg-gray-500 px-6 py-2 border border-black rounded-lg hover:bg-gray-700 hover:text-white active:bg-gray-900 transition duration-200">Annuler</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Même prévisualisation que dans add_beer.php -->
    <div class="w-1/2 flex justify-center my-auto">
      <div class="bg-stone-500/80 text-white w-96 rounded-xl p-6 flex flex-col">
        <h3 class="text-2xl mb-4 text-center">Prévisualisation de la carte</h3>

        <!-- Card Preview -->
        <div class="bg-white text-black rounded-lg overflow-hidden shadow-lg">
          <!-- Image Container -->
          <div class="w-full h-48 overflow-hidden bg-gray-100">
            <img id="preview_image" src="<?= htmlspecialchars($beer['image']) ?>" alt="Image de la bière"
              class="w-full h-full object-contain">
          </div>

          <!-- Content Container -->
          <div class="p-4">
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
  <script>
    // Initialiser la prévisualisation au chargement
    window.onload = function() {
      updatePreview();
    };
  </script>
</body>

</html>