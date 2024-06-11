<?php
session_start();
require_once "database.php";

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_email'])) {
    header("Location: ../forms/login.html");
    exit;
}

// Obtener el email del usuario de la sesión
$email = $_SESSION['usuario_email'];

// Obtener los datos enviados por el formulario
$nombre_usuario = $_POST['nombre_usuario'];
$bio = $_POST['bio'];
$pais = $_POST['pais'];

// Ruta de la carpeta de subida
$uploadDir = '../media/profilePics/';

// Crear la carpeta si no existe
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        die('Failed to create folders...');
    }
}

// Manejo de la imagen subida
if (isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] == UPLOAD_ERR_OK) {
    $imagen_tmp = $_FILES['fotoPerfil']['tmp_name'];
    $imagen_nombre = basename($_FILES['fotoPerfil']['name']);
    $imagen_ext = strtolower(pathinfo($imagen_nombre, PATHINFO_EXTENSION));

    $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($imagen_ext, $extensiones_permitidas)) {
        $nuevo_nombre_imagen = uniqid() . '.' . $imagen_ext;
        $ruta_destino = $uploadDir . $nuevo_nombre_imagen;
        if (move_uploaded_file($imagen_tmp, $ruta_destino)) {
            // Construir la ruta relativa para almacenar en la base de datos
            $ruta_relativa = '../../media/profilePics/' . $nuevo_nombre_imagen;
            // Actualizar la ruta de la imagen en la base de datos
            $sql = "UPDATE Usuarios SET fotoPerfil = ? WHERE email = ?";
            $stmt = $pdo->prepare($sql);
            if (!$stmt->execute([$ruta_relativa, $email])) {
                die('Error updating profile picture.');
            }
        } else {
            die('Error moving uploaded file.');
        }
    } else {
        die('Invalid file extension.');
    }
}

// Actualizar los demás campos del usuario en la base de datos
$sql = "UPDATE Usuarios SET nombre_usuario = ?, bio = ?, pais = ? WHERE email = ?";
$stmt = $pdo->prepare($sql);
if ($stmt->execute([$nombre_usuario, $bio, $pais, $email])) {
    echo "Perfil actualizado con éxito";
} else {
    echo "Error al actualizar el perfil";
}
?>