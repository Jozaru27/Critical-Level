<?php
session_start();
require_once "database.php";

// Verify if the user has logged in
if (!isset($_SESSION['usuario_email'])) {
    header("Location: ../forms/login.html");
    exit;
}

// Obtains email from the session
$email = $_SESSION['usuario_email'];

// Obtains data sent in form
$nombre_usuario = $_POST['nombre_usuario'];
$bio = $_POST['bio'];
$pais = $_POST['pais'];

// Upload directory path (to correct from DB)
$uploadDir = '../media/profilePics/';

// Creates folder if it doesnt exist
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        die('Failed to create folders...');
    }
}

// Handle how to upload image
if (isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] == UPLOAD_ERR_OK) {
    $imagen_tmp = $_FILES['fotoPerfil']['tmp_name'];
    $imagen_nombre = basename($_FILES['fotoPerfil']['name']);
    $imagen_ext = strtolower(pathinfo($imagen_nombre, PATHINFO_EXTENSION));

    $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($imagen_ext, $extensiones_permitidas)) {
        $nuevo_nombre_imagen = uniqid() . '.' . $imagen_ext;
        $ruta_destino = $uploadDir . $nuevo_nombre_imagen;
        if (move_uploaded_file($imagen_tmp, $ruta_destino)) {
            // Builds path
            $ruta_relativa = '../../media/profilePics/' . $nuevo_nombre_imagen;
            // Updates image path
            $sql = "UPDATE usuarios SET fotoPerfil = ? WHERE email = ?";
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

// Update other fields in the DB
$sql = "UPDATE usuarios SET nombre_usuario = ?, bio = ?, pais = ? WHERE email = ?";
$stmt = $pdo->prepare($sql);
if ($stmt->execute([$nombre_usuario, $bio, $pais, $email])) {
    echo "Perfil actualizado con éxito";
} else {
    echo "Error al actualizar el perfil";
}
?>