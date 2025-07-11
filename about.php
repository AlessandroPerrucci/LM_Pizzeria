<?php session_start(); ?>



<!DOCTYPE html>
<html lang="en">

<head>
	<title>About</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="icon" type="image/x-icon" href="./icons/pizza.ico">

	<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Nothing+You+Could+Do" rel="stylesheet">

	<link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
	<link rel="stylesheet" href="css/animate.css">

	<link rel="stylesheet" href="css/owl.carousel.min.css">
	<link rel="stylesheet" href="css/owl.theme.default.min.css">
	<link rel="stylesheet" href="css/magnific-popup.css">

	<link rel="stylesheet" href="css/aos.css">

	<link rel="stylesheet" href="css/ionicons.min.css">

	<link rel="stylesheet" href="css/bootstrap-datepicker.css">
	<link rel="stylesheet" href="css/jquery.timepicker.css">


	<link rel="stylesheet" href="css/flaticon.css">
	<link rel="stylesheet" href="css/icomoon.css">
	<link rel="stylesheet" href="css/style.css">
</head>

<body>
	<?php $pagina_attiva = 'about'; ?>
	<?php include 'header.php'; ?>
	<!-- END nav -->

	<section class="home-slider owl-carousel img" style="background-image: url(images/bg_1.jpg);">

		<div class="slider-item" style="background-image: url(images/bg_3.jpg);">
			<div class="overlay"></div>
			<div class="container">
				<div class="row slider-text justify-content-center align-items-center">

					<div class="col-md-7 col-sm-12 text-center ftco-animate">
						<h1 class="mb-3 mt-5 bread">Chi siamo</h1>
						<p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home</a></span> <span>Chi siamo</span></p>
					</div>

				</div>
			</div>
		</div>
	</section>


	<section class="ftco-intro">
		<div class="container-wrap">
			<div class="wrap d-md-flex">
				<div class="info">
					<div class="row no-gutters">
						<div class="col-md-4 d-flex ftco-animate">
							<div class="icon"><span class="icon-phone"></span></div>
							<div class="text">
								<h3>+39 345 571 947</h3>
								<p>A small river named Duden flows</p>
							</div>
						</div>
						<div class="col-md-4 d-flex ftco-animate">
							<div class="icon"><span class="icon-my_location"></span></div>
							<div class="text">
								<h3>Via Antonio Cannavacciuolo, 69</h3>
								<p>Italia, Napoli, NA, 80125</p>
							</div>
						</div>
						<div class="col-md-4 d-flex ftco-animate">
							<div class="icon"><span class="icon-clock-o"></span></div>
							<div class="text">
								<h3>Aperti tutti i giorni</h3>
								<p>12:00-15:00, 19:00-23:00</p>
							</div>
						</div>
					</div>
				</div>
				<div class="social d-md-flex pl-md-5 p-4 align-items-center">
					<ul class="social-icon">
						<li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
						<li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
						<li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
					</ul>
				</div>
			</div>
		</div>
	</section>

	<section class="ftco-about d-md-flex">
		<div class="one-half img" style="background-image: url(images/about.jpg);"></div>
		<div class="one-half ftco-animate">
			<div class="heading-section ftco-animate ">
				<h2 class="mb-4">Welcome to <span class="flaticon-pizza">L.M Pizzeria</span></h2>
			</div>
			<div>
				<p>Da L.M Pizzeria portiamo in tavola l’anima della vera pizza italiana. Le nostre ricette si ispirano alla tradizione autentica, realizzate con ingredienti locali e stagionali, selezionati con cura. Ogni pizza racconta una storia di gusto, semplicità e passione.</p>
			</div>
		</div>
	</section>

	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center mb-5 pb-3">
				<div class="col-md-7 heading-section ftco-animate text-center">
					<h2 class="mb-4">I nostri chef</h2>
					<p class="flip"><span class="deg1"></span><span class="deg2"></span><span class="deg3"></span></p>
					<p class="mt-5">Il cuore della nostra cucina batte nelle mani dei nostri chef: esperti, creativi e innamorati della tradizione. Con anni di esperienza e un’attenzione maniacale per la qualità, trasformano ogni impasto in un’opera d’arte fragrante, fedele alla vera scuola italiana della pizza.</p>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-3 d-flex mb-sm-4 ftco-animate">
					<div class="staff">
						<div class="img mb-4" style="background-image: url(images/person_1.jpg);"></div>
						<div class="info text-center">
							<h3><a href="teacher-single.php">Katalin Contrada</a></h3>
							<span class="position">Specialista della pizza</span>
							<div class="text">
								<p>Con tecnica raffinata e gusto impeccabile, Katalin crea pizze che fondono tradizione e originalità. Ogni impasto è frutto di precisione e passione.</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 d-flex mb-sm-4 ftco-animate">
					<div class="staff">
						<div class="img mb-4" style="background-image: url(images/person_2.jpg);"></div>
						<div class="info text-center">
							<h3><a href="teacher-single.php">Marco Aurelio</a></h3>
							<span class="position">Specialista dei cocktail</span>
							<div class="text">
								<p>Con stile e creatività, Marco trasforma ogni drink in un’esperienza. I suoi cocktail accompagnano perfettamente le nostre pizze, esaltandone i sapori.</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 d-flex mb-sm-4 ftco-animate">
					<div class="staff">
						<div class="img mb-4" style="background-image: url(images/Luigi.png);"></div>
						<div class="info text-center">
							<h3><a href="teacher-single.php">Luigi Luceforte</a></h3>
							<span class="position">Il Proprietario</span>
							<div class="text">
								<p>Fondatore e anima della pizzeria, Luigi custodisce le ricette originali della tradizione partenopea, mantenendo alta la qualità in ogni dettaglio.</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 d-flex mb-sm-4 ftco-animate">
					<div class="staff">
						<div class="img mb-4" style="background-image: url(images/Cesare.png);"></div>
						<div class="info text-center">
							<h3><a href="teacher-single.php">Cesare Cesaroni</a></h3>
							<span class="position">Maestro pizzaiolo</span>
							<div class="text">
								<p>Esperto nei classici Romani, Cesare è il volto della pizza verace: croccante, saporita e sempre fatta con amore per la tradizione.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>


	<section class="ftco-counter ftco-bg-dark img" id="section-counter" style="background-image: url(images/bg_2.jpg);" data-stellar-background-ratio="0.5">
		<div class="overlay"></div>
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-10">
					<div class="row">
						<div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
							<div class="block-18 text-center">
								<div class="text">
									<div class="icon"><span class="flaticon-pizza-1"></span></div>
									<strong class="number" data-number="100">0</strong>
									<span>Filiali</span>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
							<div class="block-18 text-center">
								<div class="text">
									<div class="icon"><span class="flaticon-medal"></span></div>
									<strong class="number" data-number="85">0</strong>
									<span>Numero di premiazioni</span>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
							<div class="block-18 text-center">
								<div class="text">
									<div class="icon"><span class="flaticon-laugh"></span></div>
									<strong class="number" data-number="10567">0</strong>
									<span>Clienti soddisfatti</span>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
							<div class="block-18 text-center">
								<div class="text">
									<div class="icon"><span class="flaticon-chef"></span></div>
									<strong class="number" data-number="900">0</strong>
									<span>Membri del personale</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>


	<section class="ftco-appointment">
		<div class="overlay"></div>
		<div class="container-wrap">
			<div class="row no-gutters d-md-flex align-items-center">
				<div class="col-md-6 appointment ftco-animate">
					<h3 class="mb-3">Contattaci</h3>
					<form action="#" class="appointment-form">
						<div class="d-md-flex">
							<div class="form-group">
								<input type="text" class="form-control" placeholder="Nome">
							</div>
						</div>
						<div class="d-me-flex">
							<div class="form-group">
								<input type="text" class="form-control" placeholder="Cognome">
							</div>
						</div>
						<div class="form-group">
							<textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Messaggio"></textarea>
						</div>
						<div class="form-group">
							<input type="submit" value="Invio" class="btn btn-primary py-3 px-4">
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>

	<?php include 'footer.php'; ?>



	<!-- loader -->
	<div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
			<circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
			<circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00" />
		</svg></div>


	<script src="js/jquery.min.js"></script>
	<script src="js/jquery-migrate-3.0.1.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.easing.1.3.js"></script>
	<script src="js/jquery.waypoints.min.js"></script>
	<script src="js/jquery.stellar.min.js"></script>
	<script src="js/owl.carousel.min.js"></script>
	<script src="js/jquery.magnific-popup.min.js"></script>
	<script src="js/aos.js"></script>
	<script src="js/jquery.animateNumber.min.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<script src="js/jquery.timepicker.min.js"></script>
	<script src="js/scrollax.min.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
	<script src="js/google-map.js"></script>
	<script src="js/main.js"></script>

</body>

</html>