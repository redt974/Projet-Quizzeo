<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url(https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap);@import url(https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap);body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            background-color: var(--primary-color);
            font-family: -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Oxygen,Ubuntu,Cantarell,Fira Sans,Droid Sans,Helvetica Neue,sans-serif;
            margin: 0;
            overflow-x: hidden
        }

        body {
            margin: 0;
            font-family: 'Poppins';
            
            
        }
        nav {
            overflow: hidden;
            background-color: rgba(255,255,255,0.6); 
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            width: 100%;
            height: 50px;
            padding: 30px 0 20px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            border-bottom: 1px solid gray;
        }

        nav img{
            width: 200px;
        }

        nav img:hover{
            cursor: pointer;
        }

        nav a {
            color: #000;
            text-align: center;
            padding: 10px 15px;
            text-decoration: none;
            font-size: 20px;
            border-radius: 15px;
        }
        .bleu{
            background-color: #A592C4 ;
        }
        .rouge{
            background-color: #F05A5B;
        }
        .jaune{
            background-color: #FAB238;
        }

        nav a:hover {
            background-color:#adbce6 ;
            color: #FFF;
            transition: 0.3s;
        }

        nav a.active {
            background-color:#00B ;
            color: #FFF;
        }

        @media screen and (max-width: 600px) {
            nav a {
                float: none;
                display: block;
            }
        }
    </style>
</head>
<body>
    <nav> 
        <img src="./assets/quizzeo.png" alt="disney-title" />
        <a href="./index.php" class="bleu" <?php if(basename($_SERVER['PHP_SELF']) == 'index.php') echo 'class="active"'; ?>>Accueil</a> 
        <a href="./quiz.php" class="rouge" <?php if(basename($_SERVER['PHP_SELF']) == 'quiz.php') echo 'class="active"'; ?>>Attractions</a> 
        <?php             

            if(isset($_SESSION['email'][0])) {
                echo "<a href='./logout.php' class='jaune'>Logout</a>";
            } else {
                echo '<a href="./connexion.php" class="jaune" '.(basename($_SERVER['PHP_SELF']) == 'connexion.php'  ? 'class="active"' : '').'>Login</a>';
            }

        ?>
    </nav>
</body>
</html>
