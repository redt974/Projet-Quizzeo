<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="icon" href='./assets/quizzeo.ico' />
    <style>
        @import url(https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap);
        body {
            font-family: 'Poppins';
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .quiz {
            background-color: #00B; 
            color: white;
            margin: 50px 0 0; 
            padding: 15px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: flex;
            justify-content: center;
            text-decoration: none;
        }
        .game {
            margin: 0;
            padding: 10px 10px;
        }
        table {
            margin: 50px 0 0;
            width: 70%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #8C73B4;
        }
        th {
            background-color: #9F8AC0;
            color: white;
        }
        tr {
            font-family: 'Poppins';
            background-color: #FBDCDC;
        }
        tr:nth-child(even) {
            background-color: #FDE9C8;
        }
        ::-webkit-scrollbar {
            width: 12px;
            height: 12px;
        }
        ::-webkit-scrollbar-track {
            background: none;
        }
        ::-webkit-scrollbar-thumb {
            background-color: rgb(61, 61, 61);
            border-radius: 12px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background-color: rgb(46, 46, 46);
            border-radius: 12px;
        }
        ::-webkit-scrollbar-corner {
            background: none;
        }
    </style>
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
