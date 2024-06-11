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

// Consultar el número de reseñas del usuario
$stmt_reseñas_count = $pdo->prepare("SELECT COUNT(*) AS count FROM Reseñas WHERE email = ?");
$stmt_reseñas_count->execute([$email]);
$numReseñas = $stmt_reseñas_count->fetchColumn();

// // Función para mostrar las estrellas de valoración usando imágenes
// function mostrarEstrellas($valoracion) {
//     $estrellas = '';
//     $goldStar = "../../media/rating_icons/goldStar.png";
//     $voidStar = "../../media/rating_icons/voidStar.png";
//     for ($i = 1; $i <= 5; $i++) {
//         if ($i <= $valoracion) {
//             $estrellas .= '<img src="' . $goldStar . '" alt="Estrella dorada" style="width: 20px; height: 20px;">';
//         } else {
//             $estrellas .= '<img src="' . $voidStar . '" alt="Estrella vacía" style="width: 20px; height: 20px;">';
//         }
//     }
//     return $estrellas;
// }
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/MainPageStyle.css">
    <link rel="stylesheet" href="../../css/profilesStyle/profile.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <script src="../../js/script.js"></script>
    <title>Perfil de Usuario</title>
</head>
<body>

    <!-- https://wweb.dev/resources/navigation-generator - https://freefrontend.com/css-menu/-->
    <nav class="menu-container">
      <!-- Burger Menu -->
      <input type="checkbox" aria-label="Toggle menu" />
      <span></span>
      <span></span>
      <span></span>
    
      <!-- Logo -->
      <a href="../../index.html" class="menu-logo">
        <img src="../../media/CL_Logo_Blue_Hex/CL_Logo_HD_White.png" alt="Landing Page"/>
      </a>
    
      <!-- Navbar Menu -->
      <div class="menu">
        <ul>
            <li>
                <a href="../index.php">
                    Inicio
                </a>
            </li>
            <li>
                <a href="../games.php">
                    Juegos
                </a>
            </li>
            <li>
                <a href="../eventos.php">
                    Eventos
                </a>
            </li>
            <li>
                <a href="../premium.php">
                    Premium
                </a>
            </li>
        </ul>
        <ul>
            <?php if (isset($_SESSION['usuario_email'])): ?>
                <li>
                    <a href="../../php/logout.php">
                        Cerrar Sesión
                    </a>
                </li>
            <?php else: ?>
                <li>
                    <a href="../forms/signup.html">
                        Registro
                    </a>
                </li>
                <li>
                    <a href="../forms/login.html">
                        Iniciar Sesión
                    </a>
                </li>
            <?php endif; ?>
        </ul>
      </div>
    </nav>

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
            <p><strong>Juegos reseñados:</strong> <?php echo htmlspecialchars($numReseñas); ?></p>
            <p><strong>País:</strong> <?php echo htmlspecialchars($usuario['pais']); ?></p>
            <p><strong>Última Actividad:</strong> <?php echo htmlspecialchars($usuario['ultimaActividad']); ?></p>
        </div>
        <button id="editBtn" class="edit-button">Editar</button>
        <button id="deleteProfileBtn" class="delete-button">Eliminar perfil</button>

        <hr>
        <h2>Reseñas del Usuario</h2>
        
        <?php
        // Consultar las reseñas del usuario actual
        $sql_reseñas = "SELECT * FROM Reseñas WHERE email = ?";
        $stmt_reseñas = $pdo->prepare($sql_reseñas);
        $stmt_reseñas->execute([$email]);
        $reseñas_usuario = $stmt_reseñas->fetchAll(PDO::FETCH_ASSOC);

        // Array para almacenar los IDs de juegos
        $idJuegos = [];
        foreach ($reseñas_usuario as $reseña) {
            $idJuegos[] = $reseña['idAPI'];
        }

        // Realizar llamada a la API para obtener los títulos de los juegos
        $apiUrl = "https://api.rawg.io/api/games?key=" . $apiKey . "&ids=" . implode(',', $idJuegos);
        $response = file_get_contents($apiUrl);
        $data = json_decode($response, true);

        // Crear un array asociativo de idAPI a título
        $juegos = [];
        foreach ($data['results'] as $juego) {
            $juegos[$juego['id']] = $juego['name'];
        }

        // Mostrar las reseñas del usuario junto con los títulos de los juegos
        foreach ($reseñas_usuario as $reseña) {
            echo "<div class='review-container'>";
            echo "<p><strong>Juego:</strong> <a href='http://criticallevel.myddns.me/CriticalLevel/html/profiles/game.php?id=" . $reseña['idAPI'] . "'>" . htmlspecialchars($juegos[$reseña['idAPI']]) . "</a></p>";

            // Mostrar estrellas de valoración
            echo "<p><strong>Valoración:</strong> ";
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $reseña['valoración']) {
                    echo "<i class='bi bi-star-fill' style='color: #ffb400;'></i>";
                } else {
                    echo "<i class='bi bi-star' style='color: #ffb400;'></i>";
                }
            }

            // echo "<p><strong>Valoración:</strong> " . mostrarEstrellas($reseña['valoración']) . "</p>";
            // echo "<p><strong>Valoración:</strong> " . htmlspecialchars($reseña['valoración']) . "</p>";
            echo "<p><strong>Texto:</strong> " . nl2br(htmlspecialchars($reseña['texto'])) . "</p>";
            echo "<p><em>Fecha de Creación: " . htmlspecialchars($reseña['fecha_creación']) . "</em></p>";
            echo "</div>";
        }
        ?>
    </div>

    <!-- Site Footer -->
    <footer class="site-footer">
        <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-6">
            <h6>Sobre Critical Level</h6>
            <p class="text-justify">Critical Level es una simple página web en la cuál recogemos una amplia variedad de videojuegos, además de información relacionada y peritenente a los mismos. El uso de esta página web implica que aceptas, no sólo leer las reglas impuestas en la misma, sino acatarlas para hacer un mejor uso y experiencia tanto para ti como para el resto de usuarios.</p>
            </div>

            <div class="col-xs-6 col-md-3">
            <h6>Enlaces</h6>
            <ul class="footer-links">
                <li><a href="../../../index.html">Landing Page</a></li>
                <li><a href="../index.php">Inicio</a></li>
                <li><a href="../games.php">Juegos</a></li>
                <li><a href="../eventos.html">Eventos</a></li>
                <li><a href="../premium.html">Premium</a></li>
                <!-- <li><a href="">Yuju [NULL]</a></li> -->
            </ul>
            </div>

            <div class="col-xs-6 col-md-3">
            <h6>Legal</h6>
            <ul class="footer-links">
                <li><a href="../legal/aboutus.html">Sobre Nosotros</a></li>
                <li><a href="../forms/contactus.html">Contáctanos</a></li>
                <!-- <li><a href="">Contribuir [NULL]</a></li> -->
                <li><a href="../legal/privacypolicy.html">Política de Privacidad</a></li>
                <!-- <li><a href="">Sitemap [NULL]</a></li> -->
            </ul>
            </div>
        </div>
        <hr>
        </div>
        <div class="container">
        <div class="row">
            <div class="col-md-8 col-sm-6 col-xs-12">
            <p class="copyright-text">Copyright &copy; 2024 Todos los Derechos Reservados 
            <a href="https://github.com/Jozaru27">Jose Zafrilla Ruiz</a>.
            </p>
            </div>

            <!-- Icons Taken from https://icons8.com/ <a target="_blank" href="https://icons8.com/icon/12505/steam">Steam</a> icon by <a target="_blank" href="https://icons8.com">Icons8</a>--> 
            <div class="col-md-4 col-sm-6 col-xs-12">
            <ul class="social-icons">
                <li><a class="github" href="https://github.com/Jozaru27/Critical-Level"><i class="bi-github"></i></a></li>
                <li><a class="linkedin" href="https://www.linkedin.com/in/jose-zafrilla-ruiz/"><i class="bi-linkedin"></i></a></li>
                <!-- <li><a class="steam" href="https://steamcommunity.com/id/jozaru"><i class="bi bi-steam"></i></a></li> -->
                <!--<li><a class="linkedin" href="#"><i class="fa fa-linkedin"></i></a></li>    -->
            </ul>
            </div>
        </div>
        </div>
    </footer>

    <!-- Modal para Editar Perfil -->
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
                <label for="fotoPerfil">Foto de Perfil:</label>
                <input type="file" id="fotoPerfil" name="fotoPerfil" accept="image/*">
                <button type="submit">Guardar cambios</button>
            </form>
        </div>
    </div>

    <!-- Modal para Confirmar Eliminación de Perfil -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>¿Estás seguro de que deseas eliminar tu perfil?</h2>
            <p>Esta acción no se puede deshacer.</p>
            <button id="confirmDeleteBtn" class="delete-button">Confirmar Eliminación</button>
        </div>
    </div>

    <script>
        // Modal de Editar Perfil
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

        // Modal de Eliminar Perfil
        document.getElementById('deleteProfileBtn').addEventListener('click', function() {
            document.getElementById('deleteModal').style.display = "block";
        });

        document.getElementsByClassName('close')[1].addEventListener('click', function() {
            document.getElementById('deleteModal').style.display = "none";
        });

        window.addEventListener('click', function(event) {
            if (event.target == document.getElementById('deleteModal')) {
                document.getElementById('deleteModal').style.display = "none";
            }
        });

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../../php/deleteProfile.php", true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert("Perfil eliminado con éxito.");
                    window.location.href = "../forms/login.html";
                } else {
                    alert("Hubo un error al eliminar el perfil.");
                }
            };
            xhr.send();
        });
    </script>
</body>
</html>
