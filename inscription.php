<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="icon" href='./assets/quizzeo.ico' />
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-around;
            height: 100%;
            color: #000000; 
            backdrop-filter: blur(10px);
        }
        h1 {
            text-align: center;
            font-size: 56px;
            margin: 16px 0;
        }
        form {
            padding: 20px;
            background-color: rgba(255,255,255,0.4); 
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
            margin-bottom: 20px;
            width: 35%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .form-group {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .label-input {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 20px;
        }

        label {
            display: block;
            font-size: 20px;
            font-weight: 600;
            width: 320px; 
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            width: 100%;
        }
        input[type="submit"], .inscription {
            background-color: #00B;
            color: white;
            padding: 15px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: flex;
            justify-content: center;
            text-decoration: none;
        }
        input[type="submit"]:hover {
            background-color: #adbce6; 
        }
        .switch-page{
            margin-bottom: 50px;
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
    <?php 
        include './components/header.php';
    ?>
    <h1>Inscription</h1>
    <form action="register.php" method="post">
        <div class="form-group">
            <div class="label-input">
                <label for="fname">Nom :</label>
                <input type="text" id="fname" name="fname" required>
            </div>
        </div>

        <div class="form-group">
            <div class="label-input">
                <label for="lname">Prenom :</label>
                <input type="text" id="lname" name="lname" required>
            </div>
        </div>

        <div class="form-group">
            <div class="label-input">
                <label for="email">Addresse Mail :</label>
                <input type="email" id="email" name="email" required>
            </div>
        </div>

        <div class="form-group">
            <div class="label-input">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>
        </div>

        <input type="submit" value="Inscription">
    </form>
    <div class="switch-page">
        <h2>Vous avez déjà un compte ?</h2><a class="inscription" href="connexion.php">Connectez-vous !</a>
    </div>
</body>
</html>
