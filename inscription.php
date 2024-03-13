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

        // Validate password using regular expression
        // $passwordPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
        // if (!preg_match($passwordPattern, $password)) {
        //     echo "Password must be at least 8 characters long and include one uppercase letter, one lowercase letter, one digit, and one special character.";
        //     exit();
        // }

        // Read existing users from CSV file
        $file_name = "utilisateurs.csv";

        // Check if the user already exists
        if (userExists($file_name, $email)) {
            echo "User already exists!";
            exit();
        }

        // Determine the next available user ID
        $nextUserId = getNextUserId($file_name);

        // Save new user data to CSV file
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Append new user data to the file
        $file = fopen($file_name, "a");

        if ($file !== false) {
            fputcsv($file, [$nextUserId, $fname, $lname, $email, $hashedPassword, $role]);
            fclose($file);

            // Create a session for the new user
            $_SESSION["id"] = $nextUserId;
            $_SESSION["prenom"] = $fname;
            $_SESSION["email"] = $email;

            echo "Registration successful!";

            // Redirect to index.php
            header("Location: index.php");
            exit();
        } else {
            echo "Error writing user data.";
            exit();
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

    if(count($_POST)>0)
    {
        if(empty($_POST['g-recaptcha-response'])){
            echo "<h4>Veuillez résoudre le reCAPTCHA";
        }
        if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))
        {
            $secret="6LddDpUpAAAAANaKWBH05GUoOVS75h6qje2JEEKv";
            $response=file_get_contents('https://www.google.com/recaptchat/api/siteverify?secret'.$secret. '&response='.$_POST['g-recaptcha-response']);
            
            $data=json_decode($response);
            if($data->success){
                echo"<h2>Données envoyés !";
            }
            else{
                echo"<h2>Essayez encore !";
            }
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
    <style>
        @import url(https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap);@import url(https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap);
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-around;
            height: 100%;
            color: #000000; 
            backdrop-filter: blur(10px);
        }
        h1 {
            text-align: center;
            font-size: 56px;
            margin: 16px 0;
        }
        form {
            padding: 20px;
            background-color: rgba(255,255,255,0.4); 
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
            margin-bottom: 20px;
            width: 35%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .form-group {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-bottom: 20px;
            width: 90%;
        }

        .label-input {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 20px;
        }
        option{
            width: 300px;
        }
        label {
            display: block;
            font-size: 20px;
            font-weight: 600;
            width: 300px; 
        }
        input[type="text"],
        input[type="email"],
        input[type="password"], 
        select{
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            width: 100%;
        }
        input[type="submit"], .inscription {
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
        .switch-page{
            margin-bottom: 50px;
        }
        .g-recaptcha{
            margin: 20px 0;
        }
        ::-webkit-scrollbar{
            width: 12px;
            height: 12px;
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
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <?php 
        include './components/header.php';
    ?>
    <h1>Inscription</h1>
    <form action="inscription.php" method="post">
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
                    <option value="compagny">Entreprise</option>
                </select>
            </div>
        </div>

        <div class="g-recaptcha" data-sitekey="6LddDpUpAAAAAAeUvhSEb5l_fT8u29IGVWA40sFh"></div>

        <input type="submit" value="Inscription">
    </form>
    <div class="switch-page">
        <h2>Vous avez déjà un compte ?</h2><a class="inscription" href="connexion.php">Connectez-vous !</a>
    </div>
</body>
</html>
