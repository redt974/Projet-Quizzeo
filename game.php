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

        // Calculer les points par question
        foreach($quiz_questions as $question) {
            $points[$question[0]] = $question[3];
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
            shuffle($answers);
            foreach ($answers as $answer) {
                echo "<input type='radio' name='answer[{$question[0]}]' value='{$answer[0]}'>{$answer[2]}<br>";
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
        $quiz_answers_file = fopen('user_quiz_answer.csv', 'r');
        $total_score = 0;
        foreach ($_POST['answer'] as $question_id => $selected_answer_id) {
            while(($answer = fgetcsv($quiz_answers_file)) !== false) {
                if($answer[1] == $question_id && $answer[0] == $selected_answer_id) {
                    $total_score += $answer[3] * $points[$question_id];
                }
            }
            // Réinitialiser le pointeur du fichier après chaque boucle pour revenir au début
            fseek($quiz_answers_file, 0);
        }
        fclose($quiz_answers_file);

        // Sauvegarder le résultat dans le fichier csv 'user_result_game.csv'
        $result_file_handle = fopen($result_file, 'a');
        $result_line = [$last_id, $user_id, $quiz_id, $total_score, $date,'en cours'];
        fputcsv($result_file_handle, $result_line);
        fclose($result_file_handle);

        // Redirection sur la page index.php
        header('location: index.php');
    }

?>

</body>
</html>
