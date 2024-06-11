<?php
session_start();
require_once "../../php/database.php"; // Incluir archivo de conexión a la base de datos

if (!isset($_SESSION['usuario_email'])) {
    header("Location: ../forms/login.html");
    exit;
}

if (!isset($_GET['id'])) {
    die("ID del juego no especificado.");
}

$gameId = $_GET['id'];
$apiKey = '3493dbf3242341fb9284060b456efb79';
$gameDetailsUrl = "https://api.rawg.io/api/games/{$gameId}?key={$apiKey}";

// Obtener detalles del juego desde la API
$gameDetails = @file_get_contents($gameDetailsUrl); // Usar @ para suprimir errores y manejarlos manualmente
if ($gameDetails === FALSE) {
    die("No se pudo obtener la información del juego. Verifica tu API key.");
}
$game = json_decode($gameDetails, true);

// Obtener reseñas del juego desde la base de datos
$stmt = $pdo->prepare("SELECT Reseñas.*, Usuarios.nombre_usuario, Usuarios.userCode 
                       FROM Reseñas 
                       JOIN Usuarios ON Reseñas.email = Usuarios.email 
                       WHERE Reseñas.idAPI = ?");
$stmt->execute([$gameId]);
$reseñas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verificar si el usuario ya ha enviado una reseña para este juego
$userHasReseña = false;
$existingReseña = null;
if (isset($_SESSION['usuario_email'])) {
    $stmt = $pdo->prepare("SELECT * FROM Reseñas WHERE idAPI = ? AND email = ?");
    $stmt->execute([$gameId, $_SESSION['usuario_email']]);
    $existingReseña = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($existingReseña) {
        $userHasReseña = true;
    }
}

// Calcular la media de las valoraciones
$totalValoraciones = 0;
$numeroReseñas = count($reseñas);
foreach ($reseñas as $reseña) {
    $totalValoraciones += $reseña['valoración'];
}
$valoracionMedia = $numeroReseñas > 0 ? $totalValoraciones / $numeroReseñas : "No hay valoraciones";

// Función para mostrar estrellas
function mostrarEstrellas($valoracion) {
    $estrellas = '';
    for ($i = 0; $i < 5; $i++) {
        if ($i < $valoracion) {
            $estrellas .= '★';
        } else {
            $estrellas .= '☆';
        }
    }
    return $estrellas;
}

?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($game['name']); ?> - Critical Level</title>
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/MainPageStyle.css">
    <link rel="stylesheet" href="../../css/GamePageStyle.css">
    <link rel="stylesheet" href="../../css/profilesStyle/gameStyle.css">
    <link rel="icon" type="image/x-icon" href="../media/CL_Logo_Blue_Hex/favicon.ico">
    <link href="../libraries/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="../libraries/bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
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

    <div class="container">
        <div class="game-details">
            <h1><?php echo htmlspecialchars($game['name']); ?></h1>
            <img class="imgGame" src="<?php echo $game['background_image']; ?>" alt="<?php echo htmlspecialchars($game['name']); ?>">
            <p>Released: <?php echo htmlspecialchars($game['released']); ?></p>
            <p>Valoración Media: <span class="star-rating"><?php echo mostrarEstrellas(round($valoracionMedia)); ?></span></p>
        </div>
        <br><br>

        <!-- Modal para escribir/modificar reseña -->
        <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="reviewModalLabel"><?php echo $userHasReseña ? "Modificar Reseña" : "Escribir Reseña"; ?></h2>
                    </div>
                    <div class="modal-body">
                        <form action="../../php/submitReview.php" method="POST">
                            <input type="hidden" name="idAPI" value="<?php echo $gameId; ?>">
                            <div class="mb-3">
                                <label for="reviewRating" class="form-label">Valoración</label><br>
                                <input type="number" class="form-control" id="reviewRating" name="rating" min="1" max="5" required value="<?php echo $userHasReseña ? htmlspecialchars($existingReseña['valoración']) : ''; ?>">
                            </div><br>
                            <div class="mb-3">
                                <label for="reviewText" class="form-label">Contenido de la reseña</label><br>
                                <textarea class="form-control" id="reviewText" name="text" rows="3" required><?php echo $userHasReseña ? htmlspecialchars($existingReseña['texto']) : ''; ?></textarea>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary btn-modify"><?php echo $userHasReseña ? "Confirmar Cambios" : "Enviar Reseña"; ?></button>
                            </div>
                        </form><br>
                        <?php if ($userHasReseña): ?>
                            <form action="../../php/deleteReview.php" method="POST" style="display:inline;">
                                <input type="hidden" name="idAPI" value="<?php echo $gameId; ?>">
                                <button type="submit" class="btn btn-danger btn-delete">Eliminar Reseña</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <br><br>

        <div class="reviews-section">
            <h2>Reseñas</h2>
            <?php foreach ($reseñas as $reseña): ?>
                <div class="review">
                    <div class="row">
                        <div class="col-md-2">
                            <strong>
                                <?php if ($reseña['email'] == $_SESSION['usuario_email']): ?>
                                    <a href="profile.php"><?php echo htmlspecialchars($reseña['nombre_usuario']); ?></a>
                                <?php else: ?>
                                    <a href="userProfile.php?code=<?php echo urlencode($reseña['userCode']); ?>"><?php echo htmlspecialchars($reseña['nombre_usuario']); ?></a>
                                <?php endif; ?>
                            </strong>
                        </div>
                        <div class="col-md-2">
                            <span class="star-rating"><?php echo mostrarEstrellas($reseña['valoración']); ?></span>
                        </div>
                        <div class="col-md-8">
                            <p><?php echo nl2br(htmlspecialchars($reseña['texto'])); ?></p>
                            <p><em>Fecha: <?php echo htmlspecialchars($reseña['fecha_creación']); ?></em></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
