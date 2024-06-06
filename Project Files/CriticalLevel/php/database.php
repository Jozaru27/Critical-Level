<?php
// Database Credentials
$servername = "127.0.0.1"; 
$username = "root"; 
$password = "";  
$dbname = "criticallevel"; 

// Api key
$apiKey = "3493dbf3242341fb9284060b456efb79";

// If the connection is successful, it will display so, if not, it shall show a denial message
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Establecer el modo de error de PDO a excepción
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

?>
