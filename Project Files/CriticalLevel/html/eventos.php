<?php
session_start();
require_once "../php/database.php";

// Verifies if the user has logged in and its role
$userLoggedIn = isset($_SESSION['usuario_email']);
$userRole = null;
$email = null;

if ($userLoggedIn) {
    $email = $_SESSION['usuario_email'];
    $stmt = $pdo->prepare("SELECT idROL FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $userRole = $stmt->fetchColumn();
}

//Obtain all events and number of people going to said event
$stmt = $pdo->prepare("
    SELECT E.id, E.nombre, E.fecha, E.lugar, COUNT(UE.id) AS asistentes
    FROM eventos E
    LEFT JOIN usuarios_eventos UE ON E.id = UE.evento_id
    GROUP BY E.id, E.nombre, E.fecha, E.lugar
    ORDER BY E.fecha ASC
");
$stmt->execute();
$eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="../css/eventosPageStyle.css">
    <!-- Icon -->
    <link rel="icon" type="image/x-icon" href="../media/CL_Logo_Blue_Hex/favicon.ico">
    <!-- Libraries -->
    <link href="../libraries/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="../libraries/bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <!-- Script -->
    <script src="../js/script.js"></script>
    <!-- Main Page -->
    <title>Eventos - Critical Level</title>
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

    <div class="container mt-5 eventContainer">
        <h1 class="text-center">Eventos</h1>

        <?php if ($userLoggedIn && $userRole == 1): ?>
            <!-- Button to open the create event modal -->
            <div class="text-end mb-4">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#crearEventoModal">
                    Crear Evento
                </button>
            </div>
        <?php endif; ?>

        <div id="eventosContainer" class="mt-4">
            <?php foreach ($eventos as $evento): ?>
                <div class="evento">
                    <h3><?php echo htmlspecialchars($evento['nombre']); ?></h3>
                    <p><strong>Fecha:</strong> <?php echo htmlspecialchars($evento['fecha']); ?></p>
                    <p><strong>Lugar:</strong> <?php echo htmlspecialchars($evento['lugar']); ?></p>
                    <p><strong>Asistentes:</strong> <?php echo $evento['asistentes']; ?></p>
                    
                    <?php
                    // Simple check to see if the user is already participating in said event
                    $stmt = $pdo->prepare("SELECT COUNT(id) AS inscrito FROM usuarios_eventos WHERE email = ? AND evento_id = ?");
                    $stmt->execute([$email, $evento['id']]);
                    $inscrito = $stmt->fetchColumn();
                    ?>
                    
                    <!-- Depending if its participating or not, a button shows displaying to delete or to join -->
                    <?php if ($inscrito): ?>
                        <form method="POST" action="../php/borrarseEvento.php">
                            <input type="hidden" name="evento_id" value="<?php echo $evento['id']; ?>">
                            <button type="submit" class="btn btn-danger">Borrarse</button>
                        </form>
                    <?php elseif ($userLoggedIn && $userRole == 3): ?>
                        <form method="POST" action="../php/inscribirseEvento.php">
                            <input type="hidden" name="evento_id" value="<?php echo $evento['id']; ?>">
                            <button type="submit" class="btn btn-primary">Apuntarse</button>
                        </form>
                    <?php elseif ($userLoggedIn && $userRole != 3): ?>
                        <p><em>Debes ser usuario premium para inscribirte en los eventos.</em></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal to create event -->
    <div class="modal fade" id="crearEventoModal" tabindex="-1" aria-labelledby="crearEventoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="crearEventoModalLabel">Crear Evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="crearEventoForm" method="POST" action="../php/crearEvento.php">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Evento</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required>
                        </div>
                        <div class="mb-3">
                            <label for="lugar" class="form-label">Lugar</label>
                            <input type="text" class="form-control" id="lugar" name="lugar" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Crear Evento</button>
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
</body>
</html>
