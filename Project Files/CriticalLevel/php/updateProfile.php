<?php
session_start();
require_once "database.php";

if (!isset($_SESSION['usuario_email'])) {
    echo "No autorizado";
    exit;
}

$email = $_SESSION['usuario_email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre_usuario"];
    $bio = $_POST["bio"];
    $pais = $_POST["pais"];

    $sql = "UPDATE Usuarios SET nombre_usuario = ?, bio = ?, pais = ?, ultimaActividad = NOW() WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$nombre, $bio, $pais, $email])) {
        echo "Actualización exitosa";
    } else {
        echo "Error en la actualización";
    }
}
?>