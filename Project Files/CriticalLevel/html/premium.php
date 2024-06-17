<?php
session_start();
require_once "../php/database.php";

// Verify if the user has logged in and its role
$userLoggedIn = isset($_SESSION['usuario_email']);
$userRole = null;
$isPremium = false;

if ($userLoggedIn) {
    $email = $_SESSION['usuario_email'];
    $stmt = $pdo->prepare("SELECT idROL FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $userRole = $stmt->fetchColumn();
    $isPremium = ($userRole == 3); // Verify if its premium
}

?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan Premium - Critical Level</title>
    <!-- Stylesheets -->
    <link rel="stylesheet" href="../css/MainPageStyle.css">
    <link rel="stylesheet" href="../libraries/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/premiumPageStyle.css">
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
            <li><a href="index.php">Inicio</a></li>
            <li><a href="games.php">Juegos</a></li>
            <li><a href="eventos.php">Eventos</a></li>
            <li><a href="premium.php">Premium</a></li>
        </ul>
        <ul>
            <?php if ($userLoggedIn): ?>
                <li><a href="profiles/profile.php">Perfil</a></li>
                <li><a href="../php/logout.php">Cerrar Sesión</a></li>
            <?php else: ?>
                <li><a href="forms/signup.html">Registro</a></li>
                <li><a href="forms/login.html">Iniciar Sesión</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h1 class="text-center">Plan Premium</h1><br>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><u>Beneficios de suscribirte</u></h5>
                    <p>Suscribiéndote no sólo ayudas a que Critical Level pueda mantenerse a flote, sino que tenemos algunas cosas que ofrecerte:</p>
                    <ul>
                        <li>Participación en Eventos con posibilidad de ganar el premio.</li>
                        <li>Atención al cliente personalizada.</li>
                        <li>Di adiós a los anuncios.</li>
                        <li>Una insignia personalizada en el perfil.</li>
                        <li>Contribuir a la creación de más sorteos.</li>
                        <li>Y más...</li>
                    </ul>

                    <?php if ($userRole == 1): ?> <!-- for Admin -->
                        <p>Eres un administrador, ¿qué haces aquí?.</p>
                    <?php elseif ($isPremium): ?> <!-- for premium -->
                        <form method="POST" action="../php/cancelarSuscripcion.php">
                            <button type="submit" class="btn btn-danger">Cancelar Suscripción</button>
                        </form>
                    <?php else: ?> <!-- for normal User -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#suscripcionModal">Suscribirse por 9.99€/mes</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Suscribe modal -->
<div class="modal fade" id="suscripcionModal" tabindex="-1" aria-labelledby="suscripcionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="suscripcionModalLabel">Suscripción Premium</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="suscripcionForm" method="POST" action="../php/procesarSuscripcion.php">
                    <div class="mb-3">
                        <label for="numero_tarjeta" class="form-label">Número de Tarjeta (16 dígitos)</label>
                        <input type="text" class="form-control" id="numero_tarjeta" name="numero_tarjeta" required pattern="\d{16}" maxlength="16">
                    </div>
                    <div class="mb-3">
                        <label for="nombre_tarjeta" class="form-label">Nombre en la Tarjeta</label>
                        <input type="text" class="form-control" id="nombre_tarjeta" name="nombre_tarjeta" required>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_expiracion" class="form-label">Fecha de Expiración</label>
                        <input type="date" class="form-control" id="fecha_expiracion" name="fecha_expiracion" required>
                    </div>
                    <div class="mb-3">
                        <label for="cvv" class="form-label">CVV (3 dígitos)</label>
                        <input type="text" class="form-control" id="cvv" name="cvv" required pattern="\d{3}" maxlength="3">
                    </div>
                    <button type="submit" class="btn btn-primary">Procesar Pago</button>
                </form>
            </div>
        </div>
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

<!-- Bootstrap JS y Popper.js -->
<script src="../libraries/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
