<?php
session_start();
require_once "../php/database.php";

// Verificar si el usuario ha iniciado sesión y obtener el rol del usuario
$userLoggedIn = isset($_SESSION['usuario_email']);
$userRole = null;

if ($userLoggedIn) {
    $email = $_SESSION['usuario_email'];
    $stmt = $pdo->prepare("SELECT idROL FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $userRole = $stmt->fetchColumn();
}
?>

<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fonts -->
    <link rel="stylesheet" href="../css/fonts.css">

    <!-- StyleSheets -->
    <link rel="stylesheet" href="../css/MainPageStyle.css">
    <link rel="stylesheet" href="../css/GamesPageStyle.css">

    <!-- Icon -->
    <link rel="icon" type="image/x-icon" href="../media/CL_Logo_Blue_Hex/favicon.ico">

    <!-- Libraries -->
    <link href="../libraries/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="../libraries/bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <!-- Script -->
    <script src="../js/script.js"></script>
    <script src="../js/GamesPageScript.js"></script>
    
    <!-- Main Page -->
    <title>Critical Level</title>
  </head>
  <body>

  <nav class="menu-container">
      <!-- Burger Menu -->
      <input type="checkbox" aria-label="Toggle menu" />
      <span></span>
      <span></span>
      <span></span>
    
      <!-- Logo -->
      <a href="../../index.php" class="menu-logo">
        <img src="../media/CL_Logo_Blue_Hex/CL_Logo_HD_White.png" alt="Landing Page"/>
      </a>
    <!-- Navbar Menu -->
    <div class="menu">
      <ul>
        <li>
          <a href="index.php">
              Inicio
          </a>
        </li>
        <li>
          <a href="games.php">
              Juegos
          </a>
        </li>
        <li>
          <a href="eventos.php">
              Eventos
          </a>
        </li>
        <li>
          <a href="premium.php">
              Premium
          </a>
        </li>
      </ul>
      <ul>
        <?php if (isset($_SESSION['usuario_email'])): ?>
          <li>
            <a href="profiles/profile.php">
               Perfil
            </a>
          </li>
          <li>
            <a href="../php/logout.php">
              Cerrar Sesión
            </a>
            </li>
         <?php else: ?>
            <li>
              <a href="forms/signup.html">
                Registro
              </a>
            </li>
            <li>
              <a href="forms/login.html">
                Iniciar Sesión
              </a>
            </li>
        <?php endif; ?>
      </ul>
    </div>
  </nav>

  <!-- Ad Banner -->
  <?php if (!$userLoggedIn || $userRole == 2): ?>
    <div class="bannerContainer">
      <img class="adBanner" src="../media/collab/IG_AD.jpg" alt="Banner de Anuncio">
    </div>
  <?php endif; ?>

  <!-- Page Content -->
  <div class="container">
    <!-- Search Bar -->
    <div class="search-container">
      <input type="text" id="searchInput" placeholder="Buscar Juegos...">
      <button onclick="searchGames()">Buscar</button>
    </div>
  <div class="games-container" id="gamesContainer"></div>
    <div class="pagination" id="pagination">
    <button id="prevPage" disabled>Anterior</button>
    <div id="pageNumbers"></div>
    <button id="nextPage">Siguiente</button>
  </div>

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
            <li><a href="../../index.html">Landing Page</a></li>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="games.php">Juegos</a></li>
            <li><a href="eventos.php">Eventos</a></li>
            <li><a href="premium.php">Premium</a></li>
          </ul>
        </div>

        <div class="col-xs-6 col-md-3">
          <h6>Legal</h6>
          <ul class="footer-links">
            <li><a href="legal/aboutus.html">Sobre Nosotros</a></li>
            <li><a href="forms/contactus.html">Contáctanos</a></li>
            <li><a href="">Contribuir [NULL]</a></li>
            <li><a href="legal/privacypolicy.html">Política de Privacidad</a></li>
            <li><a href="">Sitemap [NULL]</a></li>
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

<script>
let gamesData = []; // Store all obtained gmaes
let currentPage = 1; // Initial Page
let totalGames = 0; // Total Games Counter

// Obtain first sets of games
function fetchGames(page) {
    const apiUrl = `${baseUrl}/games?key=${apiKey}&page=${page}&page_size=20`;
    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            gamesData = data.results; //  Store obtained games
            totalGames = data.count; // Total of games
            currentPage = page; // Update current page
            displayGames(gamesData); // Display games
            updatePagination(page, data.previous, data.next);
        })
        .catch(error => console.error('Error fetching games:', error));
}

// Search games by title
function searchGames() {
    const searchInput = document.getElementById('searchInput').value.trim().toLowerCase();
    if (searchInput === '') {
        fetchGames(currentPage); // Obtain actual page games if search bar is empty
        return;
    }

    // Realizar búsqueda en la API
    const searchUrl = `${baseUrl}/games?key=${apiKey}&search=${searchInput}&page_size=20`;
    fetch(searchUrl)
        .then(response => response.json())
        .then(data => {
            gamesData = data.results; //  Store obtained games
            totalGames = data.count; // Total of games
            currentPage = 1; // Update current page to 1
            displayGames(gamesData); // Display filtered games
            updatePagination(1, data.previous, data.next, true); // Update pagination search
        })
        .catch(error => console.error('Error searching games:', error));
}

// Show game in game containers
function displayGames(games) {
    const gamesContainer = document.getElementById('gamesContainer');
    gamesContainer.innerHTML = '';

    games.forEach(game => {
        const gameCard = document.createElement('div');
        gameCard.classList.add('game-card');
        gameCard.innerHTML = `<img src="${game.background_image}" alt="${game.name}">`;
        gameCard.innerHTML += `<div class="game-title">${game.name}</div>`;
        gameCard.addEventListener('click', () => {
            window.location.href = `profiles/game.php?id=${game.id}`;
        });
        gamesContainer.appendChild(gameCard);
    });
}

// Update pagination buttons
function updatePagination(currentPage, previousPageUrl, nextPageUrl, isSearch = false) {
    const prevPageButton = document.getElementById('prevPage');
    const nextPageButton = document.getElementById('nextPage');
    const pageNumbersContainer = document.getElementById('pageNumbers');

    prevPageButton.disabled = !previousPageUrl;
    prevPageButton.onclick = () => {
        if (previousPageUrl) {
            fetchGames(currentPage - 1);
        }
    };

    nextPageButton.disabled = !nextPageUrl;
    nextPageButton.onclick = () => {
        if (nextPageUrl) {
            fetchGames(currentPage + 1);
        }
    };

    // Clean buttons
    pageNumbersContainer.innerHTML = '';

    // Calculate total of page
    const totalPages = Math.ceil(totalGames / 20);

    // Range of pages to show
    const startPage = Math.max(1, currentPage - 2);
    const endPage = Math.min(totalPages, currentPage + 2);

    // Creates number buttons for navigation before number input
    for (let i = startPage; i < currentPage; i++) {
        const pageNumberButton = document.createElement('button');
        pageNumberButton.innerText = i;
        pageNumberButton.classList.add('page-number');
        pageNumberButton.onclick = () => {
            if (isSearch) {
                const searchInput = document.getElementById('searchInput').value.trim().toLowerCase();
                const searchUrl = `${baseUrl}/games?key=${apiKey}&search=${searchInput}&page=${i}&page_size=20`;
                fetch(searchUrl)
                    .then(response => response.json())
                    .then(data => {
                        gamesData = data.results; 
                        currentPage = i; 
                        displayGames(gamesData);
                        updatePagination(i, data.previous, data.next, true); 
                    })
                    .catch(error => console.error('Error searching games:', error));
            } else {
                fetchGames(i);
            }
        };
        pageNumbersContainer.appendChild(pageNumberButton);
    }

    // Number Input field
    const pageInput = document.createElement('input');
    pageInput.type = 'number';
    pageInput.min = 1;
    pageInput.max = totalPages;
    pageInput.value = currentPage;
    pageInput.onchange = () => {
        const pageNumber = parseInt(pageInput.value);
        if (pageNumber >= 1 && pageNumber <= totalPages) {
            fetchGames(pageNumber);
        } else {
            pageInput.value = currentPage; // If value doesnt exist, it resets
        }
    };
    pageNumbersContainer.appendChild(pageInput);

    // Creates number buttons for navigation after number input
    for (let i = currentPage + 1; i <= endPage; i++) {
        const pageNumberButton = document.createElement('button');
        pageNumberButton.innerText = i;
        pageNumberButton.classList.add('page-number');
        pageNumberButton.onclick = () => {
            if (isSearch) {
                const searchInput = document.getElementById('searchInput').value.trim().toLowerCase();
                const searchUrl = `${baseUrl}/games?key=${apiKey}&search=${searchInput}&page=${i}&page_size=20`;
                fetch(searchUrl)
                    .then(response => response.json())
                    .then(data => {
                        gamesData = data.results; 
                        currentPage = i; 
                        displayGames(gamesData); 
                        updatePagination(i, data.previous, data.next, true); 
                    })
                    .catch(error => console.error('Error searching games:', error));
            } else {
                fetchGames(i);
            }
        };
        pageNumbersContainer.appendChild(pageNumberButton);
    }
}

// Loads the games initially
fetchGames(currentPage);
</script>
</body>
</html>