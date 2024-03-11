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
                // Login successful, create a session for the user
                $_SESSION["email"] = $email;

                // Restaurer les favoris depuis le fichier spécifique à l'utilisateur
                if (isset($_SESSION["email"])) {
                    $email = $_SESSION["email"];
                    $userFavoritesFilename = "user_favorites_" . $email . ".csv";

                    if (file_exists($userFavoritesFilename)) {
                        // Charger les favoris depuis le fichier CSV spécifique à l'email
                        $userFavoritesContent = file_get_contents($userFavoritesFilename);

                        // Vérifier si le fichier n'est pas vide
                        if (isset($userFavoritesContent)) {
                            $userFavorites = str_getcsv($userFavoritesContent, ",", '"');
                            $_SESSION["favorites"] = $userFavorites;
                        }
                    } else{
                        $_SESSION["favorites"] = [];
                    }
                }

                fclose($file); // Close the file after reading

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