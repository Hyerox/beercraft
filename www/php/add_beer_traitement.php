<?php

session_start();
require_once "db.php";

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  try {
    $stmt = $pdo->prepare("INSERT INTO Beer (name, origin, alcohol, description, image, average_price, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([
      $_POST['beer_name'],
      $_POST['origin'],
      $_POST['alcohol'],
      $_POST['description'],
      $_POST['image'],
      $_POST['price']
    ]);

    header('Location: accueil.php?success=1');
    exit;
  } catch (PDOException $e) {
    header('Location: add_beer.php?error=1');
    exit;
  }
}
