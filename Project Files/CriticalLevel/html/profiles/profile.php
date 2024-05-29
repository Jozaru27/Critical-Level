<?php
session_start();
require_once "../../php/database.php";

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_email'])) {
    // Si no ha iniciado sesión, redirigir al formulario de inicio de sesión
    header("Location: ../forms/login.html");
    exit;
}

// Obtener el email del usuario de la sesión
$email = $_SESSION['usuario_email'];

// Buscar los datos del usuario en la base de datos
$sql = "SELECT * FROM Usuarios WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar si se encontró el usuario
if (!$usuario) {
    // Si no se encontró el usuario, mostrar un mensaje de error
    echo "Usuario no encontrado";
    exit;
}
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/MainPageStyle.css">
    <title>Perfil de Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .profile-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }
        .profile-container img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin-bottom: 20px;
        }
        .profile-container h1 {
            margin: 0 0 10px;
        }
        .profile-container p {
            margin: 5px 0;
        }
        .profile-container .bio {
            margin: 20px 0;
            font-style: italic;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <img src="<?php echo htmlspecialchars($usuario['fotoPerfil']); ?>" alt="Foto de perfil">
        <h1><?php echo htmlspecialchars($usuario['nombre_usuario']); ?></h1>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
        <p class="bio"><strong>Bio:</strong> <?php echo htmlspecialchars($usuario['bio']); ?></p>
        <p><strong>Miembro desde:</strong> <?php echo htmlspecialchars($usuario['fechaCreacionCuenta']); ?></p>
        <p><strong>Juegos reseñados:</strong> <?php echo htmlspecialchars($usuario['numReseñasCreadas']); ?></p>
        <p><strong>País:</strong> <?php echo htmlspecialchars($usuario['pais']); ?></p>
        <p><strong>Última Actividad:</strong> <?php echo htmlspecialchars($usuario['ultimaActividad']); ?></p>
    </div>
</body>
</html>