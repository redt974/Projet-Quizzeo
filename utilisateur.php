<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./style/utilisateur.css">
</head>
<body>
    <video id="background-video" autoplay loop muted>
                    <source src="./assets/background6.mp4">
    </video>
    <?php
        session_start();
        if(!isset($_SESSION['email'])){
            header('location: connexion.php');
        }
        include './components/header.php';

        ?>
        <div class="wrapper">
    <button id="editProfileBtn">Modifier</button>
    <form id="profileForm" action="#" style="display: none;">
        <h2>Votre Profil</h2>
        <div class="input-field">
            <input type="text" value="<?php echo $_SESSION['prenom']; ?>" required>
            <label>Ton prénom</label>
        </div>
        <div class="input-field">
            <input type="text" value="<?php echo $_SESSION['nom']; ?>" required>
            <label>Ton nom</label>
        </div>
        <div class="input-field">
            <input type="text" value="<?php echo $_SESSION['email']; ?>" required>
            <label>Ton email</label>
        </div>
        <select class="form-select form-select-lg mb-3" aria-label="Large select example">
            <option value="1">Ecole</option>
            <option value="2">Entreprise</option>
            <option value="3">Utilisateur standard</option>
        </select>
        <button type="submit">Soumettre</button>
    </form>
</div>

<script>
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
</script>

    
</body>
</html>