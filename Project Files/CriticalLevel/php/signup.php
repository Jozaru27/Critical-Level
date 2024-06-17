<?php
require_once "database.php";

// Verify if form ahs been sent
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Gather form data
    $nombre = $_POST["nombre_usuario"];
    $email = $_POST["email"];
    $contraseña = $_POST["contraseña"];
    $contraseñaConfirmar = $_POST["contraseñaConfirmar"];

    // Verify if the passwords are correct
    if ($contraseña !== $contraseñaConfirmar) {
        echo "Las contraseñas no coinciden.";
        header("Location: ../html/forms/signup.html");

    }

    // Creates password hash
    $contraseñaHash = password_hash($contraseña, PASSWORD_DEFAULT);

    // Adds a random profile pciture
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

    // Generates random user code
    $userCode = uniqid();

    // Inserts data in DB
    $sql = "INSERT INTO usuarios (email, nombre_usuario, contraseña, idROL, bio, fotoPerfil, fechaCreacionCuenta, pais, ultimaActividad, userCode) 
            VALUES (?, ?, ?, 2, '', ?, NOW(), '', NOW(), ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email, $nombre, $contraseñaHash, $fotoPerfil, $userCode]);

    // Heads user to another page
    header("Location: ../html/index.php");
    exit;
}
?>
