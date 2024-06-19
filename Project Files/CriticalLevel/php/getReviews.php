<?php
require_once "database.php";

// Get the order parameter from the URL or default to 'recientes'
$order = isset($_GET['order']) ? $_GET['order'] : 'recientes';

// Determine the SQL ORDER BY clause based on the order parameter
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

// Prepare and execute the SQL query to fetch reviews and user information
$stmt = $pdo->prepare("SELECT reseñas.*, usuarios.nombre_usuario, usuarios.userCode, usuarios.fotoPerfil, reseñas.idAPI 
                       FROM reseñas 
                       JOIN usuarios ON reseñas.email = usuarios.email 
                       ORDER BY $orderBy");
$stmt->execute();
$reseñas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Function to convert a rating into a string of stars
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

// Function to fetch the game name using the game API ID
function fetchGameName($idAPI) {
    $apiKey = '';
    $response = file_get_contents("https://api.rawg.io/api/games/{$idAPI}?key={$apiKey}");
    $data = json_decode($response, true);
    return $data['name'] ?? 'un juego';
}

// Generate the HTML for the reviews
$html = '';
foreach ($reseñas as $reseña) {
    $estrellas = mostrarEstrellas($reseña['valoración']);
    
    // Adjust image path 
    $fotoPerfil = '../media/profilePics/' . basename($reseña['fotoPerfil']);
    
    $gameName = fetchGameName($reseña['idAPI']);
    
    $html .= "
        <div class='review'>
            <p>
                <img src='{$fotoPerfil}' alt='Foto de {$reseña['nombre_usuario']}' class='profile-pic'>
                <strong>
                    <a href='profiles/userProfile.php?code=" . urlencode($reseña['userCode']) . "'>{$reseña['nombre_usuario']}</a>
                </strong> valoró 
                <a href='profiles/game.php?id={$reseña['idAPI']}'>$gameName</a> con 
                <span class='star-rating'>{$estrellas}</span>
            </p>
            <p>{$reseña['texto']}</p>
            <p><em>Fecha: {$reseña['fecha_creación']}</em></p>
        </div>
    ";
}

echo $html;
?>
