<?php
    session_start();
    session_unset();
    session_destroy(); // Destroy session
    header("Location: ../html/index.php");
    exit;
?>
