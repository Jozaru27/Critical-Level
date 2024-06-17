<?php
// PHP FILE - GET TOP USERS TO DISPLAY IN THE INDEX PAGE
require_once "database.php";

// Obtains the most 5 active users (by checking total of reviews)
$stmt = $pdo->prepare("
    SELECT usuarios.userCode, usuarios.nombre_usuario, usuarios.fotoPerfil, COUNT(reseñas.id) AS total_reseñas 
    FROM usuarios 
    JOIN reseñas ON usuarios.email = reseñas.email 
    GROUP BY usuarios.userCode, usuarios.nombre_usuario, usuarios.fotoPerfil 
    ORDER BY total_reseñas DESC 
    LIMIT 5
");

$stmt->execute();
$topUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// For each user obtained, loads its profile picture, the name (linked to the profile) and a count of how many games they have reviewed
foreach ($topUsers as $user) {
    $profilePic = $user['fotoPerfil'];
    // Checks if its currently in a subdirectory (since the paths to images from the profiles are stored in the database)
    if (strpos($_SERVER['REQUEST_URI'], '/') !== false) {
        // Deletes the first level so the image path is loaded correctly
        $profilePic = substr($profilePic, 3);
    }

    // Displays the information
    echo "<div class='user'>";
    echo "<img src='" . htmlspecialchars($profilePic) . "' alt='Foto de " . htmlspecialchars($user['nombre_usuario']) . "' class='profile-picture' />";
    echo "<p><strong><a href='profiles/userProfile.php?code=" . htmlspecialchars($user['userCode']) . "'>" . htmlspecialchars($user['nombre_usuario']) . "</a></strong></p>";
    echo "<p>Reseñas: " . htmlspecialchars($user['total_reseñas']) . "</p>";
    echo "</div>";
}
?>
