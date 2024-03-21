<?php 
    if (isset($_POST['submit'])) {
        // Redirection sur la page index.php si Soumettre est cliqué
        header('location: index.php');
    } 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Game</title>
    <link rel="icon" href='./assets/quizzeo.ico' />
    
    <script defer src="https://kit.fontawesome.com/b32d44622b.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href='./style/game.css' />
</head>

<body>
<video id="background-video" autoplay loop muted>
        <source src="./assets/0319.mp4">
    </video>
    
    <?php

    session_start();

    include './components/header.php';

    ?>
    <div class="card">
    <?php

    // Vérifier si les identifiants de l'utilisateur et du quiz sont passés en POST
    if (isset ($_POST['user_id']) && isset ($_POST['quiz_id'])) {
        $user_id = $_POST['user_id'];
        $quiz_id = $_POST['quiz_id'];

        // Récupération des heures, minutes et secondes depuis le fichier CSV
        $file = fopen('user_quiz.csv', 'r');

        if ($file !== false) {
            fgetcsv($file);
            while (($row = fgetcsv($file)) !== false) {
                if ($quiz_id == $row[0]) {
                    $time = $row[4];
                    break; // Sortir de la boucle une fois que nous avons trouvé le temps
                }
            }
            fclose($file);
        }

        // Formatage du temps comme un tableau JavaScript
        $timeArray = explode(':', $time);
        $timeArray = array_map('intval', $timeArray);

        // Formatage de $time comme un tableau JavaScript
        $time = '[' . implode(',', $timeArray) . ']';

        echo "<script>
                var timeArray = $time; // Utilisation directe du tableau $time
                var hours = parseInt(timeArray[0]); // Récupération des heures
                var minutes = parseInt(timeArray[1]); // Récupération des minutes
                var seconds = parseInt(timeArray[2]); // Récupération des secondes

                var total_seconds = hours * 3600 + minutes * 60 + seconds; // Convertir en secondes
                var timer = setInterval(function() {
                    // Calcul des heures, minutes et secondes restantes
                    var hours = Math.floor(total_seconds / 3600);
                    var minutes = Math.floor((total_seconds % 3600) / 60);
                    var seconds = total_seconds % 60;

                    // Formatage des heures, minutes et secondes pour qu'ils aient toujours deux chiffres
                    hours = ('0' + hours).slice(-2);
                    minutes = ('0' + minutes).slice(-2);
                    seconds = ('0' + seconds).slice(-2);

                    // Affichage du temps restant
                    document.getElementById('timer').innerHTML = 'Timer: ' + hours + ' H ' + minutes + ' M ' + seconds + ' S';

                    // Décrémentation du temps restant
                    total_seconds--;

                    // Vérification si le temps est écoulé
                    if (total_seconds < 0) {
                        clearInterval(timer);
                        document.getElementById('timer').innerHTML = 'Temps écoulé';
                        document.getElementById('gameForm').submit(); // Envoyer le formulaire lorsque le temps est écoulé
                    }
                }, 1000);
            </script>";

        // Ouvrir les fichiers CSV nécessaires
        $quiz_questions_file = fopen('user_quiz_question.csv', 'r');
        $quiz_answers_file = fopen('user_quiz_answer.csv', 'r');
        $quiz_info_file = fopen('user_quiz.csv', 'r');
        $result_file = 'user_result_game.csv';
        $free_answer_file = 'user_quiz_free_answer.csv';

        // Filtrer les questions par rapport à l'ID du quiz
        $quiz_questions = [];
        while (($row = fgetcsv($quiz_questions_file)) !== false) {
            if ($row[1] == $quiz_id) {
                $quiz_questions[] = $row;
            }
        }

        // Tableau pour stocker les points de chaque question
        $points = [];

        // Bouton de sortie du jeu
        echo '            
        <a href="./index.php">
            <p class="exit">Exit<i class="fa-solid fa-arrow-right arrow" style="color: #ffffff;"></i></p>
        </a>';



        // Afficher le titre et la description du quiz
        while (($row = fgetcsv($quiz_info_file)) !== false) {
            if ($row[0] == $quiz_id) {
                echo "<h1>{$row[2]}</h1>";
                echo "<p>{$row[3]}</p>";
                echo "<div id='timer'></div>";

            }
        }

        // Récupération des points par question
        foreach ($quiz_questions as $question) {
            $points[$question[0]] = $question[3];
        }

        // Calculer le barème du quiz
        $note_max = 0;
        foreach ($quiz_questions as $question) {
            $note_max += $question[3];
        }

        // Mélanger les questions
        shuffle($quiz_questions);

        // Afficher les questions et réponses du quiz
        echo "<form action='game.php' method='post' id='gameForm'>";
        foreach ($quiz_questions as $question) {
            echo "<h2>{$question[2]}</h2>";
            // Vérifier si la question est à réponse libre
            if ($question[4] == 'libre') {
                echo "<input type='text' name='free_answer[{$question[0]}]' required><br>";
            } else {
                $answers = [];
                $quiz_answers_file = fopen('user_quiz_answer.csv', 'r');
                while (($answer = fgetcsv($quiz_answers_file)) !== false) {
                    if ($answer[1] == $question[0]) {
                        $answers[] = $answer;
                    }
                }
                fclose($quiz_answers_file);
                // Mélanger les réponses pour chaque question
                shuffle($answers);
                foreach ($answers as $answer) {
                    echo "<div class='form-check'>";
                    echo "<input class='btn-check' type='checkbox' id='btn-check-outlined{$answer[0]}' name='answer[{$question[0]}][]' value='{$answer[0]}' autocomplete='off' style='border: none;'>";
                    echo "<label class='btn btn-outline-primary' for='btn-check-outlined{$answer[0]}' style='width: 100%; border: none;'>{$answer[2]}</label> ";
                    echo "</div>";
                    
                    
                }
            }
        }
        echo "<input type='hidden' name='user_id' value='$user_id'>";
        echo "<input type='hidden' name='quiz_id' value='$quiz_id'>";
        echo "<br><input class='submit-btn' type='submit' name='submit' value='Soumettre'>";

        echo "</form>";

        // Fermer les fichiers CSV
        fclose($quiz_questions_file);
        fclose($quiz_info_file);
    } else {
        // Redirection vers la page index.php
        header('location: index.php');
    }

    // Traitement de la soumission du quiz
    if (isset ($_POST['submit'])) {
        $user_id = $_POST['user_id'];
        $quiz_id = $_POST['quiz_id'];
        $date = date('H\h i\m s\s');

        // Trouver l'id du prochain résultat ou réponse libre
        function getNextId($file_name)
        {
            $file_name_handle = fopen($file_name, 'r');
            $result_lines = [];
            if ($file_name_handle) {
                fgetcsv($file_name_handle);
                while (($line = fgetcsv($file_name_handle)) !== false) {
                    $result_lines[] = $line;
                }
                $last_id = count($result_lines) + 1;
            } else {
                $last_id = 1;
            }
            fclose($file_name_handle);
            return $last_id;
        }

        // Calcul du score des réponses sélectionnées 
        $score_obtenu = 0;
        foreach ($_POST['answer'] as $question_id => $selected_answer_ids) {
            // Variables pour le comptage des réponses correctes et incorrectes sélectionnées par l'utilisateur
            $selected_answers = 0;

            // Variables pour stocker le nombre total de réponses correctes et incorrectes pour la question actuelle
            $total_correct_answers = 0;

            // Récupération des réponses correctes et incorrectes pour la question actuelle
            $answers = [];
            $quiz_answers_file = fopen('user_quiz_answer.csv', 'r');
            while (($answer = fgetcsv($quiz_answers_file)) !== false) {
                if ($answer[1] == $question_id) {
                    $answers[] = $answer;
                    if ($answer[3] == 1) {
                        $total_correct_answers += 1;
                    }
                }
            }
            fclose($quiz_answers_file);

            // Vérifier si l'utilisateur a sélectionné au moins une réponse pour cette question
            if (!is_array($selected_answer_ids)) {
                $selected_answer_ids = array($selected_answer_ids);
            }

            // Vérification des réponses sélectionnées par l'utilisateur
            foreach ($answers as $answer) {
                if (in_array($answer[0], $selected_answer_ids)) {
                    if ($answer[3] == 1) {
                        $selected_answers += 1;
                    } else if ($answer[3] == 0) {
                        $selected_answers -= 1;
                    }
                }
            }

            // Calcul du score pour la question actuelle
            if ($selected_answers == $total_correct_answers) {
                // Toutes les bonnes réponses ont été sélectionnées et aucune réponse incorrecte n'a été sélectionnée
                $score_obtenu += $points[$question_id];
            } else if ($selected_answers > 0) {
                // Calcul de la moyenne du nombre de réponses correctes sélectionnées par le joueur
                $score_obtenu += $selected_answers / $total_correct_answers * $points[$question_id];
            }
        }

        // Sauvegarder le résultat libre dans le fichier csv 'user_quiz_free_answer.csv'
        if (isset ($_POST['free_answer'])) {
            foreach ($_POST['free_answer'] as $question_id => $free_answer) {
                $free_answer_file_handle = fopen($free_answer_file, 'a');
                $free_answer_line = [getNextId($free_answer_file), $user_id, $quiz_id, $question_id, $free_answer, $date, 'terminé'];
                fputcsv($free_answer_file_handle, $free_answer_line);
                fclose($free_answer_file_handle);
            }
        }

        // Sauvegarder le résultat dans le fichier csv 'user_result_game.csv'
        $result_file_handle = fopen($result_file, 'a');
        $result_line = [getNextId($result_file), $user_id, $quiz_id, $score_obtenu, $note_max, $date, 'terminé'];
        fputcsv($result_file_handle, $result_line);
        fclose($result_file_handle);
    }


    ?>
</div>

</body>

</html>