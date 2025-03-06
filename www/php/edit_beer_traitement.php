<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id']) || !isset($_POST['id'])) {
  header('Location: login.php');
  exit;
}

try {
  $stmt = $pdo->prepare("UPDATE Beer SET name = ?, origin = ?, alcohol = ?, description = ?, image = ?, average_price = ? WHERE id = ?");
  $stmt->execute([
    $_POST['beer_name'],
    $_POST['origin'],
    $_POST['alcohol'],
    $_POST['description'],
    $_POST['image'],
    $_POST['price'],
    $_POST['id']
  ]);

  header('Location: tab.php?success=1');
  exit;
} catch (PDOException $e) {
  header('Location: tab.php?error=1');
  exit;
}
