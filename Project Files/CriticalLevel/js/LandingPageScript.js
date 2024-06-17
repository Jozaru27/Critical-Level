// SCRIPT FOR THE LANDING PAGE AND ONLY THE LANDING PAGE
const apiKey = '3493dbf3242341fb9284060b456efb79'; // API KEY - You shouldn't be seeing this you sneaky freak!

// API general URL
const baseUrl = 'https://api.rawg.io/api';

// Function to obtain different stats from the API
async function getInitialStats() {
  try {
    //API fetch for games and genres
    const [gamesResponse, genresResponse] = await Promise.all([
      fetch(`${baseUrl}/games?key=${apiKey}`),
      fetch(`${baseUrl}/genres?key=${apiKey}`)
    ]);

    const gamesData = await gamesResponse.json();
    const genresData = await genresResponse.json();

    // Obtains the total numbers
    const totalGames = gamesData.count;
    const totalGenres = genresData.results.length;

    // Adds a comma as a separator
    const formattedTotalGames = totalGames.toLocaleString();
    const formattedTotalGenres = totalGenres.toLocaleString();

    // Updates the html text, replacing the spinning/loading effect with actual information
    document.getElementById('totalGames').innerText = `Videojuegos: ${formattedTotalGames}`;
    document.getElementById('totalGenres').innerText = `Géneros Diferentes: ${formattedTotalGenres}`;

  } catch (error) {
    console.error('Error al obtener los datos:', error);
  }
}

// Function to obtain review and user stats from the database
async function getDbStats() {
  try {
    const response = await fetch('CriticalLevel/php/getStats.php');
    const data = await response.json();

    if (data.error) {
      console.error('Error al obtener los datos de la base de datos:', data.error);
      return;
    }

    const formattedTotalReseñas = data.totalReseñas.toLocaleString();
    const formattedTotalUsuarios = data.totalUsuarios.toLocaleString();

    // Updates the html text with database stats
    document.getElementById('totalReseñas').innerText = `Críticas: ${formattedTotalReseñas}`;
    document.getElementById('totalUsuarios').innerText = `Usuarios: ${formattedTotalUsuarios}`;
  } catch (error) {
    console.error('Error al obtener los datos de la base de datos:', error);
  }
}

// Quote Array
const frasesOpiniones = [
  "La opinión es la mitad del saber. - Séneca",
  "Las opiniones son como los clavos: cuanto más se golpean, más penetran. - Voltaire",
  "No se puede hacer nada en este mundo sin la opinión pública. - Winston Churchill",
  "La opinión pública es la peor de todas las opiniones. - Nicolas Chamfort",
  "La opinión pública es una potencia invisible y no hay nada tan cambiante como ella. - William McKinley",
  "Una opinión equivocada puede ser tolerada donde la razón es libre de combatirla. - Thomas Jefferson",
  "La opinión es el medio entre el conocimiento y la ignorancia. - Platón",
  "La opinión pública es un tirano débil comparado con nuestra propia opinión. - Henry David Thoreau",
  "El cambio de opinión es una de las cosas que más les cuesta a las personas. - Baltasar Gracián",
  "La opinión es libre, pero los hechos son sagrados. - Charles Prestwich Scott",
  "Las opiniones son privadas, pero la verdad es pública. - Thomas Hobbes"
];

// Function that displays a random quote everytime the page is loaded, from the ones stored in an array
function mostrarFraseAleatoria() {
  const indiceAleatorio = Math.floor(Math.random() * frasesOpiniones.length);
  const fraseAleatoria = frasesOpiniones[indiceAleatorio];
  document.querySelector('.fraseOpinion').innerText = fraseAleatoria;
}

// Whenever the window is loaded, fetches the stats from the api + the quotes and loads them into the landing page
window.onload = function() {
  getInitialStats();
  getDbStats();
  mostrarFraseAleatoria();
};
