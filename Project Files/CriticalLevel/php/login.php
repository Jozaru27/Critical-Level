<?php
// Incluir el archivo de conexión a la base de datos
require_once "database.php";

// Variable para almacenar mensajes de error
$error = "";

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar datos del formulario
    $email = $_POST["email"];
    $contraseña = $_POST["contraseña"];

    // Buscar el usuario en la base de datos por su email
    $sql = "SELECT * FROM Usuarios WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si se encontró un usuario con ese email
    if ($usuario) {
        // Verificar la contraseña
        if (password_verify($contraseña, $usuario['contraseña'])) {
            // Iniciar sesión (aquí puedes implementar tu lógica de inicio de sesión)
            session_start();
            $_SESSION['usuario_email'] = $usuario['email']; // Guardar el email como el ID
            $_SESSION['usuario_name'] = $usuario['nombre_usuario']; // Guardar el email como el ID
            $_SESSION['usuario_rol'] = $usuario['idROL']; // Guardar el email como el ID
            // Redirigir a la página de inicio
            header("Location: ../html/index.php");
            exit;
        } else {
            // Contraseña incorrecta
            $error = "Contraseña incorrecta";
            header("Location: ../html/forms/login.html");
        }
    } else {
        // Usuario no encontrado
        $error = "Usuario no encontrado";
        header("Location: ../html/forms/login.html");
    }
}
?>
