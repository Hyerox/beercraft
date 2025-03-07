<?php

session_start();
require_once "db.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
  exit("Aucune bière sélectionnée");
}

$beer_id = $_GET['id'];

try {
  $stmt = $pdo->prepare("SELECT * FROM Beer WHERE id = ?");
  $stmt->execute([$beer_id]);
  $beer = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$beer) {
    exit("Bière introuvable...");
  }
} catch (PDOException $e) {
  exit("Erreur de lecture : " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title><?= htmlspecialchars($beer['name']) ?></title>
</head>

<body style="background-image: url('../../images/beer_bg.jpg');" class="bg-cover bg-center h-screen bg-fixed">
  <div class="flex justify-center">
    <h1><?= $beer['name']; ?></h1>
  </div>
</body>

</html>