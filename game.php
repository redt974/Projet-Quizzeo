<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Game</title>
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
        $quiz_questions = array();
        while(($row = fgetcsv($quiz_questions_file)) !== false) {
            if($row[1] == $quiz_id) {
                $quiz_questions[] = $row;
            }
        }

        // Tableau pour stocker les points de chaque question
        $points_per_question = array();

        // Afficher le titre et la description du quiz
        while(($row = fgetcsv($quiz_info_file)) !== false) {
            if($row[0] == $quiz_id) {
                echo "<h1>{$row[2]}</h1>";
                echo "<p>{$row[3]}</p>";
            }
        }

        // Calculer les points par question
        foreach($quiz_questions as $question) {
            $points_per_question[$question[0]] = $question[3];
        }

        // Afficher les questions du quiz
        foreach($quiz_questions as $question) {
            echo "<h2>{$question[2]}</h2>";
            echo "<form method='post'>";
            // Ouvrir le fichier des réponses
            $quiz_answers_file = fopen('user_quiz_answer.csv', 'r');
            while(($answer = fgetcsv($quiz_answers_file)) !== false) {
                if($answer[1] == $question[0]) {
                    echo "<input type='radio' name='answer[{$question[0]}]' value='{$answer[2]}'>{$answer[2]}<br>";
                }
            }
            fclose($quiz_answers_file);
            echo "</form>";
        }

        // Afficher le bouton de soumission après la dernière question
        echo "<form action='game.php' method='post'>";
        echo "<input type='hidden' name='user_id' value='$user_id'>";
        echo "<input type='hidden' name='quiz_id' value='$quiz_id'>";
        echo "<input type='submit' name='submit' value='Soumettre'>";
        echo "</form>";
        
        // Fermer les fichiers CSV
        fclose($quiz_questions_file);
        fclose($quiz_info_file);
    } 

    // Traitement de la soumission du quiz
    if(isset($_POST['submit']) && isset($_POST['answer'])) {
        $user_id = $_POST['user_id'];
        $quiz_id = $_POST['quiz_id'];
        $date = date('Y-m-d H:i:s');

        $quiz_answers_file = fopen('user_quiz_answer.csv', 'r');
        $total_score = 0;

        $result_file_handle = fopen($result_file, 'a');
        $result_lines = array();
        if($result_file_handle) {
            while(($line = fgetcsv($result_file_handle)) !== false) {
                $result_lines[] = $line;
            }
            $last_id = count($result_lines) + 1;
        } else {
            $last_id = 1;
        }

        foreach ($_POST['answer'] as $question_id => $selected_answer) {
            while(($answer = fgetcsv($quiz_answers_file)) !== false) {
                if($answer[1] == $question_id && $answer[2] == $selected_answer) {
                    $total_score += $answer[3] * $points_per_question[$question_id];
                }
            }
        }

        fclose($quiz_answers_file);
        $result_line = array($last_id, $user_id, $quiz_id, $total_score, $date);
        fputcsv($result_file_handle, $result_line);
        fclose($result_file_handle);

        echo "Quiz soumis avec succès! Score total: $total_score";
    }
?>

</body>
</html>
