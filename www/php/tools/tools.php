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

    // NOTE SCRIPT POUR CHARGER EMAIL //
    function sendMail(e) {
      const subject = encodeURIComponent("Ajout d'une nouvelle bière");
      const body = encodeURIComponent(`Bonjour,

Je souhaite ajouter une nouvelle bière :

Nom de la bière :
Origine :
Degré d'alcool :
Prix moyen :
Description :
URL de l'image :

Cordialement.`);

      const mailtoLink = `mailto:beercraft@outlook.com?subject=${subject}&body=${body}`;
      window.open(mailtoLink, '_blank');
    }

    function envoieMail() {


      const mailtoLink = `mailto:beercraft@outlook.com?`;
      window.open(mailtoLink, '_blank');
    }

    // Vérification de l'existence des éléments du slider
    document.addEventListener('DOMContentLoaded', function() {
      const slider = document.querySelector('.slider');
      const slides = document.querySelectorAll('.slide');

      if (slider && slides.length > 0) {
        let currentSlide = 0;

        function nextSlide() {
          currentSlide = (currentSlide + 1) % slides.length;
          slider.style.transform = `translateX(-${currentSlide * 100}%)`;
        }

        // Change de slide toutes les 3 secondes seulement s'il y a plus d'une slide
        if (slides.length > 1) {
          setInterval(nextSlide, 3000);
        }
      }
    });

    function capitalizeFirstLetter(string) {
      if (!string) return '-';
      return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function updatePreview() {
      // Image preview
      let imageUrl = document.getElementById('image').value;
      let imagePreview = document.getElementById('preview_image');
      imagePreview.src = imageUrl || 'https://via.placeholder.com/400x300?text=Image+de+la+bière';
      imagePreview.style.display = 'block';

      // Text content preview
      document.getElementById('preview_name').innerText = capitalizeFirstLetter(document.getElementById('beer_name').value) || '-';
      document.getElementById('preview_origin').innerText = capitalizeFirstLetter(document.getElementById('origin').value) || '-';
      document.getElementById('preview_alcohol').innerText = (document.getElementById('alcohol').value || '-') + '%';
      document.getElementById('preview_description').innerText = capitalizeFirstLetter(document.getElementById('description').value) || '-';
      document.getElementById('preview_price').innerText = (document.getElementById('price').value || '-') + '€';
    }

    // Initialiser la prévisualisation au chargement
    window.onload = updatePreview;


    function capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    }

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
</body>

</html>