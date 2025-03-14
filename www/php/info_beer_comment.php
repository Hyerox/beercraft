<?php
session_start();
require_once "db.php";
include "./tools/tools.php";

// Débogage - Afficher les informations de session
error_log("Session user_role: " . ($_SESSION['user_role'] ?? 'non défini'));
error_log("Session user_id: " . ($_SESSION['user_id'] ?? 'non défini'));

// Initialisation
$error = $_SESSION['error'] ?? null;
$success = $_SESSION['success'] ?? null;
unset($_SESSION['error'], $_SESSION['success']);
$comments = [];
$beer = null;

// Récupération des données
try {
  // Récupération de la bière
  $stmt = $pdo->prepare("SELECT * FROM Beer WHERE id = ?");
  $stmt->execute([$_GET['id']]);
  $beer = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$beer) {
    exit("Bière introuvable...");
  }

  // Récupération des commentaires
  $stmt = $pdo->prepare("
        SELECT c.*, u.first_name, u.id as user_id
        FROM Comment c 
        LEFT JOIN User u ON c.user_id = u.id 
        WHERE c.beer_id = ? 
        ORDER BY c.created_at DESC
    ");
  $stmt->execute([$_GET['id']]);
  $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $error = "Erreur lors de la récupération des données";
}

// Vérifier si l'utilisateur est admin
$isAdmin = isset($_SESSION['role']) && (strtolower($_SESSION['role']) === 'admin' || $_SESSION['role'] === '1');

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title><?= ucfirst(htmlspecialchars($beer['name'])) ?></title>
  <script>
    function comment() {
      const section = document.getElementById('comment-section');
      const input = document.getElementById('comment-input');

      section.scrollIntoView({
        behavior: 'smooth'
      });

      // Focus sur le champ après un court délai pour laisser le scroll se terminer
      setTimeout(() => {
        input.focus();
      }, 500); // vous pouvez ajuster la durée selon vos tests
    }
  </script>
</head>

<body style="background-image: url('../../images/beer_bg.jpg');" class="bg-cover bg-center min-h-screen bg-fixed">
  <?php
  $header = require_once "./includes/header.php";
  echo $header;
  ?>

  <!-- Section principale -->
  <div class="min-h-[70vh] flex items-center justify-center w-full py-10" style="background: linear-gradient(to bottom, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.5) 100%);">

    <div class="w-11/12 max-w-6xl bg-black/30 p-8 rounded-xl backdrop-blur-sm">
      <div class="flex flex-col md:flex-row justify-between items-start gap-12">
        <!-- Image de la bière -->
        <div class="w-full md:w-1/3 bg-white/10 p-4 rounded-xl">
          <img src="<?= htmlspecialchars($beer['image']); ?>"
            alt="Image de la bière"
            class="w-full h-auto object-contain rounded-lg shadow-2xl" />
        </div>

        <!-- Informations de la bière -->
        <div class="flex flex-col justify-between text-white space-y-6 w-full md:w-2/3">
          <div>
            <h1 class="text-4xl font-bold mb-4"><?= ucfirst(htmlspecialchars($beer['name'])); ?></h1>
            <div class="flex gap-4 mb-6">
              <span class="px-4 py-2 bg-amber-500/20 rounded-full">
                <?= htmlspecialchars($beer['alcohol']); ?>% vol.
              </span>
              <span class="px-4 py-2 bg-green-500/20 rounded-full">
                <?= htmlspecialchars($beer['average_price']); ?>€
              </span>
            </div>
            <p class="text-lg mb-4">
              <span class="text-gray-400">Origine:</span>
              <span class="font-medium"><?= ucfirst(htmlspecialchars($beer['origin'])); ?></span>
            </p>
            <p class="text-gray-300 leading-relaxed">
              <?= ucfirst(htmlspecialchars($beer['description'])); ?>
            </p>
          </div>

          <!-- Boutons d'action -->
          <div class="flex flex-wrap gap-4 mt-8">

            <button onclick="comment()" class="flex items-center gap-2 bg-stone-500/80 hover:bg-stone-600 px-6 py-3 rounded-lg transition-all duration-300 shadow-lg hover:shadow-stone-500/50">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
              </svg>
              Commenter
            </button>

            <button onclick="partager()" class="flex items-center gap-2 bg-amber-700/80 hover:bg-amber-800 px-6 py-3 rounded-lg transition-all duration-300 shadow-lg hover:shadow-amber-700/50">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
              </svg>
              Partager
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Section commentaires -->
  <div class="w-full py-12 px-4" style="background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.6) 100%);">
    <div class="w-11/12 max-w-4xl mx-auto">
      <h2 class="text-2xl font-bold text-white mb-4">Commentaires</h2>

      <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
          <?= $error ?>
        </div>
      <?php endif; ?>

      <?php if (!isset($_SESSION['user_id'])): ?>
        <div class="bg-amber-500/80 text-white p-4 rounded-lg mb-6">
          <p class="text-center">Vous devez être connecté pour laisser un commentaire.</p>
          <div class="flex justify-center mt-4">
            <a href="login.php" class="bg-white text-amber-500 px-6 py-2 rounded-lg hover:bg-gray-100">Se connecter</a>
          </div>
        </div>
      <?php else: ?>
        <!-- Formulaire de commentaire -->
        <form method="POST" action="info_beer_comment_traitement.php?beer_id=<?= $beer['id'] ?>" class="mb-8">
          <div class="mb-4" id="comment-section">
            <label class="block text-white mb-2">Note :</label>
            <select name="rating" class="rounded-lg px-3 py-2">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <option value="<?= $i ?>"><?= str_repeat('⭐', $i) ?></option>
              <?php endfor; ?>
            </select>
          </div>
          <textarea
            name="content" id="comment-input"
            required
            class="w-full p-4 rounded-lg bg-white/90 mb-2"
            rows="3"
            placeholder="Laissez votre commentaire..."></textarea>
          <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-2 rounded-lg transition-colors">
            Envoyer
          </button>
        </form>
      <?php endif; ?>

      <!-- Liste des commentaires -->
      <div class="space-y-4">
        <?php if (empty($comments)): ?>
          <div class="bg-white/90 p-4 rounded-lg">
            <p class="text-gray-500 text-center">Aucun commentaire pour le moment. Soyez le premier à commenter !</p>
          </div>
        <?php else: ?>
          <?php foreach ($comments as $comment): ?>
            <div class="bg-white/90 p-4 rounded-lg">
              <div class="flex justify-between items-start mb-2">
                <div>
                  <h3 class="font-bold"><?= ucfirst($comment['first_name']) ?></h3>
                  <span class="text-sm text-gray-500">
                    <?= str_repeat('⭐', $comment['rating']) ?>
                  </span>
                  <span class="text-sm text-gray-500 ml-2">
                    <?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?>
                  </span>
                </div>
                <div class="flex justify-end">
                  <?php if ($isAdmin || (isset($comment['user_id']) && isset($_SESSION['user_id']) && $comment['user_id'] == $_SESSION['user_id'])): ?>
                    <form method="POST" action="info_beer_comment_traitement.php?beer_id=<?= $beer['id'] ?>"
                      onsubmit="return confirm('<?= $isAdmin ? 'Administrateur: ' : '' ?>Voulez-vous vraiment supprimer ce commentaire ?');">
                      <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                      <button type="submit" name="delete_comment"
                        class="text-red-500 hover:text-red-700 flex items-center gap-2">
                        <?php if ($isAdmin && $comment['user_id'] != $_SESSION['user_id']): ?>
                          <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Admin</span>
                        <?php endif; ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </button>
                    </form>
                  <?php endif; ?>
                </div>
              </div>
              <p><?= nl2br($comment['content']) ?></p>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <?php if ($isAdmin): ?>
        <!-- Pour debug -->
        <div class="text-white text-sm mb-2">Mode administrateur actif</div>
      <?php endif; ?>
    </div>
  </div>
</body>

</html>