<?php
session_start();

$error_message = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Collecte des données du formulaire
        $quizTitle = $_POST['quizTitle'];
        $quizDescription = $_POST['quizDescription'];
        $quizHeure = $_POST['quizHeure'];
        $quizMinute = $_POST['quizMinute'];
        $quizSeconde = $_POST['quizSeconde'];
        

    // Check if image file is selected
    if (isset ($_FILES["image"]["name"])) {
        $target_dir = "uploads/"; // Directory where you want to store the uploaded images
        $image_name = uniqid() . '_' . basename($_FILES["image"]["name"]); // Generate unique filename
        $target_file = $target_dir . $image_name;

        // Vérifie si le fichier est une image réelle ou une fausse image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            // Déplace le fichier téléchargé vers le répertoire cible
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $error_message = "Le fichier " . htmlspecialchars(basename($_FILES["image"]["name"])) . " a été téléchargé avec succès.";
            } else {
                $error_message = "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
            }
        } else {
            $error_message = "Le fichier n'est pas une image.";
        }


        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            $error_message = "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            $error_message = "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            $error_message = "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["image"]["size"] > 500000) {
            $error_message = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow only certain file formats
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            $error_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $error_message = "Sorry, your file was not uploaded.";
        } else {
            // If everything is ok, try to upload file
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $error_message = "The file " . basename($_FILES["image"]["name"]) . " has been uploaded.";
            } else {
                $error_message = "Sorry, there was an error uploading your file.";
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
    if (isset ($questions)) {
        $userQuizQuestionFile = 'user_quiz_question.csv';
        foreach ($questions as $question) {
            $points = $_POST['points'];
            $questionData = [getNextQuizId($userQuizQuestionFile), $lastQuizId, $question, $points, "qcm"];
            insertIntoCSV($userQuizQuestionFile, $questionData);

            // Obtention de l'ID de la dernière question insérée
            $lastQuestionId = getNextQuizId($userQuizQuestionFile) - 1;

            // Insertion des réponses dans le fichier user_quiz_answer.csv
            $answers = $_POST['answers'];
            if (isset ($answers[$question])) {
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
    if (isset ($freeQuestions)) {
        $userQuizQuestionFile = 'user_quiz_question.csv';
        foreach ($freeQuestions as $freeQuestion) {
            $questionData = [getNextQuizId($userQuizQuestionFile), $lastQuizId, $freeQuestion, -1, 'libre'];
            insertIntoCSV($userQuizQuestionFile, $questionData);
        }
    }

    $error_message = "Quiz enregistré avec succès!";
    header('location: index.php');
}

// Fonction pour insérer des données dans un fichier CSV
function insertIntoCSV($filename, $data)
{
    $file = fopen($filename, 'a+');
    fputcsv($file, $data);
    fclose($file);
}

// Fonction pour obtenir l'ID du prochain enregistrement dans un fichier CSV
function getNextQuizId($filename)
{
    $file = fopen($filename, 'r');
    $nextId = 0;

    if ($file !== false) {
        while (($row = fgetcsv($file)) !== false) {
            $nextId = max($nextId, (int) $row[0]);
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
            $error_message = '<button type="button" onclick="addQuestionWithFreeResponse()">Ajouter une question avec une réponse libre</button>';
        }
        ?>
        <div class="container-img">
            <input type="file" name="image" id="file" style="display: none;">
            <div class="img-area">
                <i class='bx bx-upload icon'></i>
                <h2>Importer des images</h2>
                <p>L'image doit peser moins de <span>2 MegaBytes</span></p>
            </div>
            <button type="button" class="select-image" onclick="document.getElementById('image').click();">Sélectionner une Image</button>
        </div>

        <div id="quizError">
            <?php echo $error_message; ?>
        </div>

    <button type="submit">Enregistrer le Quiz</button>
</form>

    <script src="./script/quiz.js"></script>
</body>

</html>