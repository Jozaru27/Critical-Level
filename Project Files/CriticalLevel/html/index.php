<?php
session_start();
require_once "../php/database.php";

// Verifies if user is logged and the role
$userLoggedIn = isset($_SESSION['usuario_email']);
$userRole = null;

if ($userLoggedIn) {
    $email = $_SESSION['usuario_email'];
    $stmt = $pdo->prepare("SELECT idROL FROM usuarios WHERE email = ?");
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
    <link rel="stylesheet" href="../css/indexPageStyle.css">

    <!-- Icon -->
    <link rel="icon" type="image/x-icon" href="../media/CL_Logo_Blue_Hex/favicon.ico">

    <!-- Libraries -->
    <link href="../libraries/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="../libraries/bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <!-- Script -->
    <script src="../js/script.js"></script>
    <script src="../js/indexGetReviewsHandler.js"></script>
    <script src="../js/randomGame.js"></script>
    
    <!-- Main Page -->
    <title>Critical Level</title>
  </head>
  <body>

    <nav class="menu-container">
      <!-- Burger Menu -->
      <input type="checkbox" aria-label="Toggle menu" />
      <span></span>
      <span></span>
      <span></span>
    
      <!-- Logo -->
      <a href="../../index.php" class="menu-logo">
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

    <!-- Review Loader with filter -->
    <div class="container mt-5">
      <!-- Collab with F1S Banner - Viewable by everyone -->
      <div class="bannerContainer">
        <img class="imgBanner" src="../media/collab/bannerCollab_F1S_CL.png" alt="Banner de Anuncio"></img>
      </div>

        <h1 class="text-center">Reseñas</h1>
        <div class="d-flex justify-content-end mb-4">
            <select id="sortOptions" class="form-select w-auto">
                <option value="recientes">Más recientes</option>
                <option value="alta">Valoraciones más altas</option>
                <option value="baja">Valoraciones más bajas</option>
            </select>
        </div>
        <div id="reviewsContainer">
            <!-- Reviews Load here -->
        </div>
    </div>

    <!-- Ad Banner -->
    <?php if (!$userLoggedIn || $userRole == 2): ?>
    <div class="bannerContainer">
      <img class="adBanner" src="../media/collab/STEAM_AD.jpg" alt="Banner de Anuncio">
    </div>
    <?php endif; ?>

    <!-- Top Users Module -->
    <div class="top-users mt-5">
      <h2>Top 5 Usuarios Más Activos</h2>
      <div id="topUsersContainer">
          <?php include '../php/getTopUsers.php'; ?>
      </div>
    </div><br>

    <!-- Random Game Module -->
    <div class="random-game">
    <h2>Prueba un juego al azar</h2>
      <img src="https://mario.wiki.gallery/images/thumb/7/7f/Question_Block_-_Nintendo_JP_website.png/1200px-Question_Block_-_Nintendo_JP_website.png" alt="Interrogante" id="randomGameImg">
    </div>

    <div id="gameDetail"></div>

    
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
              <li><a href="index.php">Inicio</a></li>
              <li><a href="games.php">Juegos</a></li>
              <li><a href="eventos.php">Eventos</a></li>
              <li><a href="premium.php">Premium</a></li>
            </ul>
          </div>

          <div class="col-xs-6 col-md-3">
            <h6>Legal</h6>
            <ul class="footer-links">
              <li><a href="legal/aboutus.html">Sobre Nosotros</a></li>
              <li><a href="forms/contactus.html">Contáctanos</a></li>
              <!-- <li><a href="">Contribuir [NULL]</a></li> -->
              <li><a href="legal/privacypolicy.html">Política de Privacidad</a></li>
              <!-- <li><a href="">Sitemap [NULL]</a></li> -->
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
  </body>
</html>