<?php
    session_start();
    session_unset(); // Destruir todas las variables de sesión
    session_destroy(); // Destruir la sesión
    header("Location: ../html/index.php"); // Redirigir a la página de inicio
    exit;
?>
