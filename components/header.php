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
            background-color: var(--primary-color);
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
            background-color: rbga(var(--primary-color), 0.6);
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

        nav .quizzeo-title{
            width: 200px;
        }

        nav .quizzeo-title:hover{
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
            color: var(--secondary-color);
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
            border: 2px solid var(--secondary-color);
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
        <img class="quizzeo-title" src="./assets/quizzeo.png" alt="disney-title" />
        <?php             
            if(isset($_SESSION['email'])) {
                echo "<a href='./logout.php' class='jaune'><div class='logout'><i class='fa-solid fa-arrow-right-from-bracket'></i></div></a>";
            } else {
                echo '<a href="./connexion.php" class="jaune" '.(basename($_SERVER['PHP_SELF']) == 'connexion.php'  ? 'class="active"' : '').'><div class="user"><i class="fa-solid fa-user fa-lg"></i></div></a>';
            }
        ?>
        <img id="icon" class="icon" alt="Toggle Theme" src="./assets/moon.png" />
        <script>
            const icon = document.getElementById('icon'); // Remplacez 'yourIconId' par l'ID de votre icône

            const toggleTheme = () => {
                const isDarkTheme = document.body.classList.toggle("dark");
                const newTheme = isDarkTheme ? 'dark' : 'light';
                icon.src = `./assets/${newTheme === 'dark' ? 'sun' : 'moon'}.png`;
                localStorage.setItem('theme', newTheme);
            };

            if (icon) {
                const isDarkTheme = localStorage.getItem('theme') === 'dark';
                icon.src = `./assets/${isDarkTheme ? 'sun' : 'moon'}.png`;
                document.body.classList.toggle("dark", isDarkTheme);

                icon.addEventListener("click", toggleTheme);
            }
        </script>
    </nav>
</body>
</html>
