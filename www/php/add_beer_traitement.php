<?php

session_start();
require_once "db.php";

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

echo "test beer traitement";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  try {
    // CREE LES DONNEES DE LA BIERE A PARTIR DES SAISIES DE add_beer.php //
    try {
      $stmt = $pdo->prepare("INSERT INTO Beer (name, origin, alcohol, description, image) VALUES (:name, :origin, :alcohol, :description, :image)");
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':origin', $origin);
      $stmt->bindParam(':alcohol', $alcohol);
      $stmt->bindParam(':description', $description);
      $stmt->bindParam(':image', $image);

      // Remplacer les valeurs
      $name = $_POST["beer_name"];
      $origin = $_POST["origin"];
      $alcohol = $_POST["alcohol"];
      $description = $_POST["description"];
      $image = $_POST["image"];
      $stmt->execute();
    } catch (PDOException $e) {
      echo "Erreur d'insertion : " . $e->getMessage();
    }
  } catch (Exception $e) {
    // Utiliser une variable de session pour afficher le message d'erreur sur la page de connexion
    $_SESSION['error'] = "Erreur : " . $e->getMessage();
    header("Location: add_beer.php");
    exit;
  }
}
