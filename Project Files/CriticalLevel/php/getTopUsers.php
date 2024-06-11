<?php
require_once "database.php";

// Obtener los 5 usuarios más activos (con más reseñas)
$stmt = $pdo->prepare("
    SELECT Usuarios.userCode, Usuarios.nombre_usuario, Usuarios.fotoPerfil, COUNT(Reseñas.id) AS total_reseñas 
    FROM Usuarios 
    JOIN Reseñas ON Usuarios.email = Reseñas.email 
    GROUP BY Usuarios.userCode, Usuarios.nombre_usuario, Usuarios.fotoPerfil 
    ORDER BY total_reseñas DESC 
    LIMIT 5
");
$stmt->execute();
$topUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($topUsers as $user) {
    $profilePic = $user['fotoPerfil'];
    // Verificar si la página actual está en un subdirectorio
    if (strpos($_SERVER['REQUEST_URI'], '/') !== false) {
        // Eliminar el primer nivel del inicio de la ruta
        $profilePic = substr($profilePic, 3);
    }
    echo "<div class='user'>";
    echo "<img src='" . htmlspecialchars($profilePic) . "' alt='Foto de " . htmlspecialchars($user['nombre_usuario']) . "' class='profile-picture' />";
    echo "<p><strong><a href='profiles/userProfile.php?code=" . htmlspecialchars($user['userCode']) . "'>" . htmlspecialchars($user['nombre_usuario']) . "</a></strong></p>";
    echo "<p>Reseñas: " . htmlspecialchars($user['total_reseñas']) . "</p>";
    echo "</div>";
}
?>
