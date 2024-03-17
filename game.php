<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Game</title>
    <link rel="icon" href='./assets/quizzeo.ico' />
</head>
<body>

<?php
    // Vérifier si les identifiants de l'utilisateur et du quiz sont passés en POST
    if(isset($_POST['user_id']) && isset($_POST['quiz_id'])) {
        $user_id = $_POST['user_id'];
        $quiz_id = $_POST['quiz_id'];

        // Ouvrir les fichiers CSV nécessaires
        $quiz_questions_file = fopen('user_quiz_question.csv', 'r');
        $quiz_answers_file = fopen('user_quiz_answer.csv', 'r');
        $quiz_info_file = fopen('user_quiz.csv', 'r');
        $result_file = 'user_result_game.csv';

        // Filtrer les questions par rapport à l'ID du quiz
        $quiz_questions = [];
        while(($row = fgetcsv($quiz_questions_file)) !== false) {
            if($row[1] == $quiz_id) {
                $quiz_questions[] = $row;
            }
        }

        // Tableau pour stocker les points de chaque question
        $points = [];

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
            // Mélanger les réponses pour chaque question
            $answers = [];
            $quiz_answers_file = fopen('user_quiz_answer.csv', 'r');
            while(($answer = fgetcsv($quiz_answers_file)) !== false) {
                if($answer[1] == $question[0]) {
                    $answers[] = $answer;
                }
            }
            fclose($quiz_answers_file);
            // shuffle($answers);
            foreach ($answers as $answer) {
                echo "<input type='checkbox' name='answer[{$question[0]}]' value='{$answer[0]}'>{$answer[2]}<br>";
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
        $date = date('Y-m-d H:i:s');      

        // Trouver l'id du prochain résultat
        $result_file_handle = fopen($result_file, 'r');
        $result_lines = [];
        if($result_file_handle) {
            fgetcsv($result_file_handle);
            while(($line = fgetcsv($result_file_handle)) !== false) {
                $result_lines[] = $line;
            }
            $last_id = count($result_lines) + 1;
        } else {
            $last_id = 1;
        }
        fclose($result_file_handle);

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
                    } else {
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

        // Sauvegarder le résultat dans le fichier csv 'user_result_game.csv'
        $result_file_handle = fopen($result_file, 'a');
        $result_line = [$last_id, $user_id, $quiz_id, $score_obtenu, $note_max, $date,'en cours'];
        fputcsv($result_file_handle, $result_line);
        fclose($result_file_handle);

        // Redirection sur la page index.php
        header('location: index.php');
    }

?>

</body>
</html>
