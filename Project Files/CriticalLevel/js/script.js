const apiKey = '3493dbf3242341fb9284060b456efb79'; // Reemplaza 'tu-clave-de-api' con tu clave de API real

// Función para realizar la llamada a la API de RAWG
function fetchData() {
  fetch(`https://api.rawg.io/api/games?key=${apiKey}&dates=2019-09-01,2019-09-30&platforms=18,1,7`)
    .then(response => {
      // Verificar si la solicitud fue exitosa (código de estado 200)
      if (response.ok) {
        return response.json(); // Convertir la respuesta a JSON
      } else {
        throw new Error('Error al cargar la lista de juegos');
      }
    })
    .then(data => {
      // Procesar los datos de la respuesta
      console.log('Lista de juegos actualizada:', data.results);
      // Mostrar los resultados en la página HTML
      showGames(data.results);
    })
    .catch(error => {
      console.error('Error:', error);
      // Manejar errores de la solicitud
    });
}

// Definir la función showGames
// Función para mostrar los resultados en la página HTML
function showGames(results) {
    const gamesList = document.getElementById('games-list');
    // Limpiar contenido anterior
    gamesList.innerHTML = '';
    // Crear un contenedor para las filas de imágenes
    const rowContainer = document.createElement('div');
    rowContainer.classList.add('row-container');
    // Iterar sobre los resultados y crear elementos de imagen para cada juego
    results.forEach((game, index) => {
      // Crear un nuevo div para cada juego
      const gameDiv = document.createElement('div');
      gameDiv.classList.add('game-item');
      // Crear una imagen para el juego y establecer su src y alt
      const gameImage = document.createElement('img');
      gameImage.src = game.background_image;
      gameImage.alt = game.name;
      // Añadir la imagen al div del juego
      gameDiv.appendChild(gameImage);
      // Añadir el div del juego al contenedor de la fila
      rowContainer.appendChild(gameDiv);
      // Si hemos añadido cinco juegos o es el último juego, añadir la fila al contenedor principal y crear una nueva fila
      if ((index + 1) % 5 === 0 || index === results.length - 1) {
        gamesList.appendChild(rowContainer);
        // Crear un nuevo contenedor de fila
        const newRowContainer = document.createElement('div');
        newRowContainer.classList.add('row-container');
        rowContainer = newRowContainer;
      }
    });
  }
  

// Resto del código para mostrar los juegos, etc.

// Llamar a fetchData() inicialmente al cargar la página
// fetchData();

// Llamar a fetchData() cada hora (3600000 milisegundos = 1 hora)
setInterval(fetchData, 3600000);
