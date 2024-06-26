<?php
// Database Credentials - Some are left empty to the public for security reasons
$servername = "viaduct.proxy.rlwy.net"; 
$username = "root"; 
$password = "";  
$dbname = "railway"; 
$port = "36410";

// Api key
$apiKey = "";

// If the connection is successful, it will display so, if not, it shall show a denial message
try {
    $dsn = "mysql:host=$servername;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $username, $password);
    // Establecer el modo de error de PDO a excepción
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
