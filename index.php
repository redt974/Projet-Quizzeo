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
        if(!isset($_SESSION['email'])){
            header('location: connexion.php');
        }
        include './components/header.php';

        echo '<video id="background-video" autoplay loop muted>
                <source src="./assets/background6.mp4">
              </video>';
        if ($_SESSION['role'] == 'admin'){
            // Ouvrir le fichier CSV en lecture
            $file = fopen('utilisateurs.csv', 'r');
            // Ignorer la première ligne
            fgetcsv($file);

            // Afficher le tableau des utilisateurs 
            echo "<br/><br/><br/><br/><br/><br/>
            <h1>Tableau des utilisateurs :</h1>
            <table>
                    <thead>
                        <tr>
                            <th>Prenom</th>
                            <th>Nom</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>";
            while (($row = fgetcsv($file)) !== false) {
                echo "<tr>
                        <td>" .$row[1]. "</td>
                        <td>" .strtoupper($row[2]). "</td>
                        <td>" .strtoupper($row[5]). "</td>
                        <td>• " .strtoupper($row[6]). "</td>
                        <td><button>Active</button></td>
                    </tr>";
            } 
            echo "</tbody>
            </table><br/><br/><br/><br/><br/><br/>";
            // Fermer le fichier
            fclose($file);
        }
    ?>
    <?php if ($_SESSION['role'] == 'school' || $_SESSION['role'] == 'company') : ?>
        <a class="quiz" href="quiz.php">Add Quiz</a>
    <?php endif; ?>
    <?php if ($_SESSION['role'] == 'user' || $_SESSION['role'] == 'admin') : ?>
        <!-- Slider des quiz crées -->
        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <?php
                    // Ouvrir le fichier CSV en lecture
                    $file = fopen('user_quiz.csv', 'r');

                    // Compteur pour suivre l'index de la diapositive
                    $index = 0;

                    // Vérifier si le fichier contient des données 
                    if (($row = fgetcsv($file)) !== false) {
                        // Vérifier si la ligne suivante contient des données
                        while (($row = fgetcsv($file)) !== false) {
                            if ($row !== null) {
                                // Incrémenter l'index de la diapositive
                                $index++;

                                // Afficher l'indicateur
                                $active_class = ($index === 1) ? 'active' : '';
                                echo "<button type='button' data-bs-target='#carouselExampleCaptions' data-bs-slide-to='$index' class='$active_class' aria-current='true' aria-label='Slide $index'></button>";
                            }
                        }
                    }
                    // Fermer le fichier
                    fclose($file);
                ?>
            </div>
            <div class="carousel-inner">
                <?php
                    // Ouvrir le fichier CSV en lecture
                    $file = fopen('user_quiz.csv', 'r');

                    // Tableau contenant les noms de fichiers des images
                    $images = array(
                        "space.jpg",
                        "world.jpg",
                        "ucl.jpg",
                        "dbz.jpg",
                        "cinema.jpg",
                        "logo.webp",
                        "geo.jpg",
                        "animaux.png"
                    );

                    // Compteur pour suivre l'index de la diapositive
                    $index = 0;

                    // Vérifier si le fichier contient des données 
                    fgetcsv($file);
                        // Vérifier si la ligne suivante contient des données
                        while (($row = fgetcsv($file)) !== false) {
                            if ($row !== null) {
                                // Incrémenter l'index de la diapositive
                                $index++;

                                // Vérifier si c'est la première diapositive, pour la marquer comme active
                                $active_class = ($index === 1) ? 'active' : '';

                                // Sélectionner l'image correspondante à l'index actuel
                                $image = isset($images[$index - 1]) ? $images[$index - 1] : '';

                                // Afficher la diapositive du carousel avec les données du fichier CSV
                                echo "<div class='carousel-item $active_class'>
                                        <img src='./assets/$image' class='d-block w-100' alt='Slide Image'>
                                        <div class='carousel-caption d-none d-md-block'>
                                            <!-- Utilisez les données du fichier CSV pour le titre et la description -->
                                            <h5>" . $row[2] . "</h5>
                                            <p>" . $row[3] . "</p>";
                                            if ($_SESSION['role'] == 'user'){
                                                echo "<button class='quiz game' onclick='startGame(" . $row[0] . ")'>Start</button>";
                                            }
                                        echo "</div>
                                    </div>";
                            }
                        }

                    // Fermer le fichier
                    fclose($file);
                ?>
                <?php if ($_SESSION['role'] == 'user') : ?>
                <!-- Formulaire masqué pour envoyer les informations du quiz en méthode post -->
                <form id="gameForm" action="game.php" method="post" style="display: none;">
                    <input type="hidden" name="quiz_id" id="quiz_id">
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['id']; ?>">
                </form>
                <script>
                    function startGame(quizId) {
                        document.getElementById('quiz_id').value = quizId;
                        document.getElementById('gameForm').submit();
                    }
                </script>
                <?php endif; ?>
            </div>
            <!-- Boutons de contrôle -->
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    <?php endif; ?>
</body>
</html>
