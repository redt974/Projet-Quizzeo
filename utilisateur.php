<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./style/utilisateu.css">
    <style>
        /* @keyframes wavy {
            0% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
            100% { transform: translateX(-5px); }
        }

        input:not([readonly]) {
            animation: wavy 1s infinite;
        } */
    </style>
</head>
<body>
    <video id="background-video" autoplay loop muted>
        <source src="./assets/0319.mp4">
    </video>
    <?php
        session_start();
        if(!isset($_SESSION['email'])){
            header('location: connexion.php');
            exit; // Arrête l'exécution du script
        }
        include './components/header.php';

        // Vérifie si le formulaire a été soumis
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Récupère les données du formulaire
            $prenom = $_POST['prenom'];
            $nom = $_POST['nom'];
            $email = $_POST['email'];
            $role = $_POST['role'];

            // Chemin vers le fichier CSV
            $fichier_csv = 'utilisateurs.csv';

            // Lit le contenu actuel du fichier CSV
            $lines = file($fichier_csv);

            // Parcourt chaque ligne du fichier CSV
            foreach ($lines as $key => $line) {
                // Divise la ligne en colonnes
                $data = str_getcsv($line);

                // Vérifie si l'email de la ligne correspond à l'email de l'utilisateur connecté
                if ($data[3] === $_SESSION['email']) {
                    // Modifie les données appropriées
                    $lines[$key] = implode(',', [$data[0], $prenom, $nom, $email, $data[4], $role, $data[6], $data[7]]) . "\n";

                    // Met à jour les valeurs de session
                    $_SESSION['prenom'] = $prenom;
                    $_SESSION['nom'] = $nom;
                    $_SESSION['email'] = $email;
                    $_SESSION['role'] = $role;

                    break; // Arrête la boucle après la mise à jour
                }
            }

            // Réécrit le fichier CSV avec les nouvelles données
            file_put_contents($fichier_csv, implode('', $lines));

            // Redirige vers le profil après la mise à jour
            header('location: utilisateur.php');
            exit; // Arrête l'exécution du script après la redirection
        }
    ?>
    <div class="wrapper">
        <h2>Votre Profil</h2>
        <button id="editProfileBtn">Modifier</button>
        <button id="cancelBtn" style="display: none;">Annuler les modifications</button>
        <form id="profileForm" action="#" method="post"> <!-- Ajout de method="post" -->
            <div class="input-field mb-3">
                <input type="text" value="<?php echo $_SESSION['prenom']; ?>" name="prenom" required readonly>
                <label>Ton prénom</label>
            </div>
            <div class=" input-field mb-3">
                <input type="text" value="<?php echo $_SESSION['nom']; ?>" name="nom" required readonly>
                <label>Ton nom</label>
            </div>
            <div class="input-field mb-3">
                <input type="text" value="<?php echo $_SESSION['email']; ?>" name="email" required readonly>
                <label>Ton email</label>
            </div>
            <select class="form-select form-select-lg mb-3" aria-label="Large select example" name="role" readonly>
                <option value="school" <?php if ($_SESSION['role'] === 'school') echo 'selected'; ?>>Ecole</option>
                <option value="company" <?php if ($_SESSION['role'] === 'company') echo 'selected'; ?>>Entreprise</option>
                <option value="user" <?php if ($_SESSION['role'] === 'user') echo 'selected'; ?>>Utilisateur standard</option>
            </select>
            <button type="button">   <a href="forgot.php">Modifier le mot de passe</a></button><br>
            <button type="submit" id="submitBtn">Soumettre</button>
            <a href="http://"></a>
        </form>
    </div>

    <script>
        var editBtn = document.getElementById('editProfileBtn');
        var cancelBtn = document.getElementById('cancelBtn');
        var inputs = document.querySelectorAll('#profileForm input, #profileForm select');
        var submitBtn = document.getElementById('submitBtn');

        editBtn.addEventListener('click', function() {
            inputs.forEach(function(input) {
                input.removeAttribute('readonly');
            });
            editBtn.style.display = 'none';
            cancelBtn.style.display = 'inline-block';
        });

        cancelBtn.addEventListener('click', function() {
            inputs.forEach(function(input) {
                input.setAttribute('readonly', 'readonly');
            });
            editBtn.style.display = 'inline-block';
            cancelBtn.style.display = 'none';
            window.location.href = 'utilisateur.php'; // Redirection vers utilisateur.php
        });
    </script>
</body>
</html>
