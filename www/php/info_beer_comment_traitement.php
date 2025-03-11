<?php
session_start();
require_once "db.php";

// Vérifications préalables
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}

if (!isset($_GET['beer_id'])) {
  header('Location: accueil.php');
  exit();
}

$beer_id = $_GET['beer_id'];
$redirect_url = "info_beer_comment.php?id=" . $beer_id;

// Vérifier le rôle de l'utilisateur
try {
  $stmt = $pdo->prepare("SELECT role FROM User WHERE id = ?");
  $stmt->execute([$_SESSION['user_id']]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
  $isAdmin = ($user['role'] === 'admin');
} catch (PDOException $e) {
  $_SESSION['error'] = "Erreur lors de la vérification des droits";
  header("Location: " . $redirect_url);
  exit();
}

// Gestion des actions POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['delete_comment'])) {
    try {
      if ($isAdmin) {
        $stmt = $pdo->prepare("DELETE FROM Comment WHERE id = ?");
        $stmt->execute([$_POST['comment_id']]);
      } else {
        $stmt = $pdo->prepare("DELETE FROM Comment WHERE id = ? AND user_id = ?");
        $stmt->execute([$_POST['comment_id'], $_SESSION['user_id']]);
      }
      $_SESSION['success'] = "Commentaire supprimé avec succès";
    } catch (PDOException $e) {
      $_SESSION['error'] = "Erreur lors de la suppression du commentaire";
    }
  } elseif (isset($_POST['content'])) {
    try {
      $stmt = $pdo->prepare("INSERT INTO Comment (content, rating, user_id, beer_id, created_at) VALUES (?, ?, ?, ?, NOW())");
      $stmt->execute([
        htmlspecialchars($_POST['content']),
        $_POST['rating'] ?? 5,
        $_SESSION['user_id'],
        $beer_id
      ]);
      $_SESSION['success'] = "Commentaire ajouté avec succès";
    } catch (PDOException $e) {
      $_SESSION['error'] = "Erreur lors de l'ajout du commentaire";
    }
  }
}

header("Location: " . $redirect_url);
exit();
