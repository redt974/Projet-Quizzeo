<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/header.css">
    <script defer src="https://kit.fontawesome.com/b32d44622b.js" crossorigin="anonymous"></script>
</head>
<body>
    <nav>
        <a href="./preview.php">
            <img class="quizzeo-logo" src="./assets/quizzeo.png" alt="quizzeo-logo" />
        </a>
        <?php
        if (isset($_SESSION['email'])) {
            $currentPage = basename($_SERVER['PHP_SELF']);

            echo '<a href="#" class="jaune" id="userDropdown"><div class="user"><i class="fa-solid fa-user fa-lg"></i></div></a>';
            echo '<div id="dropdownContent" style="display:none;">';
            if ($currentPage == 'utilisateur.php') {
                echo '<a href="./logout.php">Se déconnecter</a>';
                echo '<a href="./index.php">Retour à l\'accueil</a>';
            } else {
                echo '<a href="./logout.php">Se déconnecter</a>';
                echo '<a href="./utilisateur.php">Profil</a>';
            }
            echo '</div>';
        }
        ?>
        <script src="./script/header.js"></script>
    </nav>
</body>
</html>