// Edit Profile Modal
document.getElementById('editBtn').addEventListener('click', function() {
    document.getElementById('editModal').style.display = "block";
});

// Close the edit modal when the close button is clicked
document.getElementsByClassName('close')[0].addEventListener('click', function() {
    document.getElementById('editModal').style.display = "none";
});

// Close the edit modal when clicking outside of it
window.addEventListener('click', function(event) {
    if (event.target == document.getElementById('editModal')) {
        document.getElementById('editModal').style.display = "none";
    }
});

// Handle the form submission for editing the profile
document.getElementById('editForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    var formData = new FormData(this);
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../../php/updateProfile.php", true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            location.reload(); // Reload the page on successful update
        } else {
            alert("Hubo un error al guardar los cambios.");
        }
    };
    xhr.send(formData);
});

// Delete Profile Modal
document.getElementById('deleteProfileBtn').addEventListener('click', function() {
    document.getElementById('deleteModal').style.display = "block";
});

// Close the delete modal when the close button is clicked
document.getElementsByClassName('close')[1].addEventListener('click', function() {
    document.getElementById('deleteModal').style.display = "none";
});

// Close the delete modal when clicking outside of it
window.addEventListener('click', function(event) {
    if (event.target == document.getElementById('deleteModal')) {
        document.getElementById('deleteModal').style.display = "none";
    }
});

// Handle the confirmation of profile deletion
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