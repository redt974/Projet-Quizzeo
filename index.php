<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="icon" href='./assets/quizzeo.ico' />
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        ::-webkit-scrollbar{
            width: 12px;
            height: 12px;
        }

        ::-webkit-scrollbar-track{
            background: none;
        }

        ::-webkit-scrollbar-thumb{
            background-color: rgb(61, 61, 61);
            border-radius: 12px;
        }

        ::-webkit-scrollbar-thumb:hover{
            background-color: rgb(46, 46, 46);
            border-radius: 12px;
        }

        ::-webkit-scrollbar-corner{
            background: none;
        }
         
    </style>
</head>
<body>
    <?php 
        include './components/header.php';           
    ?>
    <?php if (!isset($_SESSION["email"])) : 
        header("Location: connexion.php");
    ?>
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