<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header('location: connexion.php');
    exit; // Arrêter l'exécution du script
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lire le fichier CSV
    if (($file = fopen("utilisateurs.csv", "r+")) !== false) {
        // Tableau pour stocker les données du fichier
        $donnees = [];

        // Lire chaque ligne du fichier
        while (($ligne = fgetcsv($file)) !== false) {
            if ($_SESSION["id"] === $ligne[0] && $_SESSION["email"] === $ligne[3]) {
                // Vérifier l'ancien mot de passe
                if (password_verify($_POST['ancien_mot_de_passe'], $ligne[4])) {
                    // Vérifier la correspondance des nouveaux mots de passe
                    if ($_POST['nouveau_mot_de_passe'] == $_POST['confirmer_mot_de_passe']) {
                        // Mettre à jour le mot de passe dans le fichier
                        $ligne[4] = password_hash($_POST['nouveau_mot_de_passe'], PASSWORD_DEFAULT);
                        echo "Mot de passe modifié avec succès.";
                        $trouve = true;
                    } else {
                        echo "Les nouveaux mots de passe ne correspondent pas.";
                    }
                } else {
                    echo "Ancien mot de passe incorrect.";
                }
            }

            // Ajouter la ligne au tableau de données
            $donnees[] = $ligne;
        }

        // Réécrire le fichier avec les données modifiées
        ftruncate($file, 0); // Tronquer le fichier
        rewind($file); // Rembobiner le pointeur du fichier
        foreach ($donnees as $ligne) {
            fputcsv($file, $ligne);
        }

        // Fermer le fichier
        fclose($file);
        
        if ($trouve) {
            header('location: utilisateur.php');
            exit;
        }
    }
} else {
    // Rediriger vers une page d'erreur si le formulaire n'a pas été soumis correctement
    header('location: utilisateur.php');
    exit;
}
?>
