<?php
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Collecte des données du formulaire
        $quizTitle = $_POST['quizTitle'];
        $quizDescription = $_POST['quizDescription'];
        $name_user = $_SESSION['prenom'];

        // Insertion dans le fichier user_quiz.csv
        $userQuizFile = 'user_quiz.csv';
        $userQuizData = [getNextQuizId($userQuizFile), $name_user, $quizTitle, $quizDescription];
        insertIntoCSV($userQuizFile, $userQuizData);

        // Obtention de l'ID du dernier quiz inséré
        $lastQuizId = getNextQuizId($userQuizFile) - 1;
        $questions = $_POST['questions'];
        // Insertion des questions dans le fichier user_quiz_question.csv
        if (isset($questions)) {
            $userQuizQuestionFile = 'user_quiz_question.csv';
            foreach ($questions as $question) {
                $points = $_POST['points'];
                $questionData = [getNextQuizId($userQuizQuestionFile), $lastQuizId, $question, $points];
                insertIntoCSV($userQuizQuestionFile, $questionData);

                // Obtention de l'ID de la dernière question insérée
                $lastQuestionId = getNextQuizId($userQuizQuestionFile) - 1;

                // Insertion des réponses dans le fichier user_quiz_answer.csv
                $answers = $_POST['answers'];
                if (isset($answers[$question])) {
                    $userQuizAnswerFile = 'user_quiz_answer.csv';
                    foreach ($answers[$question] as $indexAnswer => $answer) {
                        $correct = $_POST['correct'];
                        $answerData = [getNextQuizId($userQuizAnswerFile), $lastQuestionId, $answer, $correct[$question][$indexAnswer]];
                        insertIntoCSV($userQuizAnswerFile, $answerData);
                    }
                }
            }
        }

        echo "Quiz enregistré avec succès!";
        header('location: index.php');
    }

    // Fonction pour insérer des données dans un fichier CSV
    function insertIntoCSV($filename, $data) {
        $file = fopen($filename, 'a+');
        fputcsv($file, $data);
        fclose($file);
    }

    // Fonction pour obtenir l'ID du prochain enregistrement dans un fichier CSV
    function getNextQuizId($filename) {
        $file = fopen($filename, 'r');
        $nextId = 0;

        if ($file !== false) {
            while (($row = fgetcsv($file)) !== false) {
                $nextId = max($nextId, (int)$row[0]);
            }

            fclose($file);
        }

        return $nextId + 1;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de Quiz</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .quiz-titre{
            display: flex;
            justify-content: space-around;
            align-items: center;
            width:100%;
        }
        h1 {
            color: #333;
            text-align:center;
        }
        form, .form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);
        }
        form{
            margin: 50px auto;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        #quizDescription{
          font-family: Arial, sans-serif;
          height: 150px;
          width: 100%;
          padding: 10px;
          border: 1px solid #ccc;
          border-radius: 4px;
          box-sizing: border-box;
          overflow-y: auto;
          word-wrap: break-word;
          resize: none;
        }
        .exit{
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
        }
        button, .exit {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        a{
            text-decoration: none;
        }
        button:hover {
            background-color: #45a049;
        }

        .container-answer {
            margin-bottom: 10px;
        }

        .container-answer form {
            margin-bottom: 5px;
        }
    </style>
    <script defer src="https://kit.fontawesome.com/b32d44622b.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php 
        if(!isset($_SESSION['email'])){
            header('location: connexion.php');
        } else {

            include './components/header.php';
        }
    ?>
    <form action="quiz.php" method="post">
        <div class='quiz-titre'>
            <h1>Création de Quiz</h1>    
            <a href='./index.php'>
                <p class="exit">Exit<i class="fa-solid fa-arrow-right" style="color: #ffffff;"></i></p>
            </a>
        </div>
        <label for="quizTitle">Titre du Quiz :</label>
        <input type="text" id="quizTitle" name="quizTitle" required>

        <label for="quizDescription">Description du Quiz :</label>
        <textarea id="quizDescription" name="quizDescription" rows="4" required></textarea>

        <div id="questionsContainer"></div>

        <button type="button" onclick="addQuestion()">Ajouter une question</button>

        <button type="submit">Enregistrer le Quiz</button>
    </form>

    <script>
        // Fonction pour ajouter une nouvelle question
        function addQuestion() {
            var container = document.getElementById('questionsContainer');
            var questionIndex = container.children.length + 1; // Index de la question

            var questionForm = document.createElement('div');
            questionForm.classList.add('form');
            questionForm.innerHTML = '<label for="questions">Nom de la Question :</label>' +
                                    '<input type="text" name="questions[]" required>' +
                                    '<label for="points">Points :</label>' +
                                    '<input type="text" name="points" required>' +
                                    '<br>' +
                                    '<div class="answersContainer"></div>' +
                                    '<button type="button" onclick="addAnswer(this)">Ajouter une Réponse</button>' +
                                    '<button type="button" onclick="removeQuestion(this)">Supprimer la Question</button>' +
                                    '<br>';

            container.appendChild(questionForm);
        }

        // Fonction pour ajouter une nouvelle réponse à une question
        function addAnswer(button) {
            var answersContainer = button.previousSibling;
            var answerForms = answersContainer.getElementsByTagName('form');

            // Vérifier si le nombre de réponses est inférieur à 4 avant d'ajouter une nouvelle réponse
            if (answerForms.length < 4) {
                var questionIndex = answersContainer.parentElement.querySelector('input').value; // Index de la question
                var answerForm = document.createElement('div');
                answerForm.classList.add('form');
                answerForm.innerHTML = '<label for="answers">Réponse :</label>' +
                                        '<input type="text" name="answers[' + questionIndex + '][]" required>' +
                                        '<label for="correct">Correct :</label>' +
                                        '<select name="correct[' + questionIndex + '][]">' +
                                        '<option value="1">Oui</option>' +
                                        '<option value="0">Non</option>' +
                                        '</select>' +
                                        '<button type="button" onclick="removeAnswer(this)">Supprimer la Réponse</button>' +
                                        '<br>';

                answersContainer.appendChild(answerForm);

                // Masquer le bouton d'ajout si la limite est atteinte
                if (answerForms.length === 4) {
                    button.style.display = 'none'; // Masquer le bouton
                }
            }
        }

        // Fonction pour supprimer une question
        function removeQuestion(button) {
            var questionForm = button.parentElement;
            questionForm.parentElement.removeChild(questionForm);
        }

        // Fonction pour supprimer une réponse
        function removeAnswer(button) {
            var answerForm = button.parentElement;
            answerForm.parentElement.removeChild(answerForm);
        }


    </script>
</body>
</html>
