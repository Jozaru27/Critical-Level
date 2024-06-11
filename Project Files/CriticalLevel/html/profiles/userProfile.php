<?php
session_start();
require_once "../../php/database.php"; 

if (!isset($_GET['code'])) {
    die("Código de usuario no especificado.");
}

$userCode = $_GET['code'];

$juegos = [];

// Obtener información del usuario desde la base de datos utilizando el userCode
$stmt = $pdo->prepare("SELECT * FROM Usuarios WHERE userCode = ?");
$stmt->execute([$userCode]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    die("Usuario no encontrado.");
}

// Obtener reseñas del usuario desde la base de datos
$stmt = $pdo->prepare("SELECT Reseñas.*, Usuarios.nombre_usuario 
                       FROM Reseñas 
                       JOIN Usuarios ON Reseñas.email = Usuarios.email 
                       WHERE Reseñas.email = ?");
$stmt->execute([$usuario['email']]);
$reseñas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcular la media de las valoraciones
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

// Array para almacenar los IDs de juegos
$idJuegos = [];
foreach ($reseñas as $reseña) {
    $idJuegos[] = $reseña['idAPI'];
}

// Realizar llamada a la API para obtener los títulos de los juegos
$apiUrl = "https://api.rawg.io/api/games?key=" . $apiKey . "&ids=" . implode(',', $idJuegos);
$response = file_get_contents($apiUrl);
$data = json_decode($response, true);

// Crear un array asociativo de idAPI a título
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
      <a href="../../index.html" class="menu-logo">
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
            // Mostrar las reseñas del usuario
            foreach ($reseñas as $reseña) {
                echo "<div class='review-container'>";
                echo "<p><strong>Juego:</strong> <a href='http://criticallevel.myddns.me/CriticalLevel/html/profiles/game.php?id=" . $reseña['idAPI'] . "'>" . htmlspecialchars($juegos[$reseña['idAPI']]) . "</a></p>";
                
                // Mostrar estrellas de valoración
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
</body>
</html>
