<?php

session_start();
require_once "db.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
  <title>Ajout de bière</title>
</head>

<body class="h-screen bg-stone-500 text-white flex justify-center flex-col">

  <h2 class="text-3xl mt-20 flex justify-center">Ajout de bière</h2>

  <form action="add_beer_traitement" method="post" class="flex flex-col mt-12 items-center h-screen">
    <label for="beer_name">Nom de la bière</label>
    <input type="text" name="beer_name" class="border border-black w-64 bg-white text-black" required><br>
    <label for="origin">Origine</label>
    <input type="text" name="origin" class="border border-black w-64 bg-white text-black" required><br>
    <label for="alcohol">% Alcool</label>
    <input type="text" name="alcohol" class="border border-black w-64 bg-white text-black" required><br>
    <label for="description">Description</label>
    <input type="text" name="description"
      class="border border-gray-500 w-64 bg-white text-black placeholder:text-black"
      placeholder="Entrez une description..." required>
    <br>


</body>

</html>