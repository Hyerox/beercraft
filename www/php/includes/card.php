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
        <?= ucfirst(htmlspecialchars($beer['description'])) ?>
      </p>

      <!-- Action Buttons -->
      <div class="mt-4 flex justify-between items-center">
        <a href="./info_beer_comment.php?id=<?= htmlspecialchars($beer['id']) ?>" class="bg-amber-500 text-white px-4 py-2 rounded-lg hover:bg-amber-600 transition-colors">
          Voir détails
        </a>
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