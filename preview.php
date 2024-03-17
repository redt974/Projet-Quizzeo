<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Accueil</title>
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
			<div class="card current--card">
				<div class="card__image">
					<img src="./assets/standarduser.jpg" alt="" />
				</div>
			</div>

			<div class="card next--card">
				<div class="card__image">
					<img src="./assets/ecole.jpg" alt="" />
				</div>
			</div>

			<div class="card previous--card">
				<div class="card__image">
					<img src="./assets/entreprise2.jpg" alt="" />
				</div>
			</div>
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
			<div class="info current--info">
				<h1 class="text name"><img src="./assets/quizzeo.png" alt="quizzeo-logo" style="height: 30px;" alt=""></h1>
				<h4 class="text location">simple utilisateur ? </h4>
				<p class="text description"> Vous pouvez répondre aux questionnaires,<br> consulter les questionnaires complétés,<br> et accéder aux quiz via des liens directs fournis.</p>
			</div>

			<div class="info next--info">
				<h1 class="text name"><img src="./assets/quizzeo.png" alt="quizzeo-logo" style="height: 30px;" alt=""></h1>
				<h4 class="text location">Etablissement scolaire ?</h4>
				<p class="text description">Vous pouvez fournir un accès sécurisé <br> à un tableau de bord pour les écoles, <br>permettant de créer, gérer et visualiser les <br> résultats des quiz, ainsi que de suivre <br>la progression des élèves dans leurs activités <br> d'apprentissage.</p>
			</div>

			<div class="info previous--info">
				<h1 class="text name"><img src="./assets/quizzeo.png" alt="quizzeo-logo" style="height: 30px;" alt=""></h1>
				<h4 class="text location">Entreprise ?</h4>
				<p class="text description">Vous avez accès à un tableau de bord <br>vous permettant de gérer vos quiz, <br> utilisateurs, et de visualiser les réponses, <br> avec la possibilité de créer de nouveaux quiz <br> et d'obtenir des statistiques détaillées <br> sur les réponses des participants.</p>
			</div>
		</div>
	</div>
    <form action="connexion.php">
    <input type="submit" value="Y ALLER">
    </form>



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
