<?php
session_start();
require_once "database.php";

// Obtain form data
$nombre = $_POST['nombre'];
$fecha = $_POST['fecha'];
$lugar = $_POST['lugar'];

// Insert event into DB
$stmt = $pdo->prepare("INSERT INTO eventos (nombre, fecha, lugar) VALUES (?, ?, ?)");
$stmt->execute([$nombre, $fecha, $lugar]);

header("Location: ../html/eventos.php");
exit();
?>
