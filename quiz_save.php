<?php
// Inclure les fichiers requis ou initialiser les variables nécessaires pour récupérer les informations
$user_quiz_answer_file = 'user_quiz_answer.csv';
$user_quiz_free_answer_file = 'user_quiz_free_answer.csv';

// Code pour récupérer les informations à partir des fichiers CSV et les afficher si nécessaire
if (file_exists($user_quiz_answer_file)) {
    $answers = [];
    $answers_file = fopen($user_quiz_answer_file, 'r');
    echo "<h2>Réponses aux questions à choix multiples :</h2>";
    echo "<ul>";
    while (($answer = fgetcsv($answers_file)) !== false) {
        $answers[] = $answer;
        echo "<li>User ID: {$answer[1]}, Question ID: {$answer[2]}, Réponse: {$answer[3]}, Correct: {$answer[4]}</li>";
    }
    echo "</ul>";
    fclose($answers_file);
}

if (file_exists($user_quiz_free_answer_file)) {
    $free_answers = [];
    $free_answers_file = fopen($user_quiz_free_answer_file, 'r');
    echo "<h2>Réponses aux questions à réponse libre :</h2>";
    echo "<ul>";
    while (($free_answer = fgetcsv($free_answers_file)) !== false) {
        $free_answers[] = $free_answer;
        echo "<li>User ID: {$free_answer[1]}, Question ID: {$free_answer[3]}, Réponse: {$free_answer[4]}</li>";
    }
    echo "</ul>";
    fclose($free_answers_file);
}

// Affichage d'un message de confirmation ou d'accueil
echo "Vos réponses ont été enregistrées avec succès. Merci d'avoir participé au quiz!";
?>
