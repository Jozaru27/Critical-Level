<?php
session_start();
require_once "../php/database.php";

// Verificar si el usuario ha iniciado sesión y obtener el rol del usuario
$userLoggedIn = isset($_SESSION['usuario_email']);
$userRole = null;

if ($userLoggedIn) {
    $email = $_SESSION['usuario_email'];
    $stmt = $pdo->prepare("SELECT idROL FROM Usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $userRole = $stmt->fetchColumn();
}
?>

<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fonts -->
    <link rel="stylesheet" href="../css/fonts.css">

    <!-- StyleSheets -->
    <link rel="stylesheet" href="../css/MainPageStyle.css">
    <link rel="stylesheet" href="../css/GamesPageStyle.css">

    <!-- Icon -->
    <link rel="icon" type="image/x-icon" href="../media/CL_Logo_Blue_Hex/favicon.ico">

    <!-- Libraries -->
    <link href="../libraries/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="../libraries/bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <!-- Script -->
    <script src="../js/script.js"></script>
    <script src="../js/GamesPageScript.js"></script>
    
    <!-- Main Page -->
    <title>Critical Level</title>

    <style>
      .game-card {
          position: relative;
          display: inline-block;
          width: 250px; /* Ajusta el ancho según tus necesidades */
          margin: 10px;
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
          transition: transform 0.2s, box-shadow 0.2s;
          background-color: rgba(0, 0, 0, 0.75);
          cursor: pointer;
      }

      .game-card img {
          width: 100%;
          display: block;
          height: 150px;
      }

      .game-card .game-title {
          text-align: center;
          padding: 10px;
          font-weight: bold;
          color: white;
      }

      .game-card:hover {
          transform: scale(1.05);
          box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
      }

      .pagination {
          margin-top: 20px;
          text-align: center;
      }

      /* Banner Style */

      .bannerContainer{
          display: flex;
          justify-content: center;
          align-items: center;
          margin-bottom: 20px;
          padding: 10px;
      }

      .imgBanner{
          max-width: 75%;
          height: auto;
      }

      .adBanner{
          max-width: 35%;
          height: auto;
      }
    </style>
  </head>
  <body>

  <nav class="menu-container">
      <!-- Burger Menu -->
      <input type="checkbox" aria-label="Toggle menu" />
      <span></span>
      <span></span>
      <span></span>
    
      <!-- Logo -->
      <a href="../../index.html" class="menu-logo">
        <img src="../media/CL_Logo_Blue_Hex/CL_Logo_HD_White.png" alt="Landing Page"/>
      </a>
    <!-- Navbar Menu -->
    <div class="menu">
      <ul>
        <li>
          <a href="index.php">
              Inicio
          </a>
        </li>
        <li>
          <a href="games.php">
              Juegos
          </a>
        </li>
        <li>
          <a href="eventos.php">
              Eventos
          </a>
        </li>
        <li>
          <a href="premium.php">
              Premium
          </a>
        </li>
      </ul>
      <ul>
        <?php if (isset($_SESSION['usuario_email'])): ?>
          <li>
            <a href="profiles/profile.php">
               Perfil
            </a>
          </li>
          <li>
            <a href="../php/logout.php">
              Cerrar Sesión
            </a>
            </li>
         <?php else: ?>
            <li>
              <a href="forms/signup.html">
                Registro
              </a>
            </li>
            <li>
              <a href="forms/login.html">
                Iniciar Sesión
              </a>
            </li>
        <?php endif; ?>
      </ul>
    </div>
  </nav>


    <!-- Banner de anuncio -->
    <?php if (!$userLoggedIn || $userRole == 2): ?>
      <div class="bannerContainer">
        <img class="adBanner" src="../media/collab/IG_AD.jpg" alt="Banner de Anuncio">
      </div>
    <?php endif; ?>

    <!-- Contenido de la página -->
    <div class="container">
      <div class="games-container" id="gamesContainer"></div>
      <div class="pagination" id="pagination">
          <button id="prevPage" disabled>Anterior</button>
          <button id="nextPage">Siguiente</button>
      </div>
    </div>


  <!-- Site Footer -->
  <footer class="site-footer">
    <div class="container">
      <div class="row">
        <div class="col-sm-12 col-md-6">
          <h6>Sobre Critical Level</h6>
          <p class="text-justify">Critical Level es una simple página web en la cuál recogemos una amplia variedad de videojuegos, además de información relacionada y peritenente a los mismos. El uso de esta página web implica que aceptas, no sólo leer las reglas impuestas en la misma, sino acatarlas para hacer un mejor uso y experiencia tanto para ti como para el resto de usuarios.</p>
        </div>

        <div class="col-xs-6 col-md-3">
          <h6>Enlaces</h6>
          <ul class="footer-links">
            <li><a href="../../index.html">Landing Page</a></li>
            <li><a href="../html/index.html">Inicio</a></li>
            <li><a href="../html/games.html">Juegos</a></li>
            <li><a href="">Flipes [NULL]</a></li>
            <li><a href="">Pipes [NULL]</a></li>
            <li><a href="">Yuju [NULL]</a></li>
          </ul>
        </div>

        <div class="col-xs-6 col-md-3">
          <h6>Legal</h6>
          <ul class="footer-links">
            <li><a href="legal/aboutus.html">Sobre Nosotros</a></li>
            <li><a href="forms/contactus.html">Contáctanos</a></li>
            <li><a href="">Contribuir [NULL]</a></li>
            <li><a href="legal/privacypolicy.html">Política de Privacidad</a></li>
            <li><a href="">Sitemap [NULL]</a></li>
          </ul>
        </div>
      </div>
      <hr>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-md-8 col-sm-6 col-xs-12">
          <p class="copyright-text">Copyright &copy; 2024 Todos los Derechos Reservados 
           <a href="https://github.com/Jozaru27">Jose Zafrilla Ruiz</a>.
          </p>
        </div>

        <!-- Icons Taken from https://icons8.com/ <a target="_blank" href="https://icons8.com/icon/12505/steam">Steam</a> icon by <a target="_blank" href="https://icons8.com">Icons8</a>--> 
        <div class="col-md-4 col-sm-6 col-xs-12">
          <ul class="social-icons">
            <li><a class="github" href="https://github.com/Jozaru27/Critical-Level"><i class="bi-github"></i></a></li>
            <li><a class="linkedin" href="https://www.linkedin.com/in/jose-zafrilla-ruiz/"><i class="bi-linkedin"></i></a></li>
            <!-- <li><a class="steam" href="https://steamcommunity.com/id/jozaru"><i class="bi bi-steam"></i></a></li> -->
            <!--<li><a class="linkedin" href="#"><i class="fa fa-linkedin"></i></a></li>    -->
          </ul>
        </div>
      </div>
    </div>
</footer>

<script>
  let currentPage = 1;

  const gamesContainer = document.getElementById('gamesContainer');
  const prevPageButton = document.getElementById('prevPage');
  const nextPageButton = document.getElementById('nextPage');

  function fetchGames(page) {
      const apiUrl = `${baseUrl}/games?key=${apiKey}&page=${page}`;
      fetch(apiUrl)
          .then(response => response.json())
          .then(data => {
              displayGames(data.results);
              updatePagination(data.previous, data.next);
          })
          .catch(error => console.error('Error fetching games:', error));
  }

  function displayGames(games) {
      gamesContainer.innerHTML = '';
      games.forEach(game => {
          const gameCard = document.createElement('div');
          gameCard.classList.add('game-card');
          gameCard.innerHTML = `<img src="${game.background_image}" alt="${game.name}">`;
          gameCard.innerHTML += `<div class="game-title">${game.name}</div>`;
          gameCard.addEventListener('click', () => {
              window.location.href = `profiles/game.php?id=${game.id}`;
          });
          gamesContainer.appendChild(gameCard);
      });
  }

  function updatePagination(previousPageUrl, nextPageUrl) {
      if (previousPageUrl) {
          prevPageButton.disabled = false;
          prevPageButton.onclick = () => {
              currentPage--;
              fetchGames(currentPage);
          };
      } else {
          prevPageButton.disabled = true;
          prevPageButton.onclick = null;
      }

      if (nextPageUrl) {
          nextPageButton.disabled = false;
          nextPageButton.onclick = () => {
              currentPage++;
              fetchGames(currentPage);
          };
      } else {
          nextPageButton.disabled = true;
          nextPageButton.onclick = null;
      }
  }

  // Iniciar con la primera página de juegos
  fetchGames(currentPage);
</script>


  </body>
</html>