<?php
require_once 'database.php'; 
try {
    // Counts total reviews stored in the DB
    $stmtReseñas = $pdo->query('SELECT COUNT(*) as totalReseñas FROM reseñas');
    $totalReseñas = $stmtReseñas->fetch(PDO::FETCH_ASSOC)['totalReseñas'];

    // Counts total users stored in the DB
    $stmtUsuarios = $pdo->query('SELECT COUNT(*) as totalUsuarios FROM usuarios');
    $totalUsuarios = $stmtUsuarios->fetch(PDO::FETCH_ASSOC)['totalUsuarios'];

    // Prepares result array
    $result = [
        'totalReseñas' => $totalReseñas,
        'totalUsuarios' => $totalUsuarios
    ];

    // Return array as JSON for the JS file to process it
    header('Content-Type: application/json');
    echo json_encode($result);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
