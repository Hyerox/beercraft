<?php

session_start();
require_once "db.php";

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
      return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function updatePreview() {
      document.getElementById('preview_name').innerText = capitalizeFirstLetter(document.getElementById('beer_name').value);
      document.getElementById('preview_origin').innerText = capitalizeFirstLetter(document.getElementById('origin').value);
      document.getElementById('preview_alcohol').innerText = document.getElementById('alcohol').value + '%';
      document.getElementById('preview_description').innerText = capitalizeFirstLetter(document.getElementById('description').value);

      // Récupérer l'URL de l'image entrée
      let imageUrl = document.getElementById('image').value;
      let imagePreview = document.getElementById('preview_image');

      if (imageUrl) {
        imagePreview.src = imageUrl;
        imagePreview.style.display = 'block';
      } else {
        imagePreview.src = "";
        imagePreview.style.display = 'none'; // Cacher l'image si aucun URL n'est entré
      }
    }
  </script>
</head>

<body style="background-image: url('../images/beer_bg.jpg');" class="h-screen bg-cover bg-no-repeat bg-center flex items-center">

  <div class="flex justify-around w-full">
    <div class="w-1/2 flex justify-center">
      <div class="bg-stone-500/80 text-white flex flex-col w-3/4 items-center rounded-xl py-6">
        <h3 class="text-3xl mb-4">Ajout d'une bière</h3>
        <form action="add_beer_traitement.php" method="post" class="flex flex-col items-center w-full">
          <label for="image" class="text-lg mb-2">Image</label>
          <input type="text" id="image" name="image" class="border border-black w-64 bg-white text-black rounded-lg mb-4 min-h-[28px]" placeholder="Entrez l'URL de l'image" oninput="updatePreview()">

          <label for="beer_name" class="text-lg mb-2">Nom de la bière</label>
          <input type="text" id="beer_name" name="beer_name" class="border border-black w-64 bg-white text-black rounded-lg mb-4 min-h-[28px]" required oninput="updatePreview()">

          <label for="origin" class="text-lg mb-2">Origine</label>
          <input type="text" id="origin" name="origin" class="border border-black w-64 bg-white text-black rounded-lg mb-4 min-h-[28px]" required oninput="updatePreview()">

          <label for="alcohol" class="text-lg mb-2">% Alcool</label>
          <input type="number" step=".01" min="0" id="alcohol" name="alcohol" class="border border-black w-64 bg-white text-black rounded-lg mb-4 min-h-[28px]" required oninput="updatePreview()">

          <label for="description" class="text-lg mb-2">Description</label>
          <textarea id="description" name="description" cols="20" rows="10" class="border border-gray-500 bg-white text-black placeholder:text-black p-2 rounded-lg resize-none w-64 mb-4" placeholder="Entrez une description détaillée de la bière..." required oninput="updatePreview()"></textarea>



          <input type="submit" value="Ajouter" class="bg-gray-500 px-6 py-2 border border-black rounded-lg hover:bg-gray-700 hover:text-white active:bg-gray-900 transition duration-200">
        </form>
      </div>
    </div>

    <div class="w-1/2 flex justify-center my-auto">
      <div class="bg-stone-500/80 text-white w-96 rounded-xl p-6 flex flex-col">
        <h3 class="text-2xl mb-4 text-center">Prévisualisation de la carte</h3>

        <!-- Card Preview -->
        <div class="bg-white text-black rounded-lg overflow-hidden shadow-lg">
          <!-- Image Container -->
          <div class="w-full h-48 overflow-hidden">
            <img id="preview_image" src="https://images.unsplash.com/photo-1608270586620-248524c67de9?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OHx8YmVlcnxlbnwwfHwwfHx8MA%3D%3D" alt="Image de la bière"
              class="w-full h-full object-cover">
          </div>

          <!-- Content Container -->
          <div class="p-4">
            <div class="flex justify-between items-center mb-2">
              <!-- NAME -->
              <h4 id="preview_name" class="text-xl font-bold">-</h4>
              <!-- DEGRÉ -->
              <span id="preview_alcohol" class="bg-amber-100 text-amber-800 px-2 py-1 rounded-full text-sm">-%</span>
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

</body>

</html>