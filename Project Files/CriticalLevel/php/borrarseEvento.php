<?php
session_start();
require_once "database.php";

// Check if the user is logged in
if (!isset($_SESSION['usuario_email'])) {
    die("Acceso denegado");
}

$email = $_SESSION['usuario_email'];
$evento_id = $_POST['evento_id'];

// Remove the user's registration for the event
$stmt = $pdo->prepare("DELETE FROM usuarios_eventos WHERE email = ? AND evento_id = ?");
$stmt->execute([$email, $evento_id]);

// Redirect to the events page
header("Location: ../html/eventos.php");
exit();
?>
