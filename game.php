<?php
    session_start();
    // Tableau pour stocker les réponses sélectionnées par l'utilisateur pour chaque question
    $user_responses = [];

    // Vérification si l'ID du quiz est fourni dans l'URL
    if (!isset($_POST['quiz_id'])) {
        echo "Erreur: ID du quiz non fourni.";
        exit;
    }
    $quiz_id = $_POST['quiz_id'];

    // Charger les informations du quiz
    $quiz_info = loadQuizInfo($quiz_id);

    // Vérifier si le quiz existe
    if (!$quiz_info) {
        echo "Aucun quiz trouvé pour cet ID.";
        exit;
    }

    // Charger les questions du quiz
    $questions = loadQuestions($quiz_id);

    // Vérifier si le quiz contient des questions
    if (empty($questions)) {
        echo "Aucune question trouvée pour ce quiz.";
        exit;
    }

    // Initialisez l'indice de la question actuelle
    $current_question_index = 0;

    // Vérifier si une action est soumise (précédent ou suivant)
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['action']) && ($_POST['action'] == 'previous' || $_POST['action'] == 'next')) {
            // Récupérer l'indice de la question actuelle
            $current_question_index = isset($_POST['current_question_index']) ? $_POST['current_question_index'] : 0;
            $current_question_index = intval($current_question_index);

            // Stocker la réponse sélectionnée par l'utilisateur pour la question actuelle
            $selected_answer_id = isset($_POST['selected_answer_id']) ? $_POST['selected_answer_id'] : null;
            $user_responses[$current_question_index] = $selected_answer_id;

            // Récupérer la prochaine question
            if ($_POST['action'] == 'next') {
                $current_question_index++;
            } elseif ($_POST['action'] == 'previous') {
                $current_question_index--;
            }

            // Vérifier les limites de la liste des questions
            if ($current_question_index < 0) {
                $current_question_index = 0;
            } elseif ($current_question_index >= count($questions)) { 
                $current_question_index = count($questions) - 1;
            }
        }

        // Vérifier si c'est la dernière question du quiz
        if ($current_question_index == count($questions) - 1) {
            // Afficher le bouton de soumission des résultats
            echo '<form action="game.php" method="post">';
            echo '<input type="hidden" name="quiz_id" value="' . $quiz_id . '">';
            echo '<input type="hidden" name="action" value="submit_results">';
            echo '<button type="submit">Soumettre les résultats</button>';
            echo '</form>';
        }
    }

    // Charger la question actuelle
    $current_question = $questions[$current_question_index];
    $current_question_id = $current_question[0];
    $current_question_text = $current_question[2];
    $answers = loadAnswers($current_question_id);

    // Si l'action est soumettre les résultats
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'submit_results') {
        // Tableau pour stocker les résultats du quiz
        $results = [];
        // Vérifier chaque réponse sélectionnée par l'utilisateur
        foreach ($user_responses as $question_index => $selected_answer_id) {
            // Charger la réponse sélectionnée depuis le fichier CSV des réponses
            $selected_answer = loadAnswerById($selected_answer_id);
            // Vérifier si la réponse a été trouvée
            if ($selected_answer) {
                $is_correct = $selected_answer[3]; // Indice 3 contient la valeur de l'attribut correct
                $results[$current_question_id] = $is_correct;
            } else {
                echo "Réponse sélectionnée introuvable pour la question " . ($question_index + 1) . ".";
            }
        }

        // Ajouter les résultats au fichier CSV
        addResultsToCSV($_SESSION['id'], $quiz_id, $results);
        }

    // Fonction pour charger les informations du quiz depuis le fichier CSV
    function loadQuizInfo($quiz_id) {
        $quiz_info = [];
        $quiz_file = 'user_quiz.csv';
        $file = fopen($quiz_file, 'r');
        if ($file) {
            while (($row = fgetcsv($file)) !== false) {
                if ($row[0] == $quiz_id) {
                    $quiz_info = $row; // Nous récupérons toutes les données du quiz
                }
            }
            fclose($file);
        }
        return $quiz_info;
    }
    // Fonction pour charger les questions depuis le fichier CSV
    function loadQuestions($quiz_id) {
        $questions = [];
        $question_file = 'user_quiz_question.csv';
        $file = fopen($question_file, 'r');
        if ($file) {
            while (($row = fgetcsv($file)) !== false) {
                if ($row[1] == $quiz_id) {
                    $questions[] = $row; // Nous récupérons toutes les données de la question
                }
            }
            fclose($file);
        }
        return $questions;
    }
    // Fonction pour charger les réponses depuis le fichier CSV
    function loadAnswers($question_id) {
        $answers = [];
        $answer_file = 'user_quiz_answer.csv';
        $file = fopen($answer_file, 'r');
        if ($file) {
            while (($row = fgetcsv($file)) !== false) {
                if ($row[1] == $question_id) {
                    $answers[] = $row; // Nous récupérons toutes les données de la réponse
                }
            }
            fclose($file);
        }
        return $answers;
    }
    // Fonction pour charger une réponse par son ID depuis le fichier CSV
    function loadAnswerById($answer_id) {
        $answer_file = 'user_quiz_answer.csv';
        $file = fopen($answer_file, 'r');
        if ($file) {
            while (($row = fgetcsv($file)) !== false) {
                if ($row[0] == $answer_id) {
                    fclose($file);
                    return $row; // Retourner la réponse trouvée
                }
            }
            fclose($file);
        }
        return false; // Retourner false si la réponse n'est pas trouvée
    }

    // Fonction pour obtenir le prochain ID pour les résultats
    function getNextResultId($result_file) {
        // Initialiser l'ID à 1 s'il n'y a pas de résultats encore
        $next_id = 1;
        // Vérifier si le fichier existe et s'il contient des données
        if (file_exists($result_file)) {
            $file = fopen($result_file, 'r');
            // Lire chaque ligne du fichier pour trouver le dernier ID utilisé
            while (($row = fgetcsv($file)) !== false) {
                $next_id++;
            }
            fclose($file);
        }
        return $next_id;
    }

    // Fonction pour ajouter les résultats dans le fichier CSV
    function addResultsToCSV($user_id, $quiz_id, $results) {
        $result_file = 'user_result_game.csv';
        // Récupérer le prochain ID pour les résultats
        $next_id = getNextResultId($result_file);
        // Obtenir la date actuelle
        $date = date('Y-m-d H:i:s');
        // Ouvrir le fichier en mode ajout
        $file = fopen($result_file, 'a');
        // Écrire les résultats dans le fichier CSV
        foreach ($results as $question_id => $result) {
            fputcsv($file, [$next_id, $user_id, $quiz_id, $question_id, $result, $date]);
            $next_id++; // Incrémenter l'ID pour les résultats suivants
        }
        fclose($file);
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $quiz_info[2]; ?></title>
    <link rel="stylesheet" href="./style/game.css">
</head>
<body>
    <div class="quiz-container">
        <h2><?php echo $quiz_info[2]; ?></h2>
        <p><?php echo $quiz_info[3]; ?></p>
        <div class="question"><?php echo $current_question_text; ?></div>
        <form action="game.php" method="post">
            <?php foreach ($answers as $answer): ?>
                <label class="answer">
                    <input type="radio" name="selected_answer_id" value="<?php echo $answer[0]; ?>"> <?php echo $answer[2]; ?>
                </label>
            <?php endforeach; ?>
            <input type="hidden" name="current_question_index" value="<?php echo $current_question_index; ?>">
            <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
            <button type="submit" name="action" value="previous">Précédent</button>
            <button type="submit" name="action" value="next">Suivant</button>
        </form>
    </div>
</body>
</html>
