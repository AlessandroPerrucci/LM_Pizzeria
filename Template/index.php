<?php session_start(); ?>


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
						<h1 class="mb-4">Italian Cuizine</h1>
						<p class="mb-4 mb-md-5">Delicious pizza hand made with localy purchased products, Following Real Italian recipes.</p>
						<p><a href="#" class="btn btn-primary p-3 px-xl-4 py-xl-3">Order Now</a> <a href="#" class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">View Menu</a></p>
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
						<span class="subheading">Crunchy</span>
						<h1 class="mb-4">Italian Pizza</h1>
						<p class="mb-4 mb-md-5">Delicious pizza hand made with localy purchased products, Following Real Italian recipes.</p>
						<p><a href="#" class="btn btn-primary p-3 px-xl-4 py-xl-3">Order Now</a> <a href="#" class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">View Menu</a></p>
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
						<span class="subheading">Welcome</span>
						<h1 class="mb-4">We make authentic Italian pizza, kept to the highest standards.</h1>
						<p class="mb-4 mb-md-5">Delicious pizza hand made with localy purchased products, Following Real Italian recipes.</p>
						<p><a href="#" class="btn btn-primary p-3 px-xl-4 py-xl-3">Order Now</a> <a href="#" class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">View Menu</a></p>
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
								<h3>000 (123) 456 7890</h3>
								<p>Call to book a table</p>
							</div>
						</div>
						<div class="col-md-4 d-flex ftco-animate">
							<div class="icon"><span class="icon-my_location"></span></div>
							<div class="text">
								<h3>198 West 21th Street</h3>
								<p>Suite 721 New York NY 10016</p>
							</div>
						</div>
						<div class="col-md-4 d-flex ftco-animate">
							<div class="icon"><span class="icon-clock-o"></span></div>
							<div class="text">
								<h3>Open Monday-Friday</h3>
								<p>8:00am - 9:00pm</p>
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
				<h2 class="mb-4">Welcome to <span class="flaticon-pizza">L.M Pizzeria</span> A Restaurant</h2>
			</div>
			<div>
				<p>At our pizzeria, every slice tells a story — crafted with locally sourced ingredients, real Italian recipes, and a passion for flavor. We follow high standards to deliver delicious pizza that's fresh, authentic, and unforgettable. Taste Italy, one bite at a time.</p>
			</div>
		</div>
	</section>

	<section class="ftco-section ftco-services">
		<div class="overlay"></div>
		<div class="container">
			<div class="row justify-content-center mb-5 pb-3">
				<div class="col-md-7 heading-section ftco-animate text-center">
					<h2 class="mb-4">Our Services</h2>
					<p>Want to know what services we offer, or the quality and origin of our products?</p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4 ftco-animate">
					<div class="media d-block text-center block-6 services">
						<div class="icon d-flex justify-content-center align-items-center mb-5">
							<span class="flaticon-diet"></span>
						</div>
						<div class="media-body">
							<h3 class="heading">Healthy Foods</h3>
							<p>We use only the finest ingredients, carefully sourced from local producers to craft our authentic pizzas.</p>
						</div>
					</div>
				</div>
				<div class="col-md-4 ftco-animate">
					<div class="media d-block text-center block-6 services">
						<div class="icon d-flex justify-content-center align-items-center mb-5">
							<span class="flaticon-bicycle"></span>
						</div>
						<div class="media-body">
							<h3 class="heading">Fastest Delivery</h3>
							<p>Our dedicated drivers ensure swift delivery, bringing your pizza to you at the perfect temperature: hot, fresh, and ready to enjoy.</p>
						</div>
					</div>
				</div>
				<div class="col-md-4 ftco-animate">
					<div class="media d-block text-center block-6 services">
						<div class="icon d-flex justify-content-center align-items-center mb-5"><span class="flaticon-pizza-1"></span></div>
						<div class="media-body">
							<h3 class="heading">Original Recipes</h3>
							<p>Every pizza is crafted using authentic Italian recipes, learned directly from their original masters</p>
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
					<h2 class="mb-4">Signature whole pizza</h2>
					<p>Our signature pizzas are specially crafted by blending authentic techniques learned in Italy with our own creative touch.</p>
				</div>
			</div>
		</div>
		<div class="container-wrap">
			<div class="row no-gutters d-flex">
				<div class="col-lg-4 d-flex ftco-animate">
					<div class="services-wrap d-flex">
						<a href="#" class="img" style="background-image: url(images/pizza-1.jpg);"></a>
						<div class="text p-4">
							<h3>Italian Pizza</h3>
							<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia </p>
							<p class="price"><span>$2.90</span> <a href="#" class="ml-2 btn btn-white btn-outline-white">Order</a></p>
						</div>
					</div>
				</div>
				<div class="col-lg-4 d-flex ftco-animate">
					<div class="services-wrap d-flex">
						<a href="#" class="img" style="background-image: url(images/pizza-2.jpg);"></a>
						<div class="text p-4">
							<h3>Greek Pizza</h3>
							<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia</p>
							<p class="price"><span>$2.90</span> <a href="#" class="ml-2 btn btn-white btn-outline-white">Order</a></p>
						</div>
					</div>
				</div>
				<div class="col-lg-4 d-flex ftco-animate">
					<div class="services-wrap d-flex">
						<a href="#" class="img" style="background-image: url(images/pizza-3.jpg);"></a>
						<div class="text p-4">
							<h3>Caucasian Pizza</h3>
							<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia</p>
							<p class="price"><span>$2.90</span> <a href="#" class="ml-2 btn btn-white btn-outline-white">Order</a></p>
						</div>
					</div>
				</div>

				<div class="col-lg-4 d-flex ftco-animate">
					<div class="services-wrap d-flex">
						<a href="#" class="img order-lg-last" style="background-image: url(images/pizza-4.jpg);"></a>
						<div class="text p-4">
							<h3>American Pizza</h3>
							<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia </p>
							<p class="price"><span>$2.90</span> <a href="#" class="ml-2 btn btn-white btn-outline-white">Order</a></p>
						</div>
					</div>
				</div>
				<div class="col-lg-4 d-flex ftco-animate">
					<div class="services-wrap d-flex">
						<a href="#" class="img order-lg-last" style="background-image: url(images/pizza-5.jpg);"></a>
						<div class="text p-4">
							<h3>Tomatoe Pie</h3>
							<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia</p>
							<p class="price"><span>$2.90</span> <a href="#" class="ml-2 btn btn-white btn-outline-white">Order</a></p>
						</div>
					</div>
				</div>
				<div class="col-lg-4 d-flex ftco-animate">
					<div class="services-wrap d-flex">
						<a href="#" class="img order-lg-last" style="background-image: url(images/pizza-6.jpg);"></a>
						<div class="text p-4">
							<h3>Margherita</h3>
							<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia</p>
							<p class="price"><span>$2.90</span> <a href="#" class="ml-2 btn btn-white btn-outline-white">Order</a></p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="container">
			<div class="row justify-content-center mb-5 pb-3 mt-5 pt-5">
				<div class="col-md-7 heading-section text-center ftco-animate">
					<h2 class="mb-4">Our Menu Pricing</h2>
					<p class="flip"><span class="deg1"></span><span class="deg2"></span><span class="deg3"></span></p>
					<p class="mt-5">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="pricing-entry d-flex ftco-animate">
						<div class="img" style="background-image: url(images/pizza-1.jpg);"></div>
						<div class="desc pl-3">
							<div class="d-flex text align-items-center">
								<h3><span>Italian Pizza</span></h3>
								<span class="price">$20.00</span>
							</div>
							<div class="d-block">
								<p>A small river named Duden flows by their place and supplies</p>
							</div>
						</div>
					</div>
					<div class="pricing-entry d-flex ftco-animate">
						<div class="img" style="background-image: url(images/pizza-2.jpg);"></div>
						<div class="desc pl-3">
							<div class="d-flex text align-items-center">
								<h3><span>Hawaiian Pizza</span></h3>
								<span class="price">$29.00</span>
							</div>
							<div class="d-block">
								<p>A small river named Duden flows by their place and supplies</p>
							</div>
						</div>
					</div>
					<div class="pricing-entry d-flex ftco-animate">
						<div class="img" style="background-image: url(images/pizza-3.jpg);"></div>
						<div class="desc pl-3">
							<div class="d-flex text align-items-center">
								<h3><span>Greek Pizza</span></h3>
								<span class="price">$20.00</span>
							</div>
							<div class="d-block">
								<p>A small river named Duden flows by their place and supplies</p>
							</div>
						</div>
					</div>
					<div class="pricing-entry d-flex ftco-animate">
						<div class="img" style="background-image: url(images/pizza-4.jpg);"></div>
						<div class="desc pl-3">
							<div class="d-flex text align-items-center">
								<h3><span>Bacon Crispy Thins</span></h3>
								<span class="price">$20.00</span>
							</div>
							<div class="d-block">
								<p>A small river named Duden flows by their place and supplies</p>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="pricing-entry d-flex ftco-animate">
						<div class="img" style="background-image: url(images/pizza-5.jpg);"></div>
						<div class="desc pl-3">
							<div class="d-flex text align-items-center">
								<h3><span>Hawaiian Special</span></h3>
								<span class="price">$49.91</span>
							</div>
							<div class="d-block">
								<p>A small river named Duden flows by their place and supplies</p>
							</div>
						</div>
					</div>
					<div class="pricing-entry d-flex ftco-animate">
						<div class="img" style="background-image: url(images/pizza-6.jpg);"></div>
						<div class="desc pl-3">
							<div class="d-flex text align-items-center">
								<h3><span>Ultimate Overload</span></h3>
								<span class="price">$20.00</span>
							</div>
							<div class="d-block">
								<p>A small river named Duden flows by their place and supplies</p>
							</div>
						</div>
					</div>
					<div class="pricing-entry d-flex ftco-animate">
						<div class="img" style="background-image: url(images/pizza-7.jpg);"></div>
						<div class="desc pl-3">
							<div class="d-flex text align-items-center">
								<h3><span>Bacon Pizza</span></h3>
								<span class="price">$20.00</span>
							</div>
							<div class="d-block">
								<p>A small river named Duden flows by their place and supplies</p>
							</div>
						</div>
					</div>
					<div class="pricing-entry d-flex ftco-animate">
						<div class="img" style="background-image: url(images/pizza-8.jpg);"></div>
						<div class="desc pl-3">
							<div class="d-flex text align-items-center">
								<h3><span>Ham &amp; Pineapple</span></h3>
								<span class="price">$20.00</span>
							</div>
							<div class="d-block">
								<p>A small river named Duden flows by their place and supplies</p>
							</div>
						</div>
					</div>
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
									<span>Pizza Branches</span>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
							<div class="block-18 text-center">
								<div class="text">
									<div class="icon"><span class="flaticon-medal"></span></div>
									<strong class="number" data-number="85">0</strong>
									<span>Number of Awards</span>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
							<div class="block-18 text-center">
								<div class="text">
									<div class="icon"><span class="flaticon-laugh"></span></div>
									<strong class="number" data-number="10567">0</strong>
									<span>Happy Customer</span>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
							<div class="block-18 text-center">
								<div class="text">
									<div class="icon"><span class="flaticon-chef"></span></div>
									<strong class="number" data-number="900">0</strong>
									<span>Staff</span>
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
								<a class="nav-link active" id="v-pills-1-tab" data-toggle="pill" href="#v-pills-1" role="tab" aria-controls="v-pills-1" aria-selected="true">Pizza</a>

								<a class="nav-link" id="v-pills-2-tab" data-toggle="pill" href="#v-pills-2" role="tab" aria-controls="v-pills-2" aria-selected="false">Drinks</a>

								<a class="nav-link" id="v-pills-3-tab" data-toggle="pill" href="#v-pills-3" role="tab" aria-controls="v-pills-3" aria-selected="false">Burgers</a>

								<a class="nav-link" id="v-pills-4-tab" data-toggle="pill" href="#v-pills-4" role="tab" aria-controls="v-pills-4" aria-selected="false">Pasta</a>
							</div>
						</div>
						<div class="col-md-12 d-flex align-items-center">

							<div class="tab-content ftco-animate" id="v-pills-tabContent">

								<div class="tab-pane fade show active" id="v-pills-1" role="tabpanel" aria-labelledby="v-pills-1-tab">
									<div class="row">
										<div class="col-md-4 text-center">
											<div class="menu-wrap">
												<a href="#" class="menu-img img mb-4" style="background-image: url(images/pizza-1.jpg);"></a>
												<div class="text">
													<h3><a href="#">Itallian Pizza</a></h3>
													<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia.</p>
													<p class="price"><span>$2.90</span></p>
													<p><a href="#" class="btn btn-white btn-outline-white">Add to cart</a></p>
												</div>
											</div>
										</div>
										<div class="col-md-4 text-center">
											<div class="menu-wrap">
												<a href="#" class="menu-img img mb-4" style="background-image: url(images/pizza-2.jpg);"></a>
												<div class="text">
													<h3><a href="#">Itallian Pizza</a></h3>
													<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia.</p>
													<p class="price"><span>$2.90</span></p>
													<p><a href="#" class="btn btn-white btn-outline-white">Add to cart</a></p>
												</div>
											</div>
										</div>
										<div class="col-md-4 text-center">
											<div class="menu-wrap">
												<a href="#" class="menu-img img mb-4" style="background-image: url(images/pizza-3.jpg);"></a>
												<div class="text">
													<h3><a href="#">Itallian Pizza</a></h3>
													<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia.</p>
													<p class="price"><span>$2.90</span></p>
													<p><a href="#" class="btn btn-white btn-outline-white">Add to cart</a></p>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="tab-pane fade" id="v-pills-2" role="tabpanel" aria-labelledby="v-pills-2-tab">
									<div class="row">
										<div class="col-md-4 text-center">
											<div class="menu-wrap">
												<a href="#" class="menu-img img mb-4" style="background-image: url(images/drink-1.jpg);"></a>
												<div class="text">
													<h3><a href="#">Lemonade Juice</a></h3>
													<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia.</p>
													<p class="price"><span>$2.90</span></p>
													<p><a href="#" class="btn btn-white btn-outline-white">Add to cart</a></p>
												</div>
											</div>
										</div>
										<div class="col-md-4 text-center">
											<div class="menu-wrap">
												<a href="#" class="menu-img img mb-4" style="background-image: url(images/drink-2.jpg);"></a>
												<div class="text">
													<h3><a href="#">Pineapple Juice</a></h3>
													<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia.</p>
													<p class="price"><span>$2.90</span></p>
													<p><a href="#" class="btn btn-white btn-outline-white">Add to cart</a></p>
												</div>
											</div>
										</div>
										<div class="col-md-4 text-center">
											<div class="menu-wrap">
												<a href="#" class="menu-img img mb-4" style="background-image: url(images/drink-3.jpg);"></a>
												<div class="text">
													<h3><a href="#">Soda Drinks</a></h3>
													<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia.</p>
													<p class="price"><span>$2.90</span></p>
													<p><a href="#" class="btn btn-white btn-outline-white">Add to cart</a></p>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="tab-pane fade" id="v-pills-3" role="tabpanel" aria-labelledby="v-pills-3-tab">
									<div class="row">
										<div class="col-md-4 text-center">
											<div class="menu-wrap">
												<a href="#" class="menu-img img mb-4" style="background-image: url(images/burger-1.jpg);"></a>
												<div class="text">
													<h3><a href="#">Itallian Pizza</a></h3>
													<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia.</p>
													<p class="price"><span>$2.90</span></p>
													<p><a href="#" class="btn btn-white btn-outline-white">Add to cart</a></p>
												</div>
											</div>
										</div>
										<div class="col-md-4 text-center">
											<div class="menu-wrap">
												<a href="#" class="menu-img img mb-4" style="background-image: url(images/burger-2.jpg);"></a>
												<div class="text">
													<h3><a href="#">Itallian Pizza</a></h3>
													<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia.</p>
													<p class="price"><span>$2.90</span></p>
													<p><a href="#" class="btn btn-white btn-outline-white">Add to cart</a></p>
												</div>
											</div>
										</div>
										<div class="col-md-4 text-center">
											<div class="menu-wrap">
												<a href="#" class="menu-img img mb-4" style="background-image: url(images/burger-3.jpg);"></a>
												<div class="text">
													<h3><a href="#">Itallian Pizza</a></h3>
													<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia.</p>
													<p class="price"><span>$2.90</span></p>
													<p><a href="#" class="btn btn-white btn-outline-white">Add to cart</a></p>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="tab-pane fade" id="v-pills-4" role="tabpanel" aria-labelledby="v-pills-4-tab">
									<div class="row">
										<div class="col-md-4 text-center">
											<div class="menu-wrap">
												<a href="#" class="menu-img img mb-4" style="background-image: url(images/pasta-1.jpg);"></a>
												<div class="text">
													<h3><a href="#">Itallian Pizza</a></h3>
													<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia.</p>
													<p class="price"><span>$2.90</span></p>
													<p><a href="#" class="btn btn-white btn-outline-white">Add to cart</a></p>
												</div>
											</div>
										</div>
										<div class="col-md-4 text-center">
											<div class="menu-wrap">
												<a href="#" class="menu-img img mb-4" style="background-image: url(images/pasta-2.jpg);"></a>
												<div class="text">
													<h3><a href="#">Itallian Pizza</a></h3>
													<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia.</p>
													<p class="price"><span>$2.90</span></p>
													<p><a href="#" class="btn btn-white btn-outline-white">Add to cart</a></p>
												</div>
											</div>
										</div>
										<div class="col-md-4 text-center">
											<div class="menu-wrap">
												<a href="#" class="menu-img img mb-4" style="background-image: url(images/pasta-3.jpg);"></a>
												<div class="text">
													<h3><a href="#">Itallian Pizza</a></h3>
													<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia.</p>
													<p class="price"><span>$2.90</span></p>
													<p><a href="#" class="btn btn-white btn-outline-white">Add to cart</a></p>
												</div>
											</div>
										</div>
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
					<h2 class="mb-4">Recent from blog</h2>
					<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
				</div>
			</div>
			<div class="row d-flex">
				<div class="col-md-4 d-flex ftco-animate">
					<div class="blog-entry align-self-stretch">
						<a href="blog-single.php" class="block-20" style="background-image: url('images/image_1.jpg');">
						</a>
						<div class="text py-4 d-block">
							<div class="meta">
								<div><a href="#">Sept 10, 2018</a></div>
								<div><a href="#">Admin</a></div>
								<div><a href="#" class="meta-chat"><span class="icon-chat"></span> 3</a></div>
							</div>
							<h3 class="heading mt-2"><a href="#">The Delicious Pizza</a></h3>
							<p>A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
						</div>
					</div>
				</div>
				<div class="col-md-4 d-flex ftco-animate">
					<div class="blog-entry align-self-stretch">
						<a href="blog-single.php" class="block-20" style="background-image: url('images/image_2.jpg');">
						</a>
						<div class="text py-4 d-block">
							<div class="meta">
								<div><a href="#">Sept 10, 2018</a></div>
								<div><a href="#">Admin</a></div>
								<div><a href="#" class="meta-chat"><span class="icon-chat"></span> 3</a></div>
							</div>
							<h3 class="heading mt-2"><a href="#">The Delicious Pizza</a></h3>
							<p>A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
						</div>
					</div>
				</div>
				<div class="col-md-4 d-flex ftco-animate">
					<div class="blog-entry align-self-stretch">
						<a href="blog-single.php" class="block-20" style="background-image: url('images/image_3.jpg');">
						</a>
						<div class="text py-4 d-block">
							<div class="meta">
								<div><a href="#">Sept 10, 2018</a></div>
								<div><a href="#">Admin</a></div>
								<div><a href="#" class="meta-chat"><span class="icon-chat"></span> 3</a></div>
							</div>
							<h3 class="heading mt-2"><a href="#">The Delicious Pizza</a></h3>
							<p>A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
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
				<div class="col-md-6 d-flex align-self-stretch">
					<div id="map"></div>
				</div>
				<div class="col-md-6 appointment ftco-animate">
					<h3 class="mb-3">Contact Us</h3>
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

</body>

</html>