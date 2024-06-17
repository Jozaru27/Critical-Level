<?php
session_start();
require_once "database.php";

// Checks if user is logged in
if (!isset($_SESSION['usuario_email'])) {
    echo "No autorizado";
    exit;
}

$email = $_SESSION['usuario_email'];

// Deletes the user from the DB
$sql = "DELETE FROM usuarios WHERE email = ?";
$stmt = $pdo->prepare($sql);
if ($stmt->execute([$email])) {
    session_destroy();
    echo "Perfil eliminado";
} else {
    echo "Error al eliminar el perfil";
}
?>
