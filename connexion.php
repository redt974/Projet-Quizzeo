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
    <style>
        @import url(https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap);@import url(https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap);
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            backdrop-filter: blur(10px);
        }
        h1 {
            text-align: center;
            font-size: 56px;
        }
        form {
            padding: 20px;
            background-color: rgba(255,255,255,0.4); 
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.4);
            margin-bottom: 20px;
            width: 35%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .form-group {
            margin: 20px 0;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .label-input {
            display: flex;
            flex-direction: row;
            align-items: center;
        }

        label {
            display: block;
            font-weight: 600;
            font-size: 20px;
            width: 320px; 
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            width: 100%; 
        }
        input[type="submit"], .connexion {
            background-color: #00B; 
            color: white;
            padding: 15px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: flex;
            justify-content: center;
            text-decoration: none;
        }
        input[type="submit"]:hover {
            background-color: #adbce6; 
        }
        ::-webkit-scrollbar{
            width: 12px;
            height: 12px;
            background-color: #000;
        }

        ::-webkit-scrollbar-track{
            background: none;
        }

        ::-webkit-scrollbar-thumb{
            background-color: #DCDCDC;
            border-radius: 12px;
        }

        ::-webkit-scrollbar-thumb:hover{
            background-color: #C0C0C0;
            border-radius: 12px;
        }

        ::-webkit-scrollbar-corner{
            background: none;
        }
    </style>
</head>
<body>
    <?php 
        include './components/header.php';
    ?>
    <h1>Connexion</h1>
    <form action="connexion.php" method="post">
        <div class="form-group">
            <div class="label-input">
                <label for="email">Addresse Mail :</label>
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
