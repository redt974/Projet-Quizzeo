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
        // Vérifier si le formulaire a été soumis
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Lire le fichier CSV
            $utilisateurs = array_map('str_getcsv', file('utilisateurs.csv'));

            // Récupérer l'ID de l'utilisateur connecté
            $email = $_SESSION['email'];
            $id_utilisateur = null;
            foreach ($utilisateurs as $utilisateur) {
                if ($utilisateur[3] == $email) {
                    $id_utilisateur = $utilisateur[0];
                    break;
                }
            }

            // Récupérer les valeurs du formulaire
            $ancien_mot_de_passe = $_POST['ancien_mot_de_passe'];
            $nouveau_mot_de_passe = $_POST['nouveau_mot_de_passe'];
            $confirmer_mot_de_passe = $_POST['confirmer_mot_de_passe'];

            // Rechercher l'utilisateur dans le fichier CSV
            $trouve = false;
            foreach ($utilisateurs as &$utilisateur) {
                if ($utilisateur[0] == $id_utilisateur) {
                    // Vérifier l'ancien mot de passe
                    if (password_verify($ancien_mot_de_passe, $utilisateur[4])) {
                        // Vérifier la correspondance des nouveaux mots de passe
                        if ($nouveau_mot_de_passe == $confirmer_mot_de_passe) {
                            // Mettre à jour le mot de passe dans le tableau des utilisateurs
                            $utilisateur[4] = password_hash($nouveau_mot_de_passe, PASSWORD_DEFAULT);
                            $trouve = true;
                            break;
                        } else {
                            echo "Les nouveaux mots de passe ne correspondent pas.";
                        }
                    } else {
                        echo "Ancien mot de passe incorrect.";
                    }
                }
            }

            // Écrire les modifications dans le fichier CSV
            if ($trouve) {
                $file = fopen('utilisateurs.csv', 'w');
                foreach ($utilisateurs as $utilisateur) {
                    fputcsv($file, $utilisateur);
                }
                fclose($file);
                echo "Mot de passe modifié avec succès.";
            } else {
                echo "Utilisateur introuvable ou erreur de mot de passe.";
            }
        }
        ?>
    </div>

</body>
</html>
