<?php
session_start();
require_once "../../php/database.php"; 

if (!isset($_GET['code'])) {
    die("Código de usuario no especificado.");
}

$userCode = $_GET['code'];

$juegos = [];

// Obtians user data from the DB using the secret userCode
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE userCode = ?");
$stmt->execute([$userCode]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    die("Usuario no encontrado.");
}

// Obtains user reviews from the DB
$stmt = $pdo->prepare("SELECT reseñas.*, usuarios.nombre_usuario 
                       FROM reseñas 
                       JOIN usuarios ON reseñas.email = usuarios.email 
                       WHERE reseñas.email = ?");
$stmt->execute([$usuario['email']]);
$reseñas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Sets average score for the reviews
$totalValoraciones = 0;
$numeroReseñas = count($reseñas);
foreach ($reseñas as $reseña) {
    $totalValoraciones += $reseña['valoración'];
}
$valoracionMedia = $numeroReseñas > 0 ? $totalValoraciones / $numeroReseñas : "No hay valoraciones";

$rol = $usuario['idROL'];

$badgeText = '';
$badgeClass = '';

if ($rol == 1) {
    $badgeText = 'Admin';
    $badgeClass = 'text-bg-danger'; // Red for Admin
} elseif ($rol == 2) {
    $badgeText = 'Usuario';
    $badgeClass = 'text-bg-primary'; // Blue for User
} elseif ($rol == 3) {
    $badgeText = 'Premium';
    $badgeClass = 'text-bg-warning'; // Yellow for Premium
}

// Array to store game IDS
$idJuegos = [];
foreach ($reseñas as $reseña) {
    $idJuegos[] = $reseña['idAPI'];
}

// Api call to obtain game title names
$apiUrl = "https://api.rawg.io/api/games?key=" . $apiKey . "&ids=" . implode(',', $idJuegos);
$response = file_get_contents($apiUrl);
$data = json_decode($response, true);

// Creates associative array from idAPI to title
$juegos = [];
foreach ($data['results'] as $juego) {
    $juegos[$juego['id']] = $juego['name'];
}

?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/MainPageStyle.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/profilesStyle/userProfile.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <script src="../../js/script.js"></script>
    <title>Perfil de Usuario</title>
</head>
<body>

    <!-- https://wweb.dev/resources/navigation-generator - https://freefrontend.com/css-menu/-->
    <nav class="menu-container">
      <!-- Burger Menu -->
      <input type="checkbox" aria-label="Toggle menu" />
      <span></span>
      <span></span>
      <span></span>
    
      <!-- Logo -->
      <a href="../../../index.php" class="menu-logo">
        <img src="../../media/CL_Logo_Blue_Hex/CL_Logo_HD_White.png" alt="Landing Page"/>
      </a>
    
      <!-- Navbar Menu -->
      <div class="menu">
        <ul>
            <li>
                <a href="../index.php">
                    Inicio
                </a>
            </li>
            <li>
                <a href="../games.php">
                    Juegos
                </a>
            </li>
            <li>
                <a href="../eventos.php">
                    Eventos
                </a>
            </li>
            <li>
                <a href="../premium.php">
                    Premium
                </a>
            </li>
        </ul>
        <ul>
            <?php if (isset($_SESSION['usuario_email'])): ?>
                <li>
                    <a href="profile.php">
                        Perfil
                    </a>
                </li>
                <li>
                    <a href="../../php/logout.php">
                        Cerrar Sesión
                    </a>
                </li>
            <?php else: ?>
                <li>
                    <a href="../forms/signup.html">
                        Registro
                    </a>
                </li>
                <li>
                    <a href="../forms/login.html">
                        Iniciar Sesión
                    </a>
                </li>
            <?php endif; ?>
        </ul>
      </div>
    </nav> 

    <div class="profile-container">
        <div class="profile-header">
            <img src="<?php echo htmlspecialchars($usuario['fotoPerfil']); ?>" alt="Foto de perfil">
            <h1>    
                <?php echo htmlspecialchars($usuario['nombre_usuario']); ?>
                <h5>&nbsp;&nbsp;<span class="badge <?php echo $badgeClass; ?>"><?php echo $badgeText;?></span></h5>
            </h1>
        </div>
        <div class="profile-info">
            <p class="bio"><strong>Bio:</strong> <?php echo htmlspecialchars($usuario['bio']); ?></p>
            <p><strong>Miembro desde:</strong> <?php echo date('Y-m-d', strtotime($usuario['fechaCreacionCuenta'])); ?></p>
            <p><strong>Juegos reseñados:</strong> <?php echo htmlspecialchars($numeroReseñas); ?></p>
            <p><strong>País:</strong> <?php echo htmlspecialchars($usuario['pais']); ?></p>
            <p><strong>Última Actividad:</strong> <?php echo htmlspecialchars($usuario['ultimaActividad']); ?></p>
        </div>

        <hr>
        <h2>Reseñas del Usuario</h2>

        <div id="reseñasContainer">
            <?php
            // Shows users reviews
            foreach ($reseñas as $reseña) {
                echo "<div class='review-container'>";
                echo "<p><strong>Juego:</strong> <a href='http://criticallevel.myddns.me/CriticalLevel/html/profiles/game.php?id=" . $reseña['idAPI'] . "'>" . htmlspecialchars($juegos[$reseña['idAPI']]) . "</a></p>";
                
                // Shows score stars
                echo "<p><strong>Valoración:</strong> ";
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $reseña['valoración']) {
                        echo "<i class='bi bi-star-fill' style='color: #ffb400;'></i>";
                    } else {
                        echo "<i class='bi bi-star' style='color: #ffb400;'></i>";
                    }
                }
                echo "</p>";

                echo "<p><strong>Texto:</strong> " . nl2br(htmlspecialchars($reseña['texto'])) . "</p>";
                echo "<p><em>Fecha de Creación: " . htmlspecialchars($reseña['fecha_creación']) . "</em></p>";
                echo "</div>";
            }
            ?>
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
                <li><a href="../../../index.html">Landing Page</a></li>
                <li><a href="../index.php">Inicio</a></li>
                <li><a href="../games.php">Juegos</a></li>
                <li><a href="../eventos.html">Eventos</a></li>
                <li><a href="../premium.html">Premium</a></li>
                <!-- <li><a href="">Yuju [NULL]</a></li> -->
            </ul>
            </div>

            <div class="col-xs-6 col-md-3">
            <h6>Legal</h6>
            <ul class="footer-links">
                <li><a href="../legal/aboutus.html">Sobre Nosotros</a></li>
                <li><a href="../forms/contactus.html">Contáctanos</a></li>
                <!-- <li><a href="">Contribuir [NULL]</a></li> -->
                <li><a href="../legal/privacypolicy.html">Política de Privacidad</a></li>
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
