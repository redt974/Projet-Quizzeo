document.addEventListener("DOMContentLoaded", function() {
    var dropdown = document.getElementById('userDropdown');
    var dropdownContent = document.getElementById('dropdownContent');

    dropdown.addEventListener('click', function() {
        if (dropdownContent.style.display === "none") {
            dropdownContent.style.display = "block";
        } else {
            dropdownContent.style.display = "none";
        }
    });
});