<?php  
session_start();
if(!isset($_SESSION['email'])){
    header('location: connexion.php');
    exit; // Ajout de l'instruction exit pour arrêter l'exécution du script si la session n'est pas définie
}
include './components/header.php'; 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le mot de passe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./style/forgot.css">
</head>
<body>
    <video id="background-video" autoplay loop muted>
        <source src="./assets/0319.mp4">
    </video>

    <div class="wrapper">
        <h2>Modifier le mot de passe</h2>
        <form action="modifier.php" method="post">
            <div class="input-field mb-3">
                <label for="ancien_mot_de_passe">Ancien mot de passe</label>
                <input type="password" class="form-control" id="ancien_mot_de_passe" name="ancien_mot_de_passe" required>
            </div>
            <div class="input-field mb-3">
                <label for="nouveau_mot_de_passe">Nouveau mot de passe</label>
                <input type="password" class="form-control" id="nouveau_mot_de_passe" name="nouveau_mot_de_passe" required>
            </div>
            <div class="input-field mb-3">
                <label for="confirmer_mot_de_passe">Confirmer le nouveau mot de passe</label>
                <input type="password" class="form-control" id="confirmer_mot_de_passe" name="confirmer_mot_de_passe" required>
            </div>
            <button type="submit">Modifier le mot de passe</button>
        </form>

        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Récupérer l'email de l'utilisateur connecté
                $email = $_SESSION['email'];

                // Ouvrir le fichier CSV en mode lecture/écriture
                $file = fopen('utilisateurs.csv', 'r+');

                // Vérifier si le fichier a bien été ouvert
                if ($file !== false) {
                    $trouve = false; // Indicateur si l'utilisateur est trouvé

                    // Parcourir le fichier CSV ligne par ligne
                    while (($data = fgetcsv($file)) !== false) {
                        // Vérifier si l'email correspond à celui de l'utilisateur
                        if ($data[3] == $email) {
                            // Récupérer l'ID de l'utilisateur
                            $id_utilisateur = $data[0];

                            // Vérifier l'ancien mot de passe
                            if (password_verify($_POST['ancien_mot_de_passe'], $data[4])) {
                                // Vérifier si les nouveaux mots de passe correspondent
                                if ($_POST['nouveau_mot_de_passe'] == $_POST['confirmer_mot_de_passe']) {
                                    // Mettre à jour le mot de passe hashé
                                    $data[4] = password_hash($_POST['nouveau_mot_de_passe'], PASSWORD_DEFAULT);
                                    $trouve = true; // L'utilisateur est trouvé
                                } else {
                                    echo "Les nouveaux mots de passe ne correspondent pas.";
                                }
                            } else {
                                echo "Ancien mot de passe incorrect.";
                            }
                        }
                    }

                    // Fermer le fichier
                    fclose($file);

                    // Réouvrir le fichier en mode écriture
                    $file = fopen('utilisateurs.csv', 'w');

                    // Vérifier si le fichier a bien été ouvert
                    if ($file !== false) {
                        // Écrire les données mises à jour dans le fichier
                        foreach ($utilisateurs as $utilisateur) {
                            fputcsv($file, $utilisateur);
                        }
                        // Fermer le fichier
                        fclose($file);
                        
                        // Afficher un message si l'utilisateur a été trouvé et le mot de passe a été mis à jour
                        if ($trouve) {
                            echo "Mot de passe modifié avec succès.";
                        } else {
                            echo "Utilisateur introuvable ou erreur de mot de passe.";
                        }
                    } else {
                        echo "Erreur lors de l'ouverture du fichier pour l'écriture.";
                    }
                } else {
                    echo "Erreur lors de l'ouverture du fichier pour la lecture.";
                }
            }
        ?>

    </div>

</body>
</html>
