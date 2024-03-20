<?php
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Collecte des données du formulaire
        $quizTitle = $_POST['quizTitle'];
        $quizDescription = $_POST['quizDescription'];
        $quizHeure = $_POST['quizHeure'];
        $quizMinute = $_POST['quizMinute'];
        $quizSeconde = $_POST['quizSeconde'];
        

            // Check if image file is selected
            if(isset($_FILES["image"]["name"])) {
                $target_dir = "uploads/"; // Directory where you want to store the uploaded images
                $image_name = uniqid() . '_' . basename($_FILES["image"]["name"]); // Generate unique filename
                $target_file = $target_dir . $image_name;
        
                 // Vérifie si le fichier est une image réelle ou une fausse image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
        // Déplace le fichier téléchargé vers le répertoire cible
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "Le fichier ". htmlspecialchars(basename( $_FILES["image"]["name"])). " a été téléchargé avec succès.";
        } else {
            echo "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
        }
    } else {
        echo "Le fichier n'est pas une image.";
    }
   

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["image"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow only certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            // If everything is ok, try to upload file
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                echo "The file ". basename( $_FILES["image"]["name"]). " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
        $image = $target_file;
    } else {
        $image = '';
    }

            

        $id_user = $_SESSION['id'];

        // Insertion dans le fichier user_quiz.csv
        $userQuizFile = 'user_quiz.csv';
        $userQuizData = [getNextQuizId($userQuizFile), $id_user, $quizTitle, $quizDescription,$quizHeure . ":" . $quizMinute . ":" . $quizSeconde,$image, "en cours", "active"];
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

        // Insertion des questions à réponse libre
        $freeQuestions = $_POST['free-questions'];
        if (isset($freeQuestions)) {
            $userQuizQuestionFile = 'user_quiz_question.csv';
            foreach ($freeQuestions as $freeQuestion) {
                $questionData = [getNextQuizId($userQuizQuestionFile), $lastQuizId, $freeQuestion, -1, 'libre'];
                insertIntoCSV($userQuizQuestionFile, $questionData);
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
    <script defer src="https://kit.fontawesome.com/b32d44622b.js" crossorigin="anonymous"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href='./assets/quizzeo.ico' />
    <link rel="stylesheet" href="./style/quiz.css">
</head>
<body>
    <?php 
        if ($_SESSION['role'] == 'school' || $_SESSION['role'] == 'company') {
            include './components/header.php';
        } else {
            header('location: index.php');
        }
    ?>
<form action="quiz.php" method="post" enctype="multipart/form-data">
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

    <label for="quizTime">Timer</label>
    

    <label for="quizHeure">Heure :</label>
    <input type="number" id="quizHeure" name="quizHeure" required>

    <label for="quizMinute">Minute :</label>
    <input type="number" id="quizMinute" name="quizMinute" required>

    <label for="quizSeconde">Seconde :</label>
    <input type="number" id="quizSeconde" name="quizSeconde" required>

    <div id="questionsContainer"></div>

    <button type="button" onclick="addQuestion()">Ajouter une question</button>
    <?php 
        if ($_SESSION['role'] == 'company') {
            echo '<button type="button" onclick="addQuestionWithFreeResponse()">Ajouter une question avec une réponse libre</button>';
        } 
    ?>
    <div class="container-img">
        <!-- Hidden file input -->
        <input type="file" name="image" id="file" style="display: none;">

        <div class="img-area">
            <i class='bx bx-upload icon'></i>
            <h2>Importer des images</h2>
            <p>L'image doit peser moins de <span>2Mb</span></p>
        </div>
        <!-- Button to trigger file input -->
        <button type="button" class="select-image" onclick="document.getElementById('image').click();">Sélectionner Image</button>
    </div>

    <button type="submit">Enregistrer le Quiz</button>
</form>

    <script defer src="./script/script.js"></script>

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
                                    '<input type="number" name="points" required>' +
                                    '<br>' +
                                    '<div class="answersContainer"></div>' +
                                    '<button type="button" onclick="addAnswer(this)">Ajouter une Réponse</button>' +
                                    '<button type="button" onclick="removeQuestion(this)">Supprimer la Question</button>' +
                                    '<br>';

            container.appendChild(questionForm);
        }

        // Fonction pour ajouter une nouvelle réponse à une question
        function addAnswer(button) {
            var answersContainer = button.previousSibling; // Sélectionner le conteneur de réponses de la question
            var answerForms = answersContainer.getElementsByClassName('answerForm'); // Sélectionner les champs de réponse spécifiques à la question

            // Vérifier si le nombre de réponses est inférieur à 4 avant d'ajouter une nouvelle réponse
            if (answerForms.length < 4) {
                var questionIndex = answersContainer.parentElement.querySelector('input').value; // Index de la question
                var answerForm = document.createElement('div');
                answerForm.classList.add('form');
                answerForm.classList.add('answerForm'); // Ajouter la classe spécifique
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

                // Mettre à jour l'index de la question
                updateQuestionIndex(answersContainer.parentElement, questionIndex);

                // Masquer le bouton d'ajout si la limite est atteinte
                if (answerForms.length === 4) {
                    button.style.display = 'none'; // Masquer le bouton
                }
            }
        }


        // Fonction pour mettre à jour l'index de la question
        function updateQuestionIndex(questionContainer, newIndex) {
            var questionInput = questionContainer.querySelector('input[name="points"]');
            questionInput.value = newIndex;
        }

        // Fonction pour ajouter une nouvelle question avec une réponse libre
        function addQuestionWithFreeResponse() {
            var container = document.getElementById('questionsContainer');
            var questionIndex = container.children.length + 1; // Index de la question

            var questionForm = document.createElement('div');
            questionForm.classList.add('form');
            questionForm.innerHTML = '<label for="free-question">Nom de la Question :</label>' +
                '<input type="text" name="free-questions[]" required>'+
                '<br>'+
                '<button type="button" onclick="removeQuestion(this)">Supprimer la Question</button>'+
                '<br>';
            container.appendChild(questionForm);
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
