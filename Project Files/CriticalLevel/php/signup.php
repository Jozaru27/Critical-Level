<?php
// Incluir el archivo de conexión a la base de datos
require_once "database.php";

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar datos del formulario
    $nombre = $_POST["nombre_usuario"];
    $email = $_POST["email"];
    $contraseña = $_POST["contraseña"];

    // Generar el hash de la contraseña
    $contraseñaHash = password_hash($contraseña, PASSWORD_DEFAULT);

    // Generar una foto de perfil aleatoria
    $defaultPhotos = [
        '../../media/defaultProfilePictures/person1.jpg',
        '../../media/defaultProfilePictures/person2.jpg',
        '../../media/defaultProfilePictures/person3.jpg',
        '../../media/defaultProfilePictures/person4.jpg',
        '../../media/defaultProfilePictures/person5.jpg',
        '../../media/defaultProfilePictures/person6.jpg',
        '../../media/defaultProfilePictures/person7.jpg',
        '../../media/defaultProfilePictures/person8.jpg',
        '../../media/defaultProfilePictures/person9.jpg',
        '../../media/defaultProfilePictures/person10.jpg'
    ];

    $fotoPerfil = $defaultPhotos[array_rand($defaultPhotos)];

    // Generar un código único para el usuario
    $userCode = uniqid();

    // Insertar datos en la tabla de usuarios, incluyendo el userCode
    $sql = "INSERT INTO Usuarios (email, nombre_usuario, contraseña, idROL, bio, fotoPerfil, fechaCreacionCuenta, pais, ultimaActividad, userCode) 
            VALUES (?, ?, ?, 2, '', ?, NOW(), '', NOW(), ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email, $nombre, $contraseñaHash, $fotoPerfil, $userCode]);

    // Redirigir a la página de inicio u otra página
    header("Location: ../html/index.php");
    exit;
}
?>
