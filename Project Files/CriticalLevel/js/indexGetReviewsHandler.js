document.addEventListener('DOMContentLoaded', function () {
    const sortOptions = document.getElementById('sortOptions');
    const reviewsContainer = document.getElementById('reviewsContainer');

    function loadReviews(order) {
        fetch(`../php/getReviews.php?order=${order}`)
            .then(response => response.text())
            .then(data => {
                reviewsContainer.innerHTML = data;
            })
            .catch(error => console.error('Error al cargar las reseñas:', error));
    }

    sortOptions.addEventListener('change', function () {
        loadReviews(this.value);
    });

    // Cargar las reseñas por defecto (más recientes)
    loadReviews('recientes');
});
