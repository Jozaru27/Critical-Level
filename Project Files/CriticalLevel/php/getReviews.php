<?php
require_once "database.php";

$order = isset($_GET['order']) ? $_GET['order'] : 'recientes';

switch ($order) {
    case 'alta':
        $orderBy = 'valoración DESC';
        break;
    case 'baja':
        $orderBy = 'valoración ASC';
        break;
    case 'recientes':
    default:
        $orderBy = 'fecha_creación DESC';
        break;
}

$stmt = $pdo->prepare("SELECT Reseñas.*, Usuarios.nombre_usuario, Usuarios.userCode FROM Reseñas JOIN Usuarios ON Reseñas.email = Usuarios.email ORDER BY $orderBy");
$stmt->execute();
$reseñas = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($reseñas as $reseña) {
    echo "<div class='review'>";
    echo "<p><strong><a href='profiles/userProfile.php?code=" . urlencode($reseña['userCode']) . "'>" . htmlspecialchars($reseña['nombre_usuario']) . "</a></strong> valoró <b>un juego</b> con <span class='star-rating'>" . mostrarEstrellas($reseña['valoración']) . "</span></p>";
    echo "<p>" . nl2br(htmlspecialchars($reseña['texto'])) . "</p>";
    echo "<p><em>Fecha: " . htmlspecialchars($reseña['fecha_creación']) . "</em></p>";
    echo "</div>";
}

function mostrarEstrellas($valoracion) {
    $estrellas = '';
    for ($i = 0; $i < 5; $i++) {
        if ($i < $valoracion) {
            $estrellas .= '★';
        } else {
            $estrellas .= '☆';
        }
    }
    return $estrellas;
}
?>
