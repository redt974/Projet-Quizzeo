<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION["email"])) {
    // Utiliser l'e-mail brut pour le nom du fichier
    $email = $_SESSION["email"];
    $userFavoritesFilename = "user_favorites_" . $email . ".csv";

    // Ouvrir le fichier en mode écriture
    if (!empty($_SESSION["favorites"])){
        $fileHandle = fopen($userFavoritesFilename, 'w');
        if ($fileHandle !== false) {
            // Écrire les favoris dans le fichier CSV
            fputcsv($fileHandle, $_SESSION["favorites"], ',');
            // Fermer le fichier
            fclose($fileHandle);
    
            // Fermer la session
            session_destroy();
            header("Location: index.php");
            exit();
        }
    } else {
        // Si l'utilisateur n'est pas connecté, simplement le rediriger
        header("Location: index.php");
        exit();
    }

} else {
    // Si l'utilisateur n'est pas connecté, simplement le rediriger
    header("Location: index.php");
    exit();
}
?>
