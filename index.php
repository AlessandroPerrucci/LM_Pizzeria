<?php session_start();
require_once 'config.php';
$stmt = $pdo->query("SELECT * FROM pizza");
$pizze = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmtCon = $pdo->query("SELECT * FROM contorno");
$contorni = $stmtCon->fetchAll(PDO::FETCH_ASSOC);
$stmtAnti = $pdo->query("SELECT * FROM antipasto");
$antipasti = $stmtAnti->fetchAll(PDO::FETCH_ASSOC);
$stmtSec = $pdo->query("SELECT * FROM secondo");
$secondi = $stmtSec->fetchAll(PDO::FETCH_ASSOC);
$stmtBev = $pdo->query("SELECT * FROM bevanda");
$bevande = $stmtBev->fetchAll(PDO::FETCH_ASSOC);

#------------------[Robba per i blog]---------------------------------
// 6) Query dei post paginati + filtro
$sql = "SELECT p.*, COUNT(c.id) AS comment_count
    FROM blog_posts p
    LEFT JOIN blog_comments c ON p.id = c.post_id
    $where
    GROUP BY p.id
    ORDER BY p.created_at DESC
    LIMIT :limit OFFSET :offset
    ";
$limit = isset($limit) ? (int)$limit : 2;
$offset = isset($offset) ? (int)$offset : 0;

$stmt = $pdo->prepare($sql);
// bind filtro
if ($categoryId) {
	$stmt->bindValue(':category', $categoryId, PDO::PARAM_INT);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (!isset($pdo)) {
	echo "<!-- Errore: connessione al database non disponibile -->";
	return;
}
// Recenti per il footer
$recentStmt = $pdo->prepare("
    SELECT p.id, p.title, p.content, p.created_at, p.image, p.author, COUNT(c.id) AS comment_count
    FROM blog_posts p
    LEFT JOIN blog_comments c ON p.id = c.post_id
    GROUP BY p.id
    ORDER BY p.created_at DESC
    LIMIT 3
");
$recentStmt->execute();
$recentPosts = $recentStmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<title>L.M. Pizzeria</title>
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
	<style>
		/* Fix completo per il layout delle card del menu */

/* Fix completo per il layout delle card del menu */

.tab-pane .row {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -15px;
}

.tab-pane .col-md-4 {
    padding: 15px;
    margin-bottom: 30px;
    display: flex;
    flex-direction: column;
}

.menu-wrap {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.menu-wrap:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.menu-wrap .menu-img {
    width: 100%;
    height: 220px;
    background-size: cover;
    background-position: center;
    display: block;
    border-radius: 0;
    margin-bottom: 0;
}

.menu-wrap .text {
    padding: 20px;
    text-align: center;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.menu-wrap .text h3 {
    margin-bottom: 12px;
    font-size: 20px;
    font-weight: 600;
    color: #333;
    line-height: 1.3;
}

.menu-wrap .text p {
    margin-bottom: 15px;
    font-size: 14px;
    line-height: 1.5;
    color: #666;
    flex: 1;
}

.menu-wrap .price {
    font-weight: bold;
    color: #F96D00;
    font-size: 18px;
    margin-bottom: 15px;
}

.menu-wrap .price span {
    font-size: 20px;
}

.menu-wrap .btn {
    margin-top: auto;
    padding: 10px 20px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.menu-wrap .btn-white {
    background-color: #F96D00;
    color: white;
    border: 2px solid #F96D00;
}

.menu-wrap .btn-white:hover {
    background-color: transparent;
    color: #F96D00;
    border: 2px solid #F96D00;
}

/* Responsive */
@media (max-width: 768px) {
    .tab-pane .col-md-4 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    .menu-wrap .menu-img {
        height: 200px;
    }
    
    .menu-wrap .text {
        padding: 15px;
    }
    
    .menu-wrap .text h3 {
        font-size: 18px;
    }
}

@media (min-width: 769px) and (max-width: 991px) {
    .tab-pane .col-md-4 {
        flex: 0 0 50%;
        max-width: 50%;
    }
}

@media (min-width: 992px) {
    .tab-pane .col-md-4 {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
    }
}

/* Assicura che tutte le card abbiano la stessa altezza */
.tab-pane .row {
    align-items: stretch;
}

/* Migliora la leggibilità del testo */
.menu-wrap .text h3,
.menu-wrap .text p {
    text-shadow: none;
}

/* Effetto loading per le immagini */
.menu-wrap .menu-img {
    background-color: #f8f9fa;
    position: relative;
}

.row .col-md-4:only-child .menu-wrap {
    width: 200px;
    margin: 0 auto;
}

.menu-wrap .menu-img::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    transform: translateX(-100%);
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

/* Rimuove l'animazione dopo il caricamento */
.menu-wrap .menu-img.loaded::before {
    display: none;
}
	</style>
</head>

<body>
	<?php $pagina_attiva = 'home'; ?>
	<?php include 'header.php'; ?>
	<!-- END nav -->

	<section class="home-slider owl-carousel img" style="background-image: url(images/bg_1.jpg);">
		<div class="slider-item">
			<div class="overlay"></div>
			<div class="container">
				<div class="row slider-text align-items-center" data-scrollax-parent="true">

					<div class="col-md-6 col-sm-12 ftco-animate">
						<span class="subheading">Delicious</span>
						<h1 class="mb-4">Pizza Italiana</h1>
						<p class="mb-4 mb-md-5">Deliziosa pizza Italiana, prodotta con i migliori prodotti locali, seguendo le ricette originali. </p>
						<p><a href="ordina.php" class="btn btn-primary p-3 px-xl-4 py-xl-3">Ordina ora</a> <a href="menu.php" class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">Vedi il Menu</a></p>
					</div>
					<div class="col-md-6 ftco-animate">
						<img src="images/bg_1.png" class="img-fluid" alt="">
					</div>

				</div>
			</div>
		</div>

		<div class="slider-item">
			<div class="overlay"></div>
			<div class="container">
				<div class="row slider-text align-items-center" data-scrollax-parent="true">

					<div class="col-md-6 col-sm-12 order-md-last ftco-animate">
						<span class="subheading">Croccante</span>
						<h1 class="mb-4">Pizza Italiana</h1>
						<p class="mb-4 mb-md-5">Deliziosa pizza Italiana, prodotta con i migliori prodotti locali, seguendo le ricette originali.</p>
						<p><a href="ordina.php" class="btn btn-primary p-3 px-xl-4 py-xl-3">Ordina ora</a> <a href="menu.php" class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">Vedi il Menu</a></p>
					</div>
					<div class="col-md-6 ftco-animate">
						<img src="images/bg_2.png" class="img-fluid" alt="">
					</div>

				</div>
			</div>
		</div>

		<div class="slider-item" style="background-image: url(images/bg_3.jpg);">
			<div class="overlay"></div>
			<div class="container">
				<div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">

					<div class="col-md-7 col-sm-12 text-center ftco-animate">
						<span class="subheading">Benvenuti</span>
						<h1 class="mb-4">Produciamo Pizza Italiana Autentica, mantenendo gli standard più elevati</h1>
						<p class="mb-4 mb-md-5">Deliziosa pizza Italiana, prodotta con i migliori prodotti locali, seguendo le ricette originali.</p>
						<p><a href="ordina.php" class="btn btn-primary p-3 px-xl-4 py-xl-3">Ordina ora</a> <a href="menu.php" class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">Vedi il Menu</a></p>
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
								<h3>+39 345 571 947 </h3>
								<p>Prenota un tavolo</p>
							</div>
						</div>
						<div class="col-md-4 d-flex ftco-animate">
							<div class="icon"><span class="icon-my_location"></span></div>
							<div class="text">
								<h3>Via Antonio Cannavacciuolo, 69,</h3>
								<p>Italia, Napoli, NA, 80125 </p>
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
				<h2 class="mb-4">Benvenuti a <span class="flaticon-pizza">L.M Pizzeria</span></h2>
			</div>
			<div>
				<p>Nella nostra pizzeria, ogni fetta racconta una storia: preparata con ingredienti locali, autentiche ricette italiane e passione per il gusto. Seguiamo standard elevati per offrire una pizza deliziosa, fresca, autentica e indimenticabile. Assapora l'Italia, un morso alla volta.</p>
			</div>
		</div>
	</section>

	<section class="ftco-section ftco-services">
		<div class="overlay"></div>
		<div class="container">
			<div class="row justify-content-center mb-5 pb-3">
				<div class="col-md-7 heading-section ftco-animate text-center">
					<h2 class="mb-4">I nostri servizi</h2>
					<p>Scopri i nostri servizi, e la qualità e origine dei nostri prodotti.</p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4 ftco-animate">
					<div class="media d-block text-center block-6 services">
						<div class="icon d-flex justify-content-center align-items-center mb-5">
							<span class="flaticon-diet"></span>
						</div>
						<div class="media-body">
							<h3 class="heading">Cibo Salutare</h3>
							<p>Per realizzare le nostre pizze autentiche utilizziamo solo i migliori ingredienti, accuratamente selezionati dai produttori locali.</p>
						</div>
					</div>
				</div>
				<div class="col-md-4 ftco-animate">
					<div class="media d-block text-center block-6 services">
						<div class="icon d-flex justify-content-center align-items-center mb-5">
							<span class="flaticon-bicycle"></span>
						</div>
						<div class="media-body">
							<h3 class="heading">Consegne rapide</h3>
							<p> I nostri autisti garantiscono una consegna rapida, portandoti la pizza alla temperatura perfetta: calda, fresca e pronta da gustare.</p>
						</div>
					</div>
				</div>
				<div class="col-md-4 ftco-animate">
					<div class="media d-block text-center block-6 services">
						<div class="icon d-flex justify-content-center align-items-center mb-5"><span class="flaticon-pizza-1"></span></div>
						<div class="media-body">
							<h3 class="heading">Ricette Originali</h3>
							<p>Ongi pizza è prodotto utilizzando vere ed autentiche ricette Italiane, imparate direttamente dai maestri originali.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center mb-5 pb-3">
				<div class="col-md-7 heading-section ftco-animate text-center">
					<h2 class="mb-4">Pizza al piatto</h2>
					<p>Le nostre pizze sono prodotte combinando autentiche tecniche Italiane aggiungendo la nostra immaginazione.</p>
				</div>
			</div>
		</div>
		<div class="container-wrap">
			<div class="row no-gutters d-flex">
				<?php
				$cont = 0;
				foreach ($pizze as $pizza):
					if ($cont > 5) {
						break;
					} ?>
					<div class="col-lg-4 d-flex ftco-animate">
						<div class="services-wrap d-flex">
							<?php
							$nomePizza = $pizza['nome']; // dal database
							$nomeFile = str_replace(' ', '_', $nomePizza);
							if ($cont > 2) {
								echo '<a href="#" class="img order-lg-last" style="background-image: url(images/FotoPizze/' . $nomeFile . '.jpg);"></a>';
							} else {
								echo '<a href="#" class="img" style="background-image: url(images/FotoPizze/' . $nomeFile . '.jpg);"></a>';
							}
							?>
							<div class="text p-4">
								<h3><?php echo $pizza['nome'] ?></h3>
								<p> <?php echo $pizza['descrizione'] ?> </p>
								<p class="price"><span>€<?php echo $pizza['prezzo'] ?></span> <a href="ordina.php" class="ml-2 btn btn-white btn-outline-white">Order</a></p>
							</div>
						</div>
					</div>
				<?php
					$cont++;
				endforeach; ?>
			</div>
		</div>

		<div class="container">
			<div class="row justify-content-center mb-5 pb-3 mt-5 pt-5">
				<div class="col-md-7 heading-section text-center ftco-animate">
					<h2 class="mb-4">Prezzi del menu</h2>
					<p class="flip"><span class="deg1"></span><span class="deg2"></span><span class="deg3"></span></p>
					<p class="mt-5">Verifica online il prezzo del tuo ordine.</p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<?php foreach ($pizze as $pizza): ?>
						<div class="pricing-entry d-flex ftco-animate">
							<?php
							$nomePizza = $pizza['nome']; // dal database
							$nomeFile = str_replace(' ', '_', $nomePizza);
							echo '<div class="img" style="background-image: url(images/FotoPizze/' . $nomeFile . '.jpg);"></div>'
							?>
							<div class="desc pl-3">
								<div class="d-flex text align-items-center">
									<h3><span><?php echo $pizza['nome'] ?></span></h3>
									<span class="price">€<?php echo $pizza['prezzo'] ?></span>
								</div>
								<div class="d-block">
									<p><?php echo $pizza['descrizione'] ?> </p>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>

	<section class="ftco-gallery">
		<div class="container-wrap">
			<div class="row no-gutters">
				<div class="col-md-3 ftco-animate">
					<a href="#" class="gallery img d-flex align-items-center" style="background-image: url(images/gallery-1.jpg);">
						<div class="icon mb-4 d-flex align-items-center justify-content-center">
							<span class="icon-search"></span>
						</div>
					</a>
				</div>
				<div class="col-md-3 ftco-animate">
					<a href="#" class="gallery img d-flex align-items-center" style="background-image: url(images/gallery-2.jpg);">
						<div class="icon mb-4 d-flex align-items-center justify-content-center">
							<span class="icon-search"></span>
						</div>
					</a>
				</div>
				<div class="col-md-3 ftco-animate">
					<a href="#" class="gallery img d-flex align-items-center" style="background-image: url(images/gallery-3.jpg);">
						<div class="icon mb-4 d-flex align-items-center justify-content-center">
							<span class="icon-search"></span>
						</div>
					</a>
				</div>
				<div class="col-md-3 ftco-animate">
					<a href="#" class="gallery img d-flex align-items-center" style="background-image: url(images/gallery-4.jpg);">
						<div class="icon mb-4 d-flex align-items-center justify-content-center">
							<span class="icon-search"></span>
						</div>
					</a>
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

	<section class="ftco-menu">
		<div class="container-fluid">
			<div class="row d-md-flex">
				<div class="col-lg-4 ftco-animate img f-menu-img mb-5 mb-md-0" style="background-image: url(images/about.jpg);">
				</div>
				<div class="col-lg-8 ftco-animate p-md-5">
					<div class="row">
						<div class="col-md-12 nav-link-wrap mb-5">
							<div class="nav ftco-animate nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
								<a class="nav-link active" id="v-pills-1-tab" data-toggle="pill" href="#v-pills-1" role="tab" aria-controls="v-pills-1" aria-selected="true">Contorni</a>

								<a class="nav-link" id="v-pills-2-tab" data-toggle="pill" href="#v-pills-2" role="tab" aria-controls="v-pills-2" aria-selected="false">Drinks</a>

								<a class="nav-link" id="v-pills-3-tab" data-toggle="pill" href="#v-pills-3" role="tab" aria-controls="v-pills-3" aria-selected="false">Antipasti</a>

								<a class="nav-link" id="v-pills-4-tab" data-toggle="pill" href="#v-pills-4" role="tab" aria-controls="v-pills-4" aria-selected="false">Secondi</a>
							</div>
						</div>
						<div class="col-md-12 d-flex align-items-center">

							<div class="tab-content ftco-animate" id="v-pills-tabContent">

								<div class="tab-pane fade show active" id="v-pills-1" role="tabpanel" aria-labelledby="v-pills-1-tab">
									<div class="row">
										<?php foreach ($contorni as $contorno):
											$nomeContorno = $contorno['nome']; // dal database
											$nomeFile = str_replace(' ', '_', $nomeContorno);
										?>
											<div class="col-md-4 text-center">
												<div class="menu-wrap">
													<?php echo '<a href="#" class="menu-img img mb-4" style="background-image: url(images/FotoContorni/' . $nomeFile . '.jpg);"></a>'; ?>
													<div class="text">
														<h3> <?php echo $contorno['nome'] ?> </h3>
														<p> <?php echo $contorno['descrizione'] ?> </p>
														<p class="price"><span>€ <?php echo $contorno['prezzo'] ?></span></p>
														<p><a href="ordina.php" class="btn btn-white btn-outline-white">Aggiungi al carrello</a></p>
													</div>
												</div>
											</div>
										<?php endforeach; ?>
									</div>
								</div>

								<div class="tab-pane fade" id="v-pills-2" role="tabpanel" aria-labelledby="v-pills-2-tab">
									<div class="row">
										<?php foreach ($bevande as $bevanda):
											$nomeBevanda = $bevanda['nome']; // dal database
											$nomeFile = str_replace(' ', '_', $nomeBevanda);

										?>
											<div class="col-md-4 text-center">
												<div class="menu-wrap">
													<?php echo '<a href="#" class="menu-img img mb-4" style="background-image: url(images/FotoBevande/' . $nomeFile . '.jpg);"></a>'; ?>
													<div class="text">
														<h3> <?php echo $bevanda['nome'] ?> </h3>
														<p> <?php echo $bevanda['descrizione'] ?> </p>
														<p class="price"><span>€ <?php echo $bevanda['prezzo'] ?></span></p>
														<p><a href="ordina.php" class="btn btn-white btn-outline-white">Aggiungi al carrello</a></p>
													</div>
												</div>
											</div>
										<?php endforeach; ?>
									</div>
								</div>

								<div class="tab-pane fade" id="v-pills-3" role="tabpanel" aria-labelledby="v-pills-3-tab">
									<div class="row">
										<?php foreach ($antipasti as $antipasto):
											$nomeAntipasto = $antipasto['nome']; // dal database
											$nomeFile = str_replace(' ', '_', $nomeAntipasto);
										?>
											<div class="col-md-4 text-center">
												<div class="menu-wrap">
													<?php echo '<a href="#" class="menu-img img mb-4" style="background-image: url(images/FotoAntipasti/' . $nomeFile . '.jpg);"></a>' ?>

													<div class="text">
														<h3> <?php echo $antipasto['nome'] ?> </h3>
														<p> <?php echo $antipasto['descrizione'] ?> </p>
														<p class="price"><span>€ <?php echo $antipasto['prezzo'] ?></span></p>
														<p><a href="ordina.php" class="btn btn-white btn-outline-white">Aggiungi al carrello</a></p>
													</div>
												</div>
											</div>
										<?php endforeach; ?>
									</div>
								</div>

								<div class="tab-pane fade" id="v-pills-4" role="tabpanel" aria-labelledby="v-pills-4-tab">
									<div class="row">
										<?php foreach ($secondi as $secondo):
											$nomeSecondo = $secondo['nome']; // dal database
											$nomeFile = str_replace(' ', '_', $nomeSecondo);

										?>
											<div class="col-md-4 text-center">
												<div class="menu-wrap">
													<?php echo '<a href="#" class="menu-img img mb-4" style="background-image: url(images/FotoSecondi/' . $nomeFile . '.jpg);"></a>'; ?>
													<div class="text">
														<h3> <?php echo $secondo['nome'] ?> </h3>
														<p> <?php echo $secondo['descrizione'] ?> </p>
														<p class="price"><span>€ <?php echo $secondo['prezzo'] ?></span></p>
														<p><a href="ordina.php" class="btn btn-white btn-outline-white">Aggiungi al carrello</a></p>
													</div>
												</div>
											</div>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center mb-5 pb-3">
				<div class="col-md-7 heading-section ftco-animate text-center">
					<h2 class="mb-4">Blog recenti</h2>
					<p>Rimani aggiornato con i nostri blog, o scopri qualcosa di nuovo sul mondo della pizza!</p>
				</div>
			</div>
			<div class="row d-flex">
				<?php foreach ($recentPosts as $post): ?>
					<div class="col-md-4 d-flex ftco-animate">
						<div class="blog-entry align-self-stretch">
							<a href="blog-single.php?id=<?= $post['id'] ?>" class="block-20" style="background-image: url('<?= htmlspecialchars($post['image']) ?>');">
							</a>
							<div class="text py-4 d-block">
								<div class="meta">
									<div><a href="#"><?= date('M j, Y', strtotime($post['created_at'])) ?></a></div>
									<div><a href="#"><?= htmlspecialchars($post['author']) ?></a></div>
									<div><a href="#" class="meta-chat"><span class="icon-chat"></span> <?= $post['comment_count'] ?></a></div>
								</div>
								<h3 class="heading mt-2"><a href="blog-single.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a></h3>
								<p><?= substr(strip_tags($post['content'] ?? ''), 0, 100) ?>...</p>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		</div>
		</div>
	</section>


	<section class="ftco-appointment">
		<div class="overlay"></div>
		<div class="container-wrap">
			<div class="row no-gutters d-md-flex align-items-center">
				<div class="col-md-6 d-flex align-self-stretch">
					<div id="map"></div>
				</div>
				<div class="col-md-6 appointment ftco-animate">
					<h3 class="mb-3">Contattaci </h3>
					<form action="#" class="appointment-form">
						<div class="d-md-flex">
							<div class="form-group">
								<input type="text" class="form-control" placeholder="First Name">
							</div>
						</div>
						<div class="d-me-flex">
							<div class="form-group">
								<input type="text" class="form-control" placeholder="Last Name">
							</div>
						</div>
						<div class="form-group">
							<textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Message"></textarea>
						</div>
						<div class="form-group">
							<input type="submit" value="Send" class="btn btn-primary py-3 px-4">
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

	<script>
		// Miglioramento per le card del menu
$(document).ready(function() {
    // Funzione per equalizzare l'altezza delle card
    function equalizeCardHeights() {
        $('.tab-pane.active .menu-wrap').each(function() {
            $(this).css('height', 'auto');
        });
        
        var maxHeight = 0;
        $('.tab-pane.active .menu-wrap').each(function() {
            var height = $(this).outerHeight();
            if (height > maxHeight) {
                maxHeight = height;
            }
        });
        
        $('.tab-pane.active .menu-wrap').css('height', maxHeight + 'px');
    }
    
    // Gestione del cambio di tab
    $('.nav-pills .nav-link').on('click', function(e) {
        var targetTab = $(this).attr('href');
        
        setTimeout(function() {
            // Equalizza le altezze dopo il cambio di tab
            equalizeCardHeights();
            
            // Assicura che le immagini abbiano le dimensioni corrette
            $(targetTab + ' .menu-img').each(function() {
                var $img = $(this);
                var bgImage = $img.css('background-image');
                
                if (bgImage && bgImage !== 'none') {
                    $img.addClass('loaded');
                }
            });
        }, 100);
    });
    
    // Equalizza le altezze al caricamento iniziale
    setTimeout(function() {
        equalizeCardHeights();
    }, 500);
    
    // Ricarica le altezze al resize della finestra
    $(window).on('resize', function() {
        clearTimeout(this.resizeTimeout);
        this.resizeTimeout = setTimeout(function() {
            equalizeCardHeights();
        }, 100);
    });
    
    // Gestione del caricamento delle immagini
    $('.menu-img').each(function() {
        var $img = $(this);
        var bgImage = $img.css('background-image');
        
        if (bgImage && bgImage !== 'none') {
            var imageUrl = bgImage.replace(/url\(['"]?/, '').replace(/['"]?\)$/, '');
            var img = new Image();
            
            img.onload = function() {
                $img.addClass('loaded');
                setTimeout(equalizeCardHeights, 50);
            };
            
            img.onerror = function() {
                $img.addClass('loaded');
                $img.css('background-image', 'url(images/placeholder.jpg)');
            };
            
            img.src = imageUrl;
        }
    });
});

// Funzione per ricaricare il layout quando necessario
function refreshMenuLayout() {
    setTimeout(function() {
        $('.tab-pane.active .menu-wrap').css('height', 'auto');
        
        var maxHeight = 0;
        $('.tab-pane.active .menu-wrap').each(function() {
            var height = $(this).outerHeight();
            if (height > maxHeight) {
                maxHeight = height;
            }
        });
        
        $('.tab-pane.active .menu-wrap').css('height', maxHeight + 'px');
    }, 100);
}
	</script>
</body>

</html>