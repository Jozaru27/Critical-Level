document.addEventListener('DOMContentLoaded', function () {
    const sortOptions = document.getElementById('sortOptions');
    const reviewsContainer = document.getElementById('reviewsContainer');

    // Function to load reviews with the given sorting order
    async function loadReviews(order) {
        try {
            const response = await fetch(`../php/getReviews.php?order=${order}`);
            const html = await response.text();
            reviewsContainer.innerHTML = html;
        } catch (error) {
            console.error('Error al cargar las reseñas:', error);
        }
    }

    // Event listener for the sort options dropdown change
    sortOptions.addEventListener('change', function () {
        loadReviews(this.value);
    });
    
    // Load reviews with the default order when the page loads
    loadReviews('recientes');
});
