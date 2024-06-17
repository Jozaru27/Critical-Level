<?php
session_start();
require_once "database.php";

// Verify if the user has logged in
if (!isset($_SESSION['usuario_email'])) {
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para eliminar una reseña.']);
    exit;
}

// Verify if all the data has been sent
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idAPI'])) {
    $idAPI = $_POST['idAPI'];
    $email = $_SESSION['usuario_email'];

    // Deletes review from the DB
    $stmt = $pdo->prepare("DELETE FROM reseñas WHERE email = ? AND idAPI = ?");
    $stmt->execute([$email, $idAPI]);

    $stmt = $pdo->prepare("UPDATE usuarios SET ultimaActividad = NOW() WHERE email = ?");
    $stmt->execute([$email]);

    header("Location: ../html/profiles/game.php?id=" . $idAPI);
    exit;
} else {
    echo json_encode(['success' => false, 'message' => 'Datos del formulario incompletos.']);
    exit;
}
?>
