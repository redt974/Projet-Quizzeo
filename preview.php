<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Accueil</title>
  <link rel="icon" href='./assets/quizzeo.ico' />
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.1/css/all.min.css'>
  <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Montserrat&amp;display=swap"rel="stylesheet'>
  <link rel="stylesheet" href="./style/preview.css">
</head>
<body>
  
<div class="app">

  <div class="cardList">
    <button class="cardList__btn btn btn--left">
      <div class="icon">
        <svg>
          <use xlink:href="#arrow-left"></use>
        </svg>
      </div>
    </button>

    <div class="cards__wrapper">
      <?php
        // Tableau contenant les chemins des images
        $image_paths = ["./assets/standarduser.jpg", "./assets/ecole.jpg", "./assets/entreprise2.jpg"];
        // Tableau contenant les informations
        $informations = [
          ["name" => "quizzeo", "location" => "simple utilisateur ?", "description" => " Vous pouvez répondre aux questionnaires,<br> consulter les questionnaires complétés,<br> et accéder aux quiz via des liens directs fournis."],
          ["name" => "quizzeo", "location" => "Etablissement scolaire ?", "description" => "Vous pouvez fournir un accès sécurisé <br> à un tableau de bord pour les écoles, <br>permettant de créer, gérer et visualiser les <br> résultats des quiz, ainsi que de suivre <br>la progression des élèves dans leurs activités <br> d'apprentissage."],
          ["name" => "quizzeo", "location" => "Entreprise ?", "description" => "Vous avez accès à un tableau de bord <br>vous permettant de gérer vos quiz, <br> utilisateurs, et de visualiser les réponses, <br> avec la possibilité de créer de nouveaux quiz <br> et d'obtenir des statistiques détaillées <br> sur les réponses des participants."]
        ];

        // Boucle pour afficher les cartes
        for ($i = 0; $i < count($image_paths); $i++) {
          echo '<div class="card' . ($i === 0 ? ' current--card' : ($i === 1 ? ' next--card' : ' previous--card')) . '">
                  <div class="card__image">
                    <img src="' . $image_paths[$i] . '" alt="" />
                  </div>
                </div>';
        }
      ?>
    </div>

    <button class="cardList__btn btn btn--right">
      <div class="icon">
        <svg>
          <use xlink:href="#arrow-right"></use>
        </svg>
      </div>
    </button>
  </div>

  <div class="infoList">
    <div class="info__wrapper">
      <?php
        // Boucle pour afficher les informations
        for ($i = 0; $i < count($informations); $i++) {
          echo '<div class="info' . ($i === 0 ? ' current--info' : ($i === 1 ? ' next--info' : ' previous--info')) . '">
                  <h1 class="text name"><img src="./assets/' . $informations[$i]["name"] . '.png" alt="quizzeo-logo" style="height: 30px;" alt=""></h1>
                  <h4 class="text location">' . $informations[$i]["location"] . '</h4>
                  <p class="text description">' . $informations[$i]["description"] . '</p>
                </div>';
        }
      ?>
    </div>
  </div>
  <?php
    // Vérifiez si vous êtes sur la page preview.php
    $is_preview_page = strpos($_SERVER['REQUEST_URI'], 'preview.php') !== false;
    // Afficher le lien de retour à la page précédente si vous êtes sur preview.php
    if ($is_preview_page) {
      echo '<form action="javascript:history.go(-1)">    
              <input type="submit" value="Retour">
            </form>';
    }
  ?>
  <div class="app__bg">
    <div class="app__bg__image current--image">
      <img src="./assets/standarduser.jpg" alt="" />
    </div>
    <div class="app__bg__image next--image">
      <img src="./assets/ecole.jpg" alt="" />
    </div>
    <div class="app__bg__image previous--image">
      <img src="./assets/entreprise2.jpg" alt="" />
    </div>
  </div>
</div>

<div class="loading__wrapper">
  <div class="loader--text"><img src="./assets/quizzeo.png" alt="quizzeo-logo" alt=""> </div>
  <div class="loader">
    <span></span>
  </div>
</div>

<svg class="icons" style="display: none;">
  <symbol id="arrow-left" xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'>
    <polyline points='328 112 184 256 328 400'
             style='fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:48px' />
  </symbol>
  <symbol id="arrow-right" xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'>
    <polyline points='184 112 328 256 184 400'
             style='fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:48px' />
  </symbol>
</svg>
<script src='https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/gsap/3.3.3/gsap.min.js'></script>
<script  src="./script/preview.js"></script>
</body>
</html>
