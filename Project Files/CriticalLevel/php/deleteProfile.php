<?php
session_start();
require_once "database.php";

if (!isset($_SESSION['usuario_email'])) {
    echo "No autorizado";
    exit;
}

$email = $_SESSION['usuario_email'];

$sql = "DELETE FROM Usuarios WHERE email = ?";
$stmt = $pdo->prepare($sql);
if ($stmt->execute([$email])) {
    session_destroy();
    echo "Perfil eliminado";
} else {
    echo "Error al eliminar el perfil";
}
?>
