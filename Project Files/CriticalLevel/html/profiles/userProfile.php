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
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/MainPageStyle.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1b2838;
            color: #c7d5e0;
            margin: 0;
            padding: 0;
        }
        .profile-container {
            max-width: 900px;
            margin: 20px auto;
            background: #2a475e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        .profile-header {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #394b59;
            padding-bottom: 20px;
        }
        .profile-header img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin-right: 20px;
            border: 5px solid #4a90e2;
        }
        .profile-header h1 {
            margin: 0;
        }
        .profile-info {
            margin-top: 20px;
        }
        .profile-info p {
            margin: 10px 0;
        }
        .edit-button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #4a90e2;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .edit-button:hover {
            background-color: #357abd;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 10px;
        }
        .modal-content input, .modal-content textarea, .modal-content select {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .modal-content button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #4a90e2;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .modal-content button:hover {
            background-color: #357abd;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover, .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
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
                echo "<div>";
                echo "<p><strong>Juego:</strong> <a href='http://criticallevel.myddns.me/CriticalLevel/html/profiles/game.php?id=" . $reseña['idAPI'] . "'>" . htmlspecialchars($juegos[$reseña['idAPI']]) . "</a></p>";
                echo "<p><strong>Valoración:</strong> " . htmlspecialchars($reseña['valoración']) . "</p>";
                echo "<p><strong>Texto:</strong> " . nl2br(htmlspecialchars($reseña['texto'])) . "</p>";
                echo "<p><em>Fecha de Creación: " . htmlspecialchars($reseña['fecha_creación']) . "</em></p>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
</body>
</html>
