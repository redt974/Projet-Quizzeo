<?php
    session_start();

    // Déconnexion dans le fichier csv :

    // Ouvrir le fichier en mode lecture
    if (($file = fopen("utilisateurs.csv", "r")) !== false) { // Mode lecture
        // Tableau pour stocker les données du fichier
        $donnees = [];

        // Lire chaque ligne du fichier
        while (($user = fgetcsv($file)) !== false) {
            if ($_SESSION["id"] === $user[0] && $_SESSION["email"] === $user[3]) {
                $user[6] = "disconnected";
            }

            // Ajouter la ligne au tableau de données
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
    }

    // Détruire la session
    session_destroy();
    header("Location: connexion.php");
?>