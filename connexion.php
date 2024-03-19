<?php
session_start();

// Check if the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $email = $_POST["email"];
        $password = $_POST["password"];

        // Check if the user exists and password is correct
        $file = fopen("utilisateurs.csv", "r"); // Mode read and write

        if ($file !== false) {
            $donnees = []; 
            while (($user = fgetcsv($file)) !== false) {
                if ($user[3] === $email && password_verify($password, $user[4]) && $user[7] == 'active') {
                    $_SESSION["id"] = $user[0];
                    $_SESSION["prenom"] = $user[1];
                    $_SESSION["nom"] = $user[2];
                    $_SESSION["email"] = $email;
                    $_SESSION["role"] = $user[5];
                    $_SESSION["status"] = "connected"; 
                    $_SESSION["activate"] = $user[7];

                    // Modification du status de l'utilisateur
                    $user[6] = "connected";
                }
                $donnees[] = $user;
            }
    
            // Fermer le fichier
            fclose($file);

            // Ouvrir le fichier en mode écriture
            if (($file = fopen("utilisateurs.csv", "w")) !== false) { // Mode écritue
                // Écrire les données modifiées dans le fichier
                foreach ($donnees as $ligne) {
                    fputcsv($file, $ligne);
                }

                // Fermer le fichier
                fclose($file);
            }        
        
            // Redirect to index.php
            header("Location: index.php");
        }
    } 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="icon" href='./assets/quizzeo.ico' />  
    <link rel="stylesheet" href="./style/connexion.css">
</head>
<body>
    <?php 
        include './components/header.php';
    ?>
    <video id="background-video" autoplay loop muted>
        <source src="./assets/background.mp4">
    </video>
    <h1>Connexion</h1>
    <form action="connexion.php" method="post">
        <div class="form-group">
            <div class="label-input">
                <label for="email">Adresse Mail :</label>
                <input type="email" id="email" name="email" required>
            </div>
        </div>

        <div class="form-group">
            <div class="label-input">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>
        </div>
        <input type="submit" value="Connexion">
    </form>
    <div>
        <h2>Vous n'avez pas de compte ?</h2><a class="connexion" href="inscription.php">Inscrivez-vous !</a>
    </div>
</body>
</html>
