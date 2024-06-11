<?php
session_start();
require_once "database.php"; // Incluir archivo de conexión a la base de datos

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_email'])) {
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para eliminar una reseña.']);
    exit;
}

// Verificar si se han enviado todos los datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idAPI'])) {
    $idAPI = $_POST['idAPI'];
    $email = $_SESSION['usuario_email'];

    // Eliminar la reseña de la base de datos
    $stmt = $pdo->prepare("DELETE FROM Reseñas WHERE email = ? AND idAPI = ?");
    $stmt->execute([$email, $idAPI]);

    $stmt = $pdo->prepare("UPDATE Usuarios SET ultimaActividad = NOW() WHERE email = ?");
    $stmt->execute([$email]);

    header("Location: ../html/profiles/game.php?id=" . $idAPI);
    exit;
} else {
    echo json_encode(['success' => false, 'message' => 'Datos del formulario incompletos.']);
    exit;
}
?>
