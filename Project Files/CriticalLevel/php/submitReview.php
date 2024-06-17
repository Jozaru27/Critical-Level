<?php
session_start();
require_once "database.php"; // Incluir archivo de conexión a la base de datos

// Verify if the user has logged in
if (!isset($_SESSION['usuario_email'])) {
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para enviar una reseña.']);
    exit;
}

// Verify if the form data has been sent
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idAPI'], $_POST['rating'], $_POST['text'])) {
    $idAPI = $_POST['idAPI'];
    $email = $_SESSION['usuario_email'];
    $valoracion = (int)$_POST['rating'];
    $texto = $_POST['text'];

    // Verify if the user has sent a review
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reseñas WHERE email = ? AND idAPI = ?");
    $stmt->execute([$email, $idAPI]);
    $reseñaExistente = $stmt->fetchColumn();

    if ($reseñaExistente > 0) {
        // if it exists, update it
        $stmt = $pdo->prepare("UPDATE reseñas SET valoración = ?, texto = ?, fecha_creación = NOW() WHERE email = ? AND idAPI = ?");
        $stmt->execute([$valoracion, $texto, $email, $idAPI]);
    } else {
        // Inserts new review if it doesnt exist
        $stmt = $pdo->prepare("INSERT INTO reseñas (email, idAPI, valoración, texto, fecha_creación) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$email, $idAPI, $valoracion, $texto]);
    }

    // Update last activty
    $stmt = $pdo->prepare("UPDATE usuarios SET ultimaActividad = NOW() WHERE email = ?");
    $stmt->execute([$email]);


    header("Location: ../html/profiles/game.php?id=" . $idAPI);
    exit;
} else {
    echo json_encode(['success' => false, 'message' => 'Datos del formulario incompletos.']);
    exit;
}
?>
