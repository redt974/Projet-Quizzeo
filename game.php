<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Game</title>
    <link rel="icon" href='./assets/quizzeo.ico' />
    <link rel="stylesheet" href='./style/game.css' />
    <script defer src="https://kit.fontawesome.com/b32d44622b.js" crossorigin="anonymous"></script>
</head>
<body>

<?php

    session_start();

    include './components/header.php';

    // Vérifier si les identifiants de l'utilisateur et du quiz sont passés en POST
    if(isset($_POST['user_id']) && isset($_POST['quiz_id'])) {
        $user_id = $_POST['user_id'];
        $quiz_id = $_POST['quiz_id'];

        // Ouvrir les fichiers CSV nécessaires
        $quiz_questions_file = fopen('user_quiz_question.csv', 'r');
        $quiz_answers_file = fopen('user_quiz_answer.csv', 'r');
        $quiz_info_file = fopen('user_quiz.csv', 'r');
        $result_file = 'user_result_game.csv';
        $free_answer_file = 'user_quiz_free_answer.csv';

        // Filtrer les questions par rapport à l'ID du quiz
        $quiz_questions = [];
        while(($row = fgetcsv($quiz_questions_file)) !== false) {
            if($row[1] == $quiz_id) {
                $quiz_questions[] = $row;
            }
        }

        // Tableau pour stocker les points de chaque question
        $points = [];

        // Bouton de sortie du jeu
        echo "<form method='post'><input type='submit' name='save_and_exit' value='Enregistrer et Quitter'></form>";


        // Afficher le titre et la description du quiz
        while(($row = fgetcsv($quiz_info_file)) !== false) {
            if($row[0] == $quiz_id) {
                echo "<h1>{$row[2]}</h1>";
                echo "<p>{$row[3]}</p>";
            }
        }

        // Récupération des points par question
        foreach($quiz_questions as $question) {
            $points[$question[0]] = $question[3];
        }

        // Calculer le barème du quiz
        $note_max = 0;
        foreach($quiz_questions as $question) {
            $note_max += $question[3];
        }
        
        // Mélanger les questions
        shuffle($quiz_questions);

        // Afficher les questions et réponses du quiz
        echo "<form action='game.php' method='post'>";
        foreach($quiz_questions as $question) {
            echo "<h2>{$question[2]}</h2>";
            // Vérifier si la question est à réponse libre
            if ($question[4] == 'libre') {
                echo "<input type='text' name='free_answer[{$question[0]}]' required><br>";
            } else {
                $answers = [];
                $quiz_answers_file = fopen('user_quiz_answer.csv', 'r');
                while(($answer = fgetcsv($quiz_answers_file)) !== false) {
                    if($answer[1] == $question[0]) {
                        $answers[] = $answer;
                    }
                }
                fclose($quiz_answers_file);
                // Mélanger les réponses pour chaque question
                shuffle($answers);
                foreach ($answers as $answer) {
                    echo "<input type='checkbox' name='answer[{$question[0]}][]' value='{$answer[0]}'>{$answer[2]}<br>";
                }
            }
        }
        echo "<input type='hidden' name='user_id' value='$user_id'>";
        echo "<input type='hidden' name='quiz_id' value='$quiz_id'>";
        echo "<input type='submit' name='submit' value='Soumettre'>";
        echo "</form>";

        // Fermer les fichiers CSV
        fclose($quiz_questions_file);
        fclose($quiz_info_file);
    } else {
        // Redirection vers la page index.php
        header('location: index.php');
    }

    // Traitement de la soumission du quiz
    if(isset($_POST['submit'])) {
        $user_id = $_POST['user_id'];
        $quiz_id = $_POST['quiz_id'];
        $date = date('H:i:s');      

        // Trouver l'id du prochain résultat ou réponse libre
        function getNextId($file_name){
            $file_name_handle = fopen($file_name, 'r');
            $result_lines = [];
            if($file_name_handle) {
                fgetcsv($file_name_handle);
                while(($line = fgetcsv($file_name_handle)) !== false) {
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
            while(($answer = fgetcsv($quiz_answers_file)) !== false) {
                if($answer[1] == $question_id) {
                    $answers[] = $answer;
                    if($answer[3] == 1) {
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
                    } else if ($answer[3] == 0){
                        $selected_answers -= 1;
                    }
                }
            }
        
            // Calcul du score pour la question actuelle
            if($selected_answers == $total_correct_answers) {
                // Toutes les bonnes réponses ont été sélectionnées et aucune réponse incorrecte n'a été sélectionnée
                $score_obtenu += $points[$question_id];
            } else if($selected_answers > 0) {
                // Calcul de la moyenne du nombre de réponses correctes sélectionnées par le joueur
                $score_obtenu += $selected_answers / $total_correct_answers * $points[$question_id];
            }
        }      
        
        // Sauvegarder le résultat libre dans le fichier csv 'user_quiz_free_answer.csv'
        if(isset($_POST['free_answer'])) {
            foreach ($_POST['free_answer'] as $question_id => $free_answer) {
                $free_answer_file_handle = fopen($free_answer_file, 'a');
                $free_answer_line = [getNextId($free_answer_file), $user_id, $quiz_id, $question_id, $free_answer, $date,'terminé'];
                fputcsv($free_answer_file_handle, $free_answer_line);
                fclose($free_answer_file_handle);
            }
        }

        // Sauvegarder le résultat dans le fichier csv 'user_result_game.csv'
        $result_file_handle = fopen($result_file, 'a');
        $result_line = [getNextId($result_file), $user_id, $quiz_id, $score_obtenu, $note_max, $date,'terminé'];
        fputcsv($result_file_handle, $result_line);
        fclose($result_file_handle);

        if(isset($_POST['submit'])) {
            // Redirection sur la page index.php si Soumettre est cliqué
            // header('location: index.php');
        } elseif(isset($_POST['save_and_exit'])) {
            // Redirection vers une page de confirmation ou d'accueil si Enregistrer et Quitter est cliqué
            // header('location: quiz_save.php');
        }
    }
    
    ?>


</body>
</html>
