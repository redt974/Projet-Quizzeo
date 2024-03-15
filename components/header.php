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
        <img class="quizzeo-logo" src="./assets/quizzeo.png" alt="quizzeo-logo" />
        <?php             
            if(isset($_SESSION['email'])) {
                echo "<a href='./logout.php' class='jaune'><div class='logout'><i class='fa-solid fa-arrow-right-from-bracket'></i></div></a>";
            } else {
                echo '<a href="./connexion.php" class="jaune" '.(basename($_SERVER['PHP_SELF']) == 'connexion.php'  ? 'class="active"' : '').'><div class="user"><i class="fa-solid fa-user fa-lg"></i></div></a>';
            }
        ?>
    </nav>
</body>
</html>
