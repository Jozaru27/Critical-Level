<?php
session_start();
require_once "../../php/database.php";

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_email'])) {
    header("Location: ../forms/login.html");
    exit;
}

// Obtener el email del usuario de la sesión
$email = $_SESSION['usuario_email'];

// Buscar los datos del usuario en la base de datos
$sql = "SELECT * FROM Usuarios WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Usuario no encontrado";
    exit;
}

// Determinar el rol del usuario y asignar el badge correspondiente
$rol = $usuario['idROL'];
$badgeText = '';
$badgeClass = '';

if ($rol == 1) {
    $badgeText = 'Admin';
    $badgeClass = 'text-bg-danger'; // Red for Admin
} elseif ($rol == 2) {
    $badgeText = 'Usuario';
    $badgeClass = 'text-bg-primary'; // Blue for User
} elseif ($rol == 3) {
    $badgeText = 'Premium';
    $badgeClass = 'text-bg-warning'; // Yellow for Premium
}
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/MainPageStyle.css">
    <title>Perfil de Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1b2838;
            color: #c7d5e0;
            margin: 0;
            padding: 0;
        }
        .profile-container {
            max-width: 900px;
            margin: 20px auto;
            background: #2a475e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        .profile-header {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #394b59;
            padding-bottom: 20px;
        }
        .profile-header img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin-right: 20px;
            border: 5px solid #4a90e2;
        }
        .profile-header h1 {
            margin: 0;
        }
        .profile-info {
            margin-top: 20px;
        }
        .profile-info p {
            margin: 10px 0;
        }
        .edit-button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #4a90e2;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .edit-button:hover {
            background-color: #357abd;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 10px;
        }
        .modal-content input, .modal-content textarea, .modal-content select {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .modal-content button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #4a90e2;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .modal-content button:hover {
            background-color: #357abd;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover, .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <img src="<?php echo htmlspecialchars($usuario['fotoPerfil']); ?>" alt="Foto de perfil">
            <h1>    
                <?php echo htmlspecialchars($usuario['nombre_usuario']); ?>
                <h5>&nbsp;&nbsp;<span class="badge <?php echo $badgeClass; ?>"><?php echo $badgeText; ?></span></h5>
            </h1>
        </div>
        <div class="profile-info">
            <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
            <p class="bio"><strong>Bio:</strong> <?php echo htmlspecialchars($usuario['bio']); ?></p>
            <p><strong>Miembro desde:</strong> <?php echo date('Y-m-d', strtotime($usuario['fechaCreacionCuenta'])); ?></p>
            <p><strong>Juegos reseñados:</strong> <?php echo htmlspecialchars($usuario['numReseñasCreadas']); ?></p>
            <p><strong>País:</strong> <?php echo htmlspecialchars($usuario['pais']); ?></p>
            <p><strong>Última Actividad:</strong> <?php echo htmlspecialchars($usuario['ultimaActividad']); ?></p>
        </div>
        <button id="editBtn" class="edit-button">Editar</button>
    </div>

    <!-- Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form id="editForm">
                <label for="nombre_usuario">Nombre de usuario:</label>
                <input type="text" id="nombre_usuario" name="nombre_usuario" value="<?php echo htmlspecialchars($usuario['nombre_usuario']); ?>" required>
                <label for="bio">Bio:</label>
                <textarea id="bio" name="bio" rows="4" cols="50"><?php echo htmlspecialchars($usuario['bio']); ?></textarea>
                <label for="pais">País:</label>
                <select id="pais" name="pais">
                    <?php

                    $paisesUE = [
                        "Austria", "Bélgica", "Bulgaria", "Croacia", "Chipre", "República Checa",
                        "Dinamarca", "Estonia", "Finlandia", "Francia", "Alemania", "Grecia",
                        "Hungría", "Irlanda", "Italia", "Letonia", "Lituania", "Luxemburgo",
                        "Malta", "Países Bajos", "Polonia", "Portugal", "Rumania", "Eslovaquia",
                        "Eslovenia", "España", "Suecia"
                    ];

                    foreach ($paisesUE as $pais) {
                        $selected = ($usuario['pais'] == $pais) ? 'selected' : '';
                        echo "<option value=\"$pais\" $selected>$pais</option>";
                    }

                    ?>
                </select>
                <button type="submit">Guardar cambios</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('editBtn').addEventListener('click', function() {
            document.getElementById('editModal').style.display = "block";
        });

        document.getElementsByClassName('close')[0].addEventListener('click', function() {
            document.getElementById('editModal').style.display = "none";
        });

        window.addEventListener('click', function(event) {
            if (event.target == document.getElementById('editModal')) {
                document.getElementById('editModal').style.display = "none";
            }
        });

        document.getElementById('editForm').addEventListener('submit', function(event) {
            event.preventDefault();

            var formData = new FormData(this);
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../../php/updateProfile.php", true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    location.reload();
                } else {
                    alert("Hubo un error al guardar los cambios.");
                }
            };
            xhr.send(formData);
        });
    </script>

    
</body>
</html>
