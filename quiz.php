<?php
session_start();

// Charger les attractions depuis le fichier CSV
$attractions = [];
$file = fopen("quiz.csv", "r");

if ($file !== false) {
    // Ignorer la première ligne (en-tête)
    fgetcsv($file);

    // Lire chaque ligne du fichier CSV
    while ($attraction = fgetcsv($file)) {
        $attractions[] = [
            'id' => $attraction[0],
            'name' => $attraction[1],
            'image' => $attraction[2],
            'description' => $attraction[3]
        ];
    }

    fclose($file);
}

// Ajouter ou supprimer des attractions aux favoris
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && isset($_POST["attractionId"])) {
    $action = $_POST["action"];
    $attractionId = $_POST["attractionId"];

    if ($action == "add" && !in_array($attractionId, $_SESSION["favorites"])) {
        $_SESSION["favorites"][] = $attractionId;
    } else if ($action == "remove" && ($index = array_search($attractionId, $_SESSION["favorites"])) !== false) {
        unset($_SESSION["favorites"][$index]);
    }

    // Redirection pour éviter la soumission du formulaire lors du rafraîchissement
    header("Location: quiz.php");
    exit();
}

// En-tête HTML
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link rel="icon" href='./assets/quizzeo.ico' />
    <style>
        /* Add CSS to style your page if necessary */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-attachment: fixed;
            backdrop-filter: blur(10px);
            height: 100%;
        }
        h1 {
            font-size : 92px;
            height: 35vh;
        }
        p{
            font-size : 34px;
            height: 35vh;
        }
        table {
            background-color: #DCDCDC;
            width: 90%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 100px;
        }
        table, th, td {
            border: 1px solid #ddd;            
        }

        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #C0C0C0;
        }
        .table-img {
            max-width: 100px;
            max-height: 100px;
        }
        ::-webkit-scrollbar{
            width: 12px;
            height: 12px;
        }

        ::-webkit-scrollbar-track{
            background: none;
        }

        ::-webkit-scrollbar-thumb{
            background-color: #DCDCDC;
            border-radius: 12px;
        }

        ::-webkit-scrollbar-thumb:hover{
            background-color: #C0C0C0;
            border-radius: 12px;
        }

        ::-webkit-scrollbar-corner{
            background: none;
        }
    </style>
</head>
<body>
    <?php include './components/header.php'; ?>

    <h1>Disneyland Attractions</h1>

    <?php if (!isset($_SESSION["email"])) : ?>
        <p>Vous devez être connecté pour voir les attractions !</p>
    <?php else : ?>
        <!-- Afficher le tableau des attractions -->
        <?php if (!empty($attractions)) : ?>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Description</th>
                    <th>Favoris</th>
                </tr>
                <?php foreach ($attractions as $attraction) : ?>
                    <tr>
                        <td><?= $attraction['name'] ?></td>
                        <td><img class="table-img" src="<?= $attraction['image'] ?>" alt="<?= $attraction['name'] ?>"></td>
                        <td><?= $attraction['description'] ?></td>
                        <td>
                            <!-- Formulaire pour ajouter ou supprimer des favoris -->
                            <?php if (in_array($attraction['id'], $_SESSION["favorites"])) : ?>
                                <form method="post" action="quiz.php">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="attractionId" value="<?= $attraction['id'] ?>">
                                    <button type="submit">Enlever des favoris</button>
                                </form>
                            <?php else : ?>
                                <form method="post" action="quiz.php">
                                    <input type="hidden" name="action" value="add">
                                    <input type="hidden" name="attractionId" value="<?= $attraction['id'] ?>">
                                    <button type="submit">Ajouter aux favoris</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else : ?>
            <p>No attractions found.</p>
        <?php endif; ?>

        <!-- Afficher le tableau des favoris -->
        <?php if ($_SESSION["favorites"] !== [null]) : ?>
            <h1>Vos Attractions favorites</h1>
            <table id='favoritesTable'>
                <tr>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Description</th>
                    <th>Favoris</th>
                </tr>
                <?php foreach ($_SESSION["favorites"] as $favAttractionId) : ?>
                    <?php
                    $favAttraction = $attractions[array_search($favAttractionId, array_column($attractions, 'id'))];
                    ?>
                    <tr data-id='<?= $favAttractionId ?>'>
                        <td><?= $favAttraction['name'] ?></td>
                        <td><img class="table-img" src='<?= $favAttraction['image'] ?>' alt='<?= $favAttraction['name'] ?>'></td>
                        <td><?= $favAttraction['description'] ?></td>
                        <td>
                            <form method='post' action='quiz.php'>
                                <input type='hidden' name='action' value='remove'>
                                <input type='hidden' name='attractionId' value='<?= $favAttractionId ?>'>
                                <button type='submit'>Enlever des favoris</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else : ?>
            <h2>Vos Attractions favorites</h2>
            <table id='favoritesTable'>
                <tr>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Description</th>
                    <th>Favorites</th>
                </tr>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>

