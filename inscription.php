<?php
    session_start();

    // Check if the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Retrieve form data
        $fname = $_POST["fname"];
        $lname = $_POST["lname"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $role = $_POST["role"];

        // Validate email using regular expression
        $emailPattern = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z]{2,4}$/';
        if (!preg_match($emailPattern, $email)) {
            echo "L'adresse email est invalide !";
        }

        // Validate password using regular expression
        $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@$!%*#?&])[A-Za-z0-9@$!%*#?&]{8,}$/';
        if (!preg_match($passwordPattern, $password)) {
            echo "Votre mot de passe doit avoir une longueur minimale de 8 caractères. Votre mot de passe doit contenir au moins une lettre minuscule, une lettre majuscule, un chiffre, un caractère spécial parmi @$!%*#?&";
        }

        // Read existing users from CSV file
        $file_name = "utilisateurs.csv";

        // Check if the user already exists
        if (userExists($file_name, $email)) {
            echo "User already exists!";
        }

        // Determine the next available user ID
        $nextUserId = getNextUserId($file_name);

        // Save new user data to CSV file
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Append new user data to the file
        $file = fopen($file_name, "a");

        if ($file !== false) {
            fputcsv($file, [$nextUserId, $fname, $lname, $email, $hashedPassword, $role, "connected", "activate"]);
            fclose($file);

            // Create a session for the new user
            $_SESSION["id"] = $nextUserId;
            $_SESSION["prenom"] = $fname;
            $_SESSION["nom"] = $lname;
            $_SESSION["email"] = $email; 
            $_SESSION["role"] = $role;
            $_SESSION["status"] = "connected";
            $_SESSION["activate"] = "activate";

            // Redirect to index.php
            header("Location: index.php");
        }
    }

    // Function to check if the user already exists
    function userExists($file_name, $email) {
        $file = fopen($file_name, "r");

        if ($file !== false) {
            while (($user = fgetcsv($file)) !== false) {
                if ($user[3] === $email) {
                    fclose($file);
                    return true;
                }
            }

            fclose($file);
        }

        return false;
    }

    // Function to get the next available user ID
    function getNextUserId($file_name) {
        $file = fopen($file_name, "r");
        $nextUserId = 0;

        if ($file !== false) {
            while (($user = fgetcsv($file)) !== false) {
                $nextUserId = max($nextUserId, (int) $user[0]);
            }

            fclose($file);
        }

        return $nextUserId + 1;
    }

    // Captcha Authentification
    if(!empty($_POST['g-recaptcha-response']) || isset($_POST['g-recaptcha-response'])) {
        $secret="6LddDpUpAAAAANaKWBH05GUoOVS75h6qje2JEEKv";
        
        $data=json_decode($response);
        if($data->success){
            echo"<h2>Données envoyés !";
        }
        else{
            echo"<h2>Essayez encore !";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="icon" href='./assets/quizzeo.ico' />
    <link rel="stylesheet" href="./style/inscription.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <?php 
        include './components/header.php';
    ?>
    <video id="background-video" autoplay loop muted>
        <source src="./assets/background.mp4">
    </video>
    <h1>Inscription</h1>
    <form action="inscription.php" method="post" onsubmit="return validateForm();">
        <div class="form-group">
            <div class="label-input">
                <label for="fname">Prénom :</label>
                <input type="text" id="fname" name="fname" required>
            </div>
        </div>

        <div class="form-group">
            <div class="label-input">
                <label for="lname">Nom :</label>
                <input type="text" id="lname" name="lname" required>
            </div>
        </div>

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
        <div class="form-group">
            <div class="label-input">
                <label>Choisir un rôle:</label>
                
                <select name="role" id="role">
                    <option value="user">Utilisateur standard</option>
                    <option value="school">Ecole</option>
                    <option value="company">Entreprise</option>
                </select>
            </div>
        </div>
        <div class="g-recaptcha" data-sitekey="6LddDpUpAAAAAAeUvhSEb5l_fT8u29IGVWA40sFh"></div>
        <div id="captchaError"></div>

        <script defer>
            function validateForm() {             
                var captchaResponse = grecaptcha.getResponse();             
                while (captchaResponse == "") {                 
                    document.getElementById("captchaError").innerHTML = "<h4>Veuillez compléter le reCAPTCHA.</h4>";
                    return false;             
                }             
                return true; 
            } 
        </script>
        <input type="submit" value="Inscription">
    </form>
    <div class="switch-page">
        <h2>Vous avez déjà un compte ?</h2><a class="inscription" href="connexion.php">Connectez-vous !</a>
    </div>
</body>
</html>
