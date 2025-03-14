<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <script>
    function partager() {
      if (navigator.share) {
        navigator.share({
          title: document.title,
          text: "Découvrez cette page !",
          url: window.location.href
        }).then(() => {
          console.log('Partage réussi');
        }).catch((error) => {
          console.error('Erreur de partage :', error);
        });
      } else {
        alert("Le partage n'est pas pris en charge sur ce navigateur.");
      }
    }
  </script>
</body>

</html>