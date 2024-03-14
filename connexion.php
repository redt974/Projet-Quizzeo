<?php
    session_start();

    // Check if the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $email = $_POST["email"];
        $password = $_POST["password"];

        // Check if the user exists and password is correct
        $file = fopen("utilisateurs.csv", "r");  // Use "r" for read mode

        if ($file !== false) {
            while (($user = fgetcsv($file)) !== false) {
                if ($user[3] === $email && password_verify($password, $user[4])) {
                    // Login successful, save in the session the email and id
                    $_SESSION["email"] = $email;
                    $_SESSION["prenom"] = $user[1];
                    $_SESSION["id"] = $user[0];

                    echo "Login successful!";

                    // Redirect to index.php
                    header("Location: index.php");
                    exit();
                }
            }
            fclose($file);  // Close the file after reading
            // If login fails
            echo "Invalid email or password.";
        } else {
            echo "Error reading user data.";
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
        <source src="./assets/background4.webm">
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
