const apiKey = '';

// Function to make the API call to RAWG
function fetchData() {
  fetch(`https://api.rawg.io/api/games?key=${apiKey}&dates=2019-09-01,2019-09-30&platforms=18,1,7`)
    .then(response => {
      // Check if the request was successful (status code 200)
      if (response.ok) {
        return response.json(); // Convert the response to JSON
      } else {
        throw new Error('Error loading the games list');
      }
    })
    .then(data => {
      // Process the response data
      console.log('Updated games list:', data.results);
      // Display the results on the HTML page
      showGames(data.results);
    })
    .catch(error => {
      console.error('Error:', error);
      // Handle request errors
    });
}

// Define the showGames function
// Function to display the results on the HTML page
function showGames(results) {
  const gamesList = document.getElementById('games-list');
  // Clear previous content
  gamesList.innerHTML = '';
  // Create a container for the image rows
  const rowContainer = document.createElement('div');
  rowContainer.classList.add('row-container');
  // Iterate over the results and create image elements for each game
  results.forEach((game, index) => {
    // Create a new div for each game
    const gameDiv = document.createElement('div');
    gameDiv.classList.add('game-item');
    // Create an image for the game and set its src and alt
    const gameImage = document.createElement('img');
    gameImage.src = game.background_image;
    gameImage.alt = game.name;
    // Add the image to the game div
    gameDiv.appendChild(gameImage);
    // Add the game div to the row container
    rowContainer.appendChild(gameDiv);
    // If we have added five games or it is the last game, add the row to the main container and create a new row
    if ((index + 1) % 5 === 0 || index === results.length - 1) {
      gamesList.appendChild(rowContainer);
      // Create a new row container
      const newRowContainer = document.createElement('div');
      newRowContainer.classList.add('row-container');
      rowContainer = newRowContainer;
    }
  });
}

// Rest of the code to display the games, etc.

// Call fetchData() initially when the page loads
// fetchData();

// Call fetchData() every hour (3600000 milliseconds = 1 hour)
setInterval(fetchData, 3600000);
