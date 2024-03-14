<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url(https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap);@import url(https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap);
        body {
            margin: 0;
            font-family: 'Poppins';
            background-color: #FFF;
        }
        :root{
            --primary-color : #FFF;
            --secondary-color : #000;
        }
        body.dark{
            --primary-color :#000;
            --secondary-color : #FFF;
        }
        nav {
            overflow: hidden;
            background-color: rbga(255,255,255, 0.6);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            width: 100%;
            padding: 25px 0;
            display: flex;
            justify-content: space-around;
            align-items: center;
            border-bottom: 1px solid gray;
        }

        nav .quizzeo-logo{
            width: 200px;
        }

        nav .quizzeo-logo:hover{
            cursor: pointer;
        }
        nav .icon {
            width: 48px;
            height: 48px;
        }
        nav .icon:hover {
            cursor: pointer;
        }

        nav a {
            color: #000;
            text-align: center;
            padding: 7px;
            text-decoration: none;
            border-radius: 30px;
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
            background-color:#FFCF85 ;
            transition: 0.3s;
        }
        nav a.active {
            background-color:#00B ;
        }

        .user {
            border: 2px solid #000;
            border-radius: 25px;
            height: 35px;
            justify-content: center;
            width: 35px;
            display: flex;
            align-items:center;
        }
        .logout{
            border: none;
            padding: 2px;
            border-radius: 25px;
            justify-content: center;
            display: flex;
            align-items:center;
            width:35px;
            height:35px;
        }


        @media screen and (max-width: 600px) {
            nav a {
                float: none;
                display: block;
            }
        }
        
    </style>
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
