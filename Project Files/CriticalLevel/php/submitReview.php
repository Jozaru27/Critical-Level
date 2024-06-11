<?php
session_start();
require_once "database.php"; // Incluir archivo de conexión a la base de datos

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_email'])) {
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para enviar una reseña.']);
    exit;
}

// Verificar si se han enviado todos los datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idAPI'], $_POST['rating'], $_POST['text'])) {
    $idAPI = $_POST['idAPI'];
    $email = $_SESSION['usuario_email'];
    $valoracion = (int)$_POST['rating'];
    $texto = $_POST['text'];

    // Verificar si el usuario ya ha enviado una reseña para este juego
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Reseñas WHERE email = ? AND idAPI = ?");
    $stmt->execute([$email, $idAPI]);
    $reseñaExistente = $stmt->fetchColumn();

    if ($reseñaExistente > 0) {
        // Si ya existe una reseña, actualizarla
        $stmt = $pdo->prepare("UPDATE Reseñas SET valoración = ?, texto = ?, fecha_creación = NOW() WHERE email = ? AND idAPI = ?");
        $stmt->execute([$valoracion, $texto, $email, $idAPI]);
    } else {
        // Insertar la nueva reseña en la base de datos
        $stmt = $pdo->prepare("INSERT INTO Reseñas (email, idAPI, valoración, texto, fecha_creación) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$email, $idAPI, $valoracion, $texto]);
    }

    // Después de actualizar o insertar la reseña en la base de datos
    // Actualizar la columna ultimaActividad del usuario
    $stmt = $pdo->prepare("UPDATE Usuarios SET ultimaActividad = NOW() WHERE email = ?");
    $stmt->execute([$email]);


    header("Location: ../html/profiles/game.php?id=" . $idAPI);
    exit;
} else {
    echo json_encode(['success' => false, 'message' => 'Datos del formulario incompletos.']);
    exit;
}
?>
