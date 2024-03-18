<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="icon" href='./assets/quizzeo.ico' />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./style/index.css">
</head>

<body>
    <?php
        session_start();
        if(!isset($_SESSION['email'])){
            header('location: connexion.php');
        }
        include './components/header.php';
    ?>
    <video id="background-video" autoplay loop muted>
        <source src="./assets/background6.mp4">
    </video>
    <?php
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
    <div class="container">
        <div class="slide">
            <?php
                // Tableau des URLs des images
                $image_urls = [
                    "https://i.ibb.co/qCkd9jS/img1.jpg",
                    "https://i.ibb.co/jrRb11q/img2.jpg",
                    "https://i.ibb.co/NSwVv8D/img3.jpg",
                    "https://i.ibb.co/Bq4Q0M8/img4.jpg",
                    "https://img.freepik.com/premium-photo/lakes_972708-78.jpg?size=626&ext=jpg&ga=GA1.1.735520172.1710288000&semt=ais"
                ];

                // Ouvrir le fichier CSV en lecture
                $file = fopen('user_quiz.csv', 'r');

                // Index de l'image à utiliser
                $image_index = 0;

                // Vérifier si le fichier contient des données 
                fgetcsv($file);

                // Boucler à travers les lignes du fichier CSV
                while (($row = fgetcsv($file)) !== false) {
                    if ($row !== null) {
                        // Récupérer l'URL de l'image ou utiliser l'URL correspondante dans le tableau $image_urls
                        $image_url = !empty($row[4]) ? $row[4] : $image_urls[$image_index];

                        // Afficher la diapositive du carousel avec les données du fichier CSV
                        echo "<div class='item' style='background-image: url(" . $image_url . ");'>
                                <div class='content'>
                                    <div class='name'>" . $row[2] . "</div>
                                    <div class='des'>" . $row[3] . "</div>";
                        if ($_SESSION['role'] == 'user'){
                            echo "<button class='game' onclick='startGame(" . $row[0] . ")'>Start</button>";
                        }
                        echo "</div>
                            </div>";

                        // Incrémenter l'index pour la prochaine itération
                        $image_index++;
                        // Si l'index dépasse la taille du tableau, réinitialiser à zéro
                        if ($image_index >= count($image_urls)) {
                            $image_index = 0;
                        }
                    }
                }

                // Fermer le fichier
                fclose($file);
            ?>
        </div>
        <div class="button">
            <button class="prev"><i class="fa-solid fa-arrow-left"></i></button>
            <button class="next"><i class="fa-solid fa-arrow-right"></i></button>
        </div>
    </div>
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
    <script>
        let next = document.querySelector('.next')
        let prev = document.querySelector('.prev')

        next.addEventListener('click', function () {
            let items = document.querySelectorAll('.item')
            document.querySelector('.slide').appendChild(items[0])
        })

        prev.addEventListener('click', function () {
            let items = document.querySelectorAll('.item')
            document.querySelector('.slide').prepend(items[items.length - 1]) // here the length of items = 6
        })
    </script>































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

    <?php endif; ?>
</body>

</html>