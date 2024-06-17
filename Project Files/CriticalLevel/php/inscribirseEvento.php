<?php
session_start();
require_once "database.php";

// Check if user is logged in
if (!isset($_SESSION['usuario_email'])) {
    die("Acceso denegado: No se encontró email en la sesión.");
}

$email = $_SESSION['usuario_email'];
$evento_id = $_POST['evento_id'];

// Verify if user is premium
$stmt = $pdo->prepare("SELECT idROL FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$userRole = $stmt->fetchColumn();

if ($userRole != 3) {
    die("Debes ser usuario premium para inscribirte en los eventos.");
}

// Add user to the event
$stmt = $pdo->prepare("INSERT INTO usuarios_eventos (email, evento_id) VALUES (?, ?)");
$resultado = $stmt->execute([$email, $evento_id]);

if ($resultado) {
    echo "Inscripción exitosa.";
} else {
    echo "Error al inscribirse en el evento.";
}

header("Location: ../html/eventos.php");
exit();

?>
