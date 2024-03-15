<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="icon" href='./assets/quizzeo.ico' />
    <link rel="stylesheet" href="./style/index.css">
</head>
<body>
    <?php
        session_start();
        if(!isset($_SESSION['email'])){
            header('location: connexion.php');
        } else {
            include './components/header.php';
        }
    ?>
    <a class="quiz" href="quiz.php">Add Quiz</a>
    <form id="gameForm" action="game.php" method="post" style="display: none;">
        <input type="hidden" name="quiz_id" id="quiz_id">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['id']; ?>">
    </form>
    <?php
        // Ouvrir le fichier CSV en lecture
        $file = fopen('user_quiz.csv', 'r');

        // Vérifier si le fichier contient des données 
        if (($row = fgetcsv($file)) !== false) {
            // Afficher le tableau des quiz
            echo "<table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Titre</th>
                            <th>Description</th>
                            <th>Jouer</th>
                        </tr>
                    </thead>
                <tbody>";

            // Vérifier si la ligne suivante contient des données
            while (($row = fgetcsv($file)) !== false) {
                if($row !== null){
                    echo "<tr>
                        <td>" . strtoupper($row[1]) . "</td>
                        <td>" . $row[2] . "</td>
                        <td>" . $row[3] . "</td>
                        <td><button class='quiz game' onclick='startGame(" . $row[0] . ")'>Start</button></td>
                    </tr>";
                } 
            }

            echo "</tbody>
                </table>";
        } 
        // Fermer le fichier
        fclose($file);
    ?>
    <script>
        function startGame(quizId) {
            document.getElementById('quiz_id').value = quizId;
            document.getElementById('gameForm').submit();
        }
    </script>
</body>
</html>
