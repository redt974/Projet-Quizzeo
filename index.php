<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="icon" href='./assets/quizzeo.ico' />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./style/index.css">
</head>
<body>
    <?php
    session_start();
    if (!isset ($_SESSION['email'])) {
        header('location: connexion.php');
    }
    include './components/header.php';
    ?>
    <video id="background-video" autoplay loop muted>
        <source src="./assets/0319.mp4">
    </video>
    <?php
    // Fonction pour activer ou désactiver un utilisateur ou un quiz dans le fichier CSV
    function active($file_name, $id, $index)
    {
        // Ouvrir le fichier en mode lecture
        if (($file = fopen($file_name, "r")) !== false) { // Mode lecture
            // Tableau pour stocker les données du fichier
            $donnees = [];

            // Lire chaque ligne du fichier
            while (($row = fgetcsv($file)) !== false) {
                if ($id === $row[0]) {
                    // Inverser l'état du statut dans la colonne spécifiée
                    $row[$index] = ($row[$index] == 'active') ? 'desactive' : 'active';
                }
                // Ajouter la ligne au tableau de données
                $donnees[] = $row;
            }

            // Fermer le fichier
            fclose($file);

            // Ouvrir le fichier en mode écriture
            if (($file = fopen($file_name, "w")) !== false) { // Mode écritue
                // Écrire les données modifiées dans le fichier
                foreach ($donnees as $ligne) {
                    fputcsv($file, $ligne);
                }

                // Fermer le fichier
                fclose($file);
            }
        }
    }

    // Vérifier si un utilisateur a été sélectionné pour activer ou désactiver
    if (isset ($_POST['user_id']) && isset ($_POST['action_user'])) {
        // Récupérer l'ID de l'utilisateur et l'action à effectuer depuis le formulaire
        $user_id = $_POST['user_id'];
        $action = $_POST['action_user'];

        // Effectuer l'action en fonction du bouton cliqué
        if ($action === 'Activer' || $action === 'Désactiver') {
            active('utilisateurs.csv', $user_id, 7);
        }
    }

    // Vérifier si un quiz a été sélectionné pour lancer ou arrêter
    if (isset ($_POST['quiz_id']) && isset ($_POST['action_quiz'])) {
        // Récupérer l'ID du quiz et l'action à effectuer depuis le formulaire
        $quiz_id = $_POST['quiz_id'];
        $action = $_POST['action_quiz'];

        // Effectuer l'action en fonction du bouton cliqué
        if ($action === 'Activer' || $action === 'Désactiver') {
            active('user_quiz.csv', $quiz_id, 7);
        }
    }

    // Afficher le tableau des utilisateurs 
    if ($_SESSION['role'] == 'admin') {
        // Tableau des utilisateurs :
    
        // Ouvrir le fichier CSV en lecture
        $file = fopen('utilisateurs.csv', 'r');
        // Ignorer la première ligne
        fgetcsv($file);

        if (($row = fgetcsv($file)) !== false) {
            // Afficher le tableau des utilisateurs
            echo '<main class="table" id="customers_table">
                    <section class="table__header">
                        <h1>Tableau des utilisateurs :</h1>
                        <div class="input-group">
                            <input type="search" placeholder="Rechercher...">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                    </section>
                    <section class="table__body">
                        <table>
                            <thead>
                            <tr>
                                <th>Prénom</th>
                                <th>Nom</th>
                                <th>Rôle</th>
                                <th>Status</th>
                                <th>Activate</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>';

            while (($row = fgetcsv($file)) !== false) {
                if ($row[5] != "admin") {
                    echo "<tr>
                            <td>{$row[1]}</td>
                            <td>" . strtoupper($row[2]) . "</td>
                            <td>" . strtoupper($row[5]) . "</td>
                            <td><p class='status " . ($row[6] == 'connected' ? 'delivered' : 'cancelled') . "'>• " . ucwords($row[6]) . "</p></td>
                            <td><p class='status " . ($row[7] == 'active' ? 'delivered' : 'cancelled') . "'>• " . ucwords($row[7]) . "</p></td>
                            <td>
                                <form method='post' action='index.php'>
                                    <input type='hidden' name='user_id' value='{$row[0]}'>
                                    <input type='hidden' name='action_user' value='" . ($row[7] == 'active' ? 'Désactiver' : 'Activer') . "'>
                                    <input type='submit' value='" . ($row[7] == 'active' ? 'Désactiver' : 'Activer') . "'>
                                </form>
                            </td>
                        </tr>";
                }
            }
            echo '</tbody>
                </table>
            </section>
            </main>';

            // Fermer le fichier
            fclose($file);
        
        // Tableau des quiz :

        $file = fopen('user_quiz.csv', 'r');

        fgetcsv($file);

        if (($row = fgetcsv($file)) !== false) {
            fclose($file);

            // Ouvrir le fichier CSV en lecture
            $file = fopen('user_quiz.csv', 'r');
            // Ignorer la première ligne
            fgetcsv($file);
            // Afficher le tableau des utilisateurs
            echo '<main class="table" id="customers_table">
            <section class="table__header">
                <h1>Tableau des quiz :</h1>
                <div class="input-group">
                    <input type="search" placeholder="Rechercher...">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
            </section>
            <section class="table__body">
                <table>
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>';
                while (($row = fgetcsv($file)) !== false) {
                    echo "<tr>
                            <td>" . $row[2] . "</td>
                            <td>" . $row[3] . "</td>
                            <td><p class='status " . ($row[6] == 'lance' ? 'delivered' : 'cancelled') . "'>• " . ucwords($row[6]) . "</p></td>
                            <td>
                                <form method='post' action='index.php'>
                                    <input type='hidden' name='quiz_id' value='{$row[0]}'>
                                    <input type='hidden' name='action_quiz' value='" . ($row[7] == 'active' ? 'Désactiver' : 'Activer') . "'>
                                    <input type='submit' value='" . ($row[7] == 'active' ? 'Désactiver' : 'Activer') . "'>
                                </form>
                            </td>
                        </tr>";
                    } 
                }
            echo '</tbody>
                </table>
            </section>
            </main>';

            // Fermer le fichier
            fclose($file);
        } else {
            echo "<div>Vous n'avez pas encore créer de quiz ! Faites-en un dès maintenant !</div>";
        }

        // Tableau de vos quiz terminés:

        function quizTitre($id_quiz)
        {
            $quiz_titre = "";

            // Ouvrir le fichier CSV en lecture
            $file = fopen('user_quiz.csv', 'r');
            // Ignorer la première ligne
            fgetcsv($file);

            while (($row = fgetcsv($file)) !== false) {
                if ($id_quiz == $row[0]) {
                    $quiz_titre = $row[2];
                }
            }

            fclose($file);
            return $quiz_titre;
        }

        // Ouvrir le fichier CSV en lecture
        $file = fopen('user_result_game.csv', 'r');
        // Ignorer la première ligne
        fgetcsv($file);

        if (($row = fgetcsv($file)) !== false) {
            fclose($file);

            // Ouvrir le fichier CSV en lecture
            $file = fopen('user_result_game.csv', 'r');
            // Ignorer la première ligne
            fgetcsv($file);
            // Afficher le tableau des quiz

            echo '<main class="table" id="customers_table">
            <section class="table__header">
                <h1>Tableau des quiz :</h1>
                <div class="input-group">
                    <input type="search" placeholder="Rechercher...">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
            </section>
            <section class="table__body">
                <table>
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Résultat</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>';
            while (($row = fgetcsv($file)) !== false) {
                if ($row[4] != -1 ){
                    $user = quizUser($row[1]);
                    echo "<tr>
                                <td>" . ucwords(quizTitre($row[2])) . "</td>
                                <td>" . $user[0] . " ".$user[1]."</td>
                                <td>" . $row[3] . "/" . $row[4] . "</td>
                                <td>" . $row[5] . "</td>
                                <td><p class='status shipped'>" . ucwords($row[6]) . "</p></td>
                            </tr>";
                }
          echo      '</table>
                </section>
                </main>';
                fclose($file);
            } 
        } else {
            fclose($file);
            echo "<h2>Aucun quiz n'a été terminé ! Revenez plus tard !</h2>"; 
        }
    }

    ?>
    <?php if ($_SESSION['role'] == 'school' || $_SESSION['role'] == 'company'): ?>
        <div class="btnbtn">
            <h2>Cliquez ici pour créer un nouveau quiz :</h2>
            <a id="btn" href="quiz.php">Ajouter</a>
        </div>
        <?php

        // Fonction pour activer ou désactiver un utilisateur dans le fichier CSV
        function lancer($id)
        {
            // Ouvrir le fichier en mode lecture
            $file = fopen('user_quiz.csv', "r");

            // Vérifier si le fichier a bien été ouvert
            if ($file !== false) {

                // Tableau pour stocker les données du fichier
                $donnees = [];

                // Lire chaque ligne du fichier
                while (($row = fgetcsv($file)) !== false) {
                    // Vérifier si l'ID correspond à celui recherché
                    if ($id === $row[0]) {
                        // Inverser l'état du statut dans la colonne spécifiée
                        $row[6] = ($row[6] == 'lance') ? 'en cours' : 'lance';
                    }
                    // Ajouter la ligne au tableau de données
                    $donnees[] = $row;
                }

                // Fermer le fichier
                fclose($file);

                // Ouvrir le fichier en mode écriture
                if (($file = fopen('user_quiz.csv', "w")) !== false) { // Mode écritue
                    // Écrire les données modifiées dans le fichier
                    foreach ($donnees as $ligne) {
                        fputcsv($file, $ligne);
                    }

                    // Fermer le fichier
                    fclose($file);
                }
            }
        }

        // Vérifier si un utilisateur a été sélectionné pour activer ou désactiver
        if (isset ($_POST['quiz_id']) && isset ($_POST['status_quiz'])) {
            // Récupérer l'ID de l'utilisateur et l'action à effectuer depuis le formulaire
            $quiz_id = $_POST['quiz_id'];
            $action = $_POST['status_quiz'];

            // Effectuer l'action en fonction du bouton cliqué
            if ($action === 'Lancé' || $action === 'En cours') {
                lancer($quiz_id);
            }
        }
        
        // Tableau des quiz :
    
        function nombreResult($id_quiz) {
            $nb_result = 0;

            // Ouvrir le fichier CSV en lecture
            $file = fopen('user_result_game.csv', 'r');
            // Ignorer la première ligne
            fgetcsv($file);

            while (($row = fgetcsv($file)) !== false) {
                if ($id_quiz === $row[2]) {
                    $nb_result += 1;
                }
            }

            fclose($file);
            return $nb_result;
        }

        // Ouvrir le fichier CSV en lecture
        $file = fopen('user_quiz.csv', 'r');
        // Ignorer la première ligne
        fgetcsv($file);

        // Afficher le tableau des quiz
            echo '<main class="table" id="customers_table">
                    <section class="table__header">
                        <h1>Tableau des quiz :</h1>
                        <div class="input-group">
                            <input type="search" placeholder="Rechercher...">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                    </section>
                    <section class="table__body">
                        <table>
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Description</th>
                                    <th>Réponses</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>';
        while (($row = fgetcsv($file)) !== false) {
            if ($_SESSION['id'] === $row[1]) {
                echo "<tr>
                        <td>" . $row[2] . "</td>
                        <td>" . $row[3] . "</td>
                        <td>" . nombreResult($row[0]) . "</td>
                        <td><p class='status " . ($row[6] == 'lance' ? 'delivered' : 'cancelled') . "'>• " . ucwords($row[6]) . "</p></td>
                        <td>                                
                        <form method='post'>
                            <input type='hidden' name='quiz_id' value='{$row[0]}'>
                            <input type='hidden' name='status_quiz' value='" . ($row[6] == 'lance' ? 'En cours' : 'Lancé') . "'>
                            <input type='submit' value='" . ($row[6] == 'lance' ? 'En cours' : 'Lancé') . "'>
                        </form>
                        </td>
                    </tr>";
            } else if ($row == false) {
                echo "<div>Vous n'avez pas encore créer de quiz ! Faites-en un dès maintenant !</div>";
            }
        }
            echo '</tbody>
            </table>
        </section>
        </main>';
        // Fermer le fichier
        fclose($file);

        // Tableau des QCM terminés :
        
        function quizTitre($id_quiz)
        {
            $quiz_titre = "";

            // Ouvrir le fichier CSV en lecture
            $file = fopen('user_quiz.csv', 'r');
            // Ignorer la première ligne
            fgetcsv($file);

            while (($row = fgetcsv($file)) !== false) {
                if ($id_quiz == $row[0]) {
                    $quiz_titre = $row[2];
                }
            }

            fclose($file);
            return $quiz_titre;
        }

        function quizUser($id_user)
        {
            $nom = "";
            $prenom = "";

            // Ouvrir le fichier CSV en lecture
            $file = fopen('utilisateurs.csv', 'r');
            // Ignorer la première ligne
            fgetcsv($file);

            while (($row = fgetcsv($file)) !== false) {
                if ($id_user == $row[0]) {
                    $nom = $row[2];
                    $prenom = $row[1];
                }
            }

            fclose($file);
            return [$nom, $prenom];
        }

        // Ouvrir le fichier CSV en lecture
        $file = fopen('user_result_game.csv', 'r');
        // Ignorer la première ligne
        fgetcsv($file);

        if (($row = fgetcsv($file)) !== false) {
            fclose($file);

            // Ouvrir le fichier CSV en lecture
            $file = fopen('user_result_game.csv', 'r');
            // Ignorer la première ligne
            fgetcsv($file);
            // Afficher le tableau des quiz

            echo '<main class="table" id="customers_table">
            <section class="table__header">
                <h1>Tableau des QCM terminés :</h1>
                <div class="input-group">
                    <input type="search" placeholder="Rechercher...">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
            </section>
            <section class="table__body">
                <table>
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Utilisateur</th>
                            <th>Résultat</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>';
            while (($row = fgetcsv($file)) !== false) {
                if ($row == false) {
                    echo "<div>Personne n'a fait l'un de vos quiz ! Revenez plus tard !</div>";
                } else if ($row[4] != -1 ){
                    $user = quizUser($row[1]);
                    echo "<tr>
                                <td>" . ucwords(quizTitre($row[2])) . "</td>
                                <td>" . $user[0] . " ".$user[1]."</td>
                                <td>" . $row[3] . "/" . $row[4] . "</td>
                                <td>" . $row[5] . "</td>
                                <td><p class='status shipped'>" . ucwords($row[6]) . "</p></td>
                            </tr>";
                }
            } 
            echo '</tbody>
            </table>
        </section>
        </main>';
        fclose($file);
        }

        if ($_SESSION['role'] == 'company'){

            // Ouvrir le fichier CSV en lecture
            $file = fopen('user_quiz_free_answer.csv', 'r');
            // Ignorer la première ligne
            fgetcsv($file);

            if (($row = fgetcsv($file)) !== false) {
                fclose($file);

                // Ouvrir le fichier CSV en lecture
                $file = fopen('user_quiz_free_answer.csv', 'r');
                // Ignorer la première ligne
                fgetcsv($file);
                // Afficher le tableau des quiz

                echo '<main class="table" id="customers_table">
                <section class="table__header">
                    <h1>Tableau des quiz à réponses libres :</h1>
                    <div class="input-group">
                        <input type="search" placeholder="Rechercher...">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                </section>
                <section class="table__body">
                    <table>
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Utilisateur</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Résultat</th>
                            </tr>
                            </thead>
                            <tbody>';
                while (($row = fgetcsv($file)) !== false) {
                    if ($row == false) {
                        echo "<div>Personne n'a fait l'un de vos quiz ! Revenez plus tard !</div>";
                    } else {
                        $user = quizUser($row[1]);
                        echo "<tr>
                                <td>" . ucwords(quizTitre($row[2])) . "</td>
                                <td>" . $user[0] . " ".$user[1]."</td>
                                <td>" . $row[5] . "</td>
                                <td><p class='status shipped'>" . ucwords($row[6]) . "</p></td>
                                <td>
                                    <form id='gameForm' action='index.php' method='post'>
                                        <input type='hidden' name='view_user_id' value='{$_SESSION['id']}' />
                                        <input type='hidden' name='view_quiz_id' value='{$row[2]}'>
                                        <input type='submit' value='Voir'>    
                                    </form>
                                </td>
                            </tr>";
                    }
                } 
                echo '</tbody>
                </table>
            </section>
            </main>';
            fclose($file);

            echo "<h1>↓ Les réponses au quiz s'afficheront juste en dessous ! ↓</h1>";

            }

            function questionTitre($question_id){
                $titre = "";
    
                // Ouvrir le fichier CSV en lecture
                $file = fopen('user_quiz_question.csv', 'r');
                // Ignorer la première ligne
                fgetcsv($file);
    
                while (($row = fgetcsv($file)) !== false) {
                    if ($question_id == $row[0]) {
                        $titre = $row[2];
                    }
                }
    
                fclose($file);
                return $titre;
            }

            // Vérifier si un utilisateur a été sélectionné pour activer ou désactiver
            if (isset ($_POST['view_quiz_id']) && isset ($_POST['view_user_id'])) {
                // Récupérer l'ID de l'utilisateur et l'action à effectuer depuis le formulaire
                $quiz_id = $_POST['view_quiz_id'];
                $user_id = $_POST['view_user_id'];

                // Ouvrir le fichier CSV en lecture
                $file = fopen('user_quiz_free_answer.csv', 'r');
                // Ignorer la première ligne
                fgetcsv($file);
                // Afficher des réponses libres

                echo '<main class="table">
                <section>
                    <h1>Réponses au Quiz :</h1>
                </section>
                <section class="table__body">
                    <table>
                        <thead>
                            <tr>
                                <th>Question</th>
                                <th>Réponse</th>
                            </tr>
                            </thead>
                            <tbody>'; 
                while (($row = fgetcsv($file)) !== false) {
                 if ($row == false) {
                        echo "<div>Personne n'a fait l'un de vos quiz ! Revenez plus tard !</div>";
                    } else {
                        echo "<tr>
                                <td>". questionTitre($row[3]) ."</td>
                                <td>" . $row[4] . "</td>
                            </tr>";
                    }
                }
                echo '</tbody>
                </table>
            </section>
            </main>';
            fclose($file);
            }
        } 
        ?>
    <?php endif; ?>
    <?php if ($_SESSION['role'] == 'user'): ?>
        <?php
        // Tableau des quiz terminés :
        
        function quizTitre($id_quiz)
        {
            $quiz_titre = "";

            // Ouvrir le fichier CSV en lecture
            $file = fopen('user_quiz.csv', 'r');
            // Ignorer la première ligne
            fgetcsv($file);

            while (($row = fgetcsv($file)) !== false) {
                if ($id_quiz == $row[0]) {
                    $quiz_titre = $row[2];
                }
            }

            fclose($file);
            return $quiz_titre;
        }

        function quizUser($id_user)
        {
            $nom = "";
            $prenom = "";

            // Ouvrir le fichier CSV en lecture
            $file = fopen('utilisateurs.csv', 'r');
            // Ignorer la première ligne
            fgetcsv($file);

            while (($row = fgetcsv($file)) !== false) {
                if ($id_user == $row[0]) {
                    $nom = $row[2];
                    $prenom = $row[1];
                }
            }

            fclose($file);
            return [$nom, $prenom];
        }

        // Ouvrir le fichier CSV en lecture
        $file = fopen('user_result_game.csv', 'r');
        // Ignorer la première ligne
        fgetcsv($file);
        // Afficher le tableau des quiz

        echo '<main class="table" id="customers_table">
        <section class="table__header">
            <h1>Tableau des quiz terminés :</h1>
            <div class="input-group">
                <input type="search" placeholder="Rechercher...">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
        </section>
        <section class="table__body">
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Utilisateur</th>
                        <th>Résultat</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>';
        while (($row = fgetcsv($file)) !== false) {
            if ($_SESSION['id'] == $row[1] && $row[4] != -1 ){
                $user = quizUser($row[1]);
                echo "<tr>
                            <td>" . ucwords(quizTitre($row[2])) . "</td>
                            <td>" . $user[0] . " ".$user[1]."</td>
                            <td>" . $row[3] . "/" . $row[4] . "</td>
                            <td>" . $row[5] . "</td>
                            <td><p class='status shipped'>" . ucwords($row[6]) . "</p></td>
                        </tr>";
            } 
        } 
        echo '</tbody>
        </table>
    </section>
    </main>';
    fclose($file);


        // Slider des quiz :                
        function getRole($id_user){
            $role = "";

            // Ouvrir le fichier CSV en lecture
            $file = fopen('utilisateurs.csv', 'r');
            // Ignorer la première ligne
            fgetcsv($file);

            while (($row = fgetcsv($file)) !== false) {
                if ($id_user == $row[0]) {
                    $role = $row[5];
                }
            }

            fclose($file);
            return $role;
        }

        function isDone($id_user, $id_quiz){

            // Ouvrir le fichier CSV en lecture
            $file = fopen('user_result_game.csv', 'r');
            // Ignorer la première ligne
            fgetcsv($file);

            while (($row = fgetcsv($file)) !== false) {
                if ($id_quiz == $row[2] && $id_user == $row[1]) {
                    return true;
                }
            }

            fclose($file);
            return false;
        }

        // Ouvrir le fichier CSV en lecture
        $file = fopen('user_quiz.csv', 'r');
        // Ignorer la première ligne
        fgetcsv($file);

        if (($row = fgetcsv($file)) !== false) {
            fclose($file);

            echo '<div class="container">
            <div class="slide">';

            // Tableau des URLs des images
            $image_urls = [
                'school' => "https://myviewboard.com/blog/wp-content/uploads/2020/08/MP0027-01-scaled.jpg",
                'company'=> "https://img-0.journaldunet.com/la7i_1Y8UNwnsDRdLYjaR2CHPKA=/1500x/smart/da9bdec385c74c66b032708cfe1453a6/ccmcms-jdn/28990032.jpg",
            ];

            // Ouvrir le fichier CSV en lecture
            $file = fopen('user_quiz.csv', 'r');

            $compteur = 0;

            $information = [$row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7]];

            // Vérifier si le fichier contient des données 
            fgetcsv($file);

            while (($row = fgetcsv($file)) !== false) {
                // Boucler à travers les lignes du fichier CSV
                if ($row[7] == 'active' && $row[6] == 'lance' && !isDone($_SESSION['id'], $row[0])) {
                    // Récupérer l'URL de l'image ou utiliser l'URL correspondante dans le tableau $image_urls
                    $image_url = !empty ($row[5]) ? $row[5] : $image_urls[getRole($row[1])];

                    // Afficher la diapositive du carousel avec les données du fichier CSV
                    echo "<div class='item' style='background-image: url(" . $image_url . ");'>
                            <div class='content' style='text-align: center;'>
                            <div class='name'>" . $row[2] . "</div>
                            <div class='des'>" . $row[3] . "</div>
                            <button class='game' onclick='startGame(" . $row[0] . ")' style='margin: 0 auto;'>Start</button>";

                    echo "</div>
                    </div>";

                    $compteur += 1;
                }
            }

            if ($compteur == 1 && $row == false) {
                if ($information[7] == 'active' && $information[6] == 'lance' && !isDone($_SESSION['id'], $row[$information[0]])) {
                    // Récupérer l'URL de l'image ou utiliser l'URL correspondante dans le tableau $image_urls
                    $image_url = !empty ($information[5]) ? $information[5] : $image_urls[getRole($row[$information[1]])];

                    // Afficher la diapositive du carousel avec les données du fichier CSV
                    echo "<div class='item' style='background-image: url(" . $image_url . ") display: block;'>
                            <div class='content' style='text-align: center;'>
                            <div class='name'>" . $information[2] . "</div>
                            <div class='des'>" . $information[3] . "</div>
                            <button class='game' onclick='startGame(" . $information[0] . ")' style='margin: 0 auto;'>Start</button>";

                    echo "</div>
                    </div>";
                }
            }

            // Fermer le fichier
            fclose($file);

            if ($compteur >= 2) {
                echo '</div>
                <div class="button">
                    <button class="prev"><i class="fa-solid fa-arrow-left"></i></button>
                    <button class="next"><i class="fa-solid fa-arrow-right"></i></button>
                </div>
                </div>

                <!-- Formulaire masqué pour envoyer les informations du quiz en méthode post -->
                <form id="gameForm" action="game.php" method="post" style="display: none;">
                    <input type="hidden" name="quiz_id" id="quiz_id">
                    <input type="hidden" name="user_id" value="' . $_SESSION['id'] . '">
                </form>';
            }

        } else {
            fclose($file);
            echo "<h2>Aucun quiz n'a été trouvé ! Revenez plus tard !</h2>"; 
        }
        ?>

        <script src="./script/index.js"></script>
    <?php endif; ?>
</body>

</html>