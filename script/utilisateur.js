document.addEventListener("DOMContentLoaded", function() {
    var editProfileBtn = document.getElementById('editProfileBtn');
    var profileForm = document.getElementById('profileForm');

    editProfileBtn.addEventListener('click', function() {
        if (profileForm.style.display === "none") {
            profileForm.style.display = "block";
        } else {
            profileForm.style.display = "none";
        }
    });
});