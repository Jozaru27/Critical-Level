<?php
require_once "database.php";
// Store error messages
$error = "";

// Checks if form has been sent
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from form
    $email = $_POST["email"];
    $contraseña = $_POST["contraseña"];

    // Search user in DB via email
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // If user is found
    if ($usuario) {
        // Verify password
        if (password_verify($contraseña, $usuario['contraseña'])) {
            session_start();
            $_SESSION['usuario_email'] = $usuario['email']; 
            $_SESSION['usuario_name'] = $usuario['nombre_usuario']; 
            $_SESSION['usuario_rol'] = $usuario['idROL']; 
            header("Location: ../html/index.php");
            exit;
        } else {
            // Incorrect Password
            $error = "Contraseña incorrecta";
            header("Location: ../html/forms/login.html");
        }
    } else {
        // User not found
        $error = "Usuario no encontrado";
        header("Location: ../html/forms/login.html");
    }
}
?>
