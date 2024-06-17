<?php
session_start();
require_once "database.php";

if (!isset($_SESSION['usuario_email'])) {
    die("Acceso denegado");
}

$email = $_SESSION['usuario_email'];
$tarjeta_numero = $_POST['numero_tarjeta'];
$tarjeta_nombre = $_POST['nombre_tarjeta'];
$tarjeta_fecha = $_POST['fecha_expiracion'];
$tarjeta_cvv = $_POST['cvv'];

// Validate credit card details
$numero_valido = preg_match('/^\d{16}$/', $tarjeta_numero);
$fecha_valida = strtotime($tarjeta_fecha) > time();
$cvv_valido = preg_match('/^\d{3}$/', $tarjeta_cvv);

// Proceed with payment if all is good
if ($numero_valido && $fecha_valida && $cvv_valido) {
    // Change user to premium
    $stmt = $pdo->prepare("UPDATE usuarios SET idROL = 3 WHERE email = ?");
    $stmt->execute([$email]);

    header("Location: ../html/premium.php");
    exit();
} else {
    die("Error en el procesamiento del pago. Verifique los datos de la tarjeta.");
}
?>
