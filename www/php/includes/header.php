<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
// Récupérer le nom du fichier courant
$current_page = basename($_SERVER['PHP_SELF']);
?>

<header class="w-full bg-black/50 fixed top-0 z-50">
  <div class="container mx-auto px-2 py-2">
    <div class="flex w-3/3">
      <!-- Logo et titre -->
      <div class="flex items-center gap-4 w-1/3">
        <a href="./accueil.php" class="flex items-center gap-2">
          <img src="../images/Logo-beercraft-removebg-preview.png" alt="Logo Beercraft" class="w-16 h-16 object-contain" width="50">
        </a>
      </div>

      <!-- Titre central -->
      <div class="text-center text-white w-1/3">
        <h1 class="text-3xl font-bold">Beercraft</h1>
        <h2 class="italic text-xl">Chaque bière a une histoire, partagez la vôtre !</h2>
      </div>

      <!-- Navigation -->
      <nav class="flex items-center justify-end gap-2 w-1/3">
        <a href="./accueil.php" class="text-white hover:text-amber-400 transition-colors px-3 py-2 rounded-lg hover:bg-black/30">
          Accueil
        </a>

        <?php if (isset($_SESSION['user_id'])): ?>
          <div class="flex items-center gap-2">
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
              <a href="./tab.php" class="text-white hover:text-amber-400 transition-colors px-3 py-2 rounded-lg hover:bg-black/30">
                Administration
              </a>
              <a href="./add_beer.php" class="text-white hover:text-amber-400 transition-colors px-3 py-2 rounded-lg hover:bg-black/30">
                Ajouter une bière
              </a>
            <?php else: ?>

            <?php endif; ?>
            <a href="./logout.php" class="bg-red-500/80 hover:bg-red-600 text-white px-3 py-2 rounded-lg transition-colors">
              Déconnexion
            </a>
          </div>
        <?php else: ?>
          <div class="flex items-center gap-2">
            <a href="./login.php" class="bg-amber-500/80 hover:bg-amber-600 text-white px-3 py-2 rounded-lg transition-colors">
              Connexion
            </a>
            <a href="./signup.php" class="bg-blue-500/80 hover:bg-blue-600 text-white px-3 py-2 rounded-lg transition-colors">
              Inscription
            </a>
          </div>
        <?php endif; ?>
      </nav>
    </div>
  </div>
</header>

<!-- Spacer pour compenser la hauteur du header fixe -->
<div class="h-28"></div>