document.addEventListener('DOMContentLoaded', function () {
    const randomGameImg = document.getElementById('randomGameImg');

    // Function to load random game!
    function loadRandomGame() {
        fetch('https://api.rawg.io/api/games?key=3493dbf3242341fb9284060b456efb79&dates=2019-09-01,2019-09-30&platforms=18,1,7')
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error('Error al cargar el juego aleatorio');
                }
            })
            .then(data => {
                const randomIndex = Math.floor(Math.random() * data.results.length); // Obtains random index
                const randomGame = data.results[randomIndex];
                const gameID = randomGame.id;
                const gameName = randomGame.name;
                randomGameImg.src = randomGame.background_image; // Swap ? block image for actual game
                randomGameImg.alt = gameName; 
                randomGameImg.addEventListener('click', function () {
                    window.location.href = `profiles/game.php?id=${gameID}`; 
                });
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    // Load random game when page loads
    loadRandomGame();
});
