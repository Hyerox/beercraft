<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Footer</title>
  <script>
    function sendMail() {


      const mailtoLink = `mailto:beercraft@outlook.com?`;
      window.open(mailtoLink, '_blank');
    }
  </script>
  <style>
    .footer-container {
      box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease-in-out;
      border-top: 2px solid rgba(255, 255, 255, 0.1);
      margin-top: 2rem;
      padding-top: 1rem;
    }

    .footer-container:hover {
      box-shadow: 0 -6px 8px -1px rgba(0, 0, 0, 0.2);
    }

    .footer-heading {
      font-size: 1.5rem;
      font-weight: bold;
      margin-bottom: 1rem;
      text-align: center;
      color: #f59e0b;
      text-transform: uppercase;
      letter-spacing: 0.1em;
    }
  </style>
</head>

<body>
  <footer class="bg-black/50 text-white backdrop-blur-sm footer-container">
    <h2 class="footer-heading">Beercraft</h2>
    <p class="text-xl py-2 text-center">Merci d'être venu sur le site Beercraft ! Si vous voulez plus d'informations nous concernant, les voici !</p>
    <div class=" flex justify-around">
      <div class="flex flex-col gap-1">
        <h1 class="text-center underline">Nos informations</h1>
        <h2>Email : <button onclick="sendMail()">beercraft@outlook.com</button></h2>
        <h2>Numéro de téléphone : 01-23-45-67-89</h2>
        <h2>Nos locaux : <a href=" https://www.google.com/maps/place/Chalet+des+Cascades/@46.0477233,6.7653504,17z/data=!3m1!4b1!4m6!3m5!1s0x478eab9d7a6b34fd:0x3596330ef4ef2e1e!8m2!3d46.0477196!4d6.7679253!16s%2Fg%2F11c58410pj?hl=fr&entry=ttu&g_ep=EgoyMDI1MDMwOC4wIKXMDSoASAFQAw%3D%3D" class="text-blue-600 hover:text-blue-700 underline"> Chalet des cascades, Sixt-Fer-à-cheval, 74273, FRANCE</a></h2>
      </div>
      <div class="flex flex-col text-center gap-1">
        <h1 class="underline">Nos réseaux</h1>
        <a href="https://www.facebook.com" class="flex gap-2"><img src="/images/facebook.png" class="w-6 h-6" alt="logo-facebook" /> Facebook </a>
        <a href="https://www.twitter.com" class="flex gap-2"><img src="/images/twitter.png" class="w-6 h-6" alt="logo-x" /> X </a>
        <a href="https://www.instagram.com" class="flex gap-2"><img src="/images/instagram.png" class="w-6 h-6" alt="logo-instagram" /> Instagram </a>
        <a href="https://www.linkedin.com" class="flex gap-2"><img src="/images/linkedin.png" class="w-6 h-6" alt="logo-linkedin" /> Linkedin </a>
      </div>
    </div>
    <p class=" text-center">© Copyright Property of Hyerox - 2025 ©</p>
  </footer>
</body>

</html>