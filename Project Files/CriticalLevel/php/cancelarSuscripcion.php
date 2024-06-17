<?php
session_start();
require_once "database.php";

if (!isset($_SESSION['usuario_email'])) {
    die("Acceso denegado");
}

$email = $_SESSION['usuario_email'];

// Verify if the user is premium
$stmt = $pdo->prepare("SELECT idROL FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$userRole = $stmt->fetchColumn();

if ($userRole == 3) {
    // Revert role to 2 (normal user)
    $stmt = $pdo->prepare("UPDATE usuarios SET idROL = 2 WHERE email = ?");
    $stmt->execute([$email]);

    header("Location: ../html/premium.php");
    exit();
} else {
    die("No tienes una suscripción activa.");
}
?>
