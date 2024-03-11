<?php
session_start();

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $email = $_POST["email"];
    $password = $_POST["password"];

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
        fputcsv($file, [$nextUserId, $fname, $lname, $email, $hashedPassword]);
        fclose($file);

        // Create a session for the new user
        $_SESSION["email"] = $email;

        // Initialize an empty favorites array for the user if not already set
        if (!isset($_SESSION["favorites"])) {
            $_SESSION["favorites"] = [];
        }

        echo "Registration successful!";

        // Redirect to quiz.php
        header("Location: quiz.php");
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
?>