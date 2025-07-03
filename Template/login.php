<?php
session_start();
require_once 'config.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = trim($_POST["email"] ?? '');
  $password = trim($_POST["password"] ?? '');

  if ($email === '' || $password === '') {
    $errors[] = "Email e password sono obbligatorie.";
  } else {
    $stmt = $pdo->prepare("SELECT * FROM utente WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
      $_SESSION['user'] = $user;
      header("Location: index.php");
      exit;
    } else {
      $errors[] = "Credenziali non valide.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
  <title>Login</title>
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
  <link rel="stylesheet" href="css/stile_personalizzato.css">
</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
      <a class="navbar-brand" href="index.php"><span class="flaticon-pizza-1 mr-1"></span>L.M.<br><small>Pizzeria</small></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="oi oi-menu"></span> Menu
      </button>
      <div class="collapse navbar-collapse" id="ftco-nav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item active"><a href="index.php" class="nav-link">Home</a></li>
          <li class="nav-item"><a href="menu.php" class="nav-link">Menu</a></li>
          <li class="nav-item"><a href="services.php" class="nav-link">Services</a></li>
          <li class="nav-item"><a href="blog.php" class="nav-link">Blog</a></li>
          <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
          <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
          <li class="nav-item d-flex align-items-center">
            <?php if (isset($_SESSION['user'])): ?>
              <a href="profilo.php" class="btn btn-primary mr-2"><?= htmlspecialchars($_SESSION['user']['nickname']) ?></a>
              <a href="logout.php" class="btn btn-outline-light">Logout</a>
            <?php else: ?>
              <a href="login.php" class="btn btn-primary">Login</a>
            <?php endif; ?>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-5">
    <div class="form-profilo">
      <h2 class="text-center mb-4" style="color: #212529;">Accedi al tuo profilo</h2>

      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
          <ul class="mb-0">
            <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="post">
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" name="email" id="email" class="form-control"
            value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required
            style="color: #212529 !important; background-color: #fff; border:1px solid !important;">
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" name="password" id="password" class="form-control" required
            style="color: #212529 !important; background-color: #fff; border:1px solid !important;">
        </div>

        <button type="submit" class="btn-custom-warning" style="cursor: pointer !important;">Accedi</button>
      </form>

      <div class="text-center mt-3">
        <a href="register.php" class="btn btn-outline-secondary">Non hai un account? Registrati</a>
      </div>
    </div>
  </div>

  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>

  <footer class="ftco-footer ftco-section img" style="margin-top: 8% !important;">
    <div class="overlay"></div>
    <div class="container">
      <div class="row mb-5">
        <div class="col-lg-3 col-md-6 mb-5 mb-md-5">
          <div class="ftco-footer-widget mb-4">
            <h2 class="ftco-heading-2">About Us</h2>
            <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
            <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
              <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
              <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
              <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
            </ul>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-5 mb-md-5">
          <div class="ftco-footer-widget mb-4">
            <h2 class="ftco-heading-2">Recent Blog</h2>
            <div class="block-21 mb-4 d-flex">
              <a class="blog-img mr-4" style="background-image: url(images/image_1.jpg);"></a>
              <div class="text">
                <h3 class="heading"><a href="#">Even the all-powerful Pointing has no control about</a></h3>
                <div class="meta">
                  <div><a href="#"><span class="icon-calendar"></span> Sept 15, 2018</a></div>
                  <div><a href="#"><span class="icon-person"></span> Admin</a></div>
                  <div><a href="#"><span class="icon-chat"></span> 19</a></div>
                </div>
              </div>
            </div>
            <div class="block-21 mb-4 d-flex">
              <a class="blog-img mr-4" style="background-image: url(images/image_2.jpg);"></a>
              <div class="text">
                <h3 class="heading"><a href="#">Even the all-powerful Pointing has no control about</a></h3>
                <div class="meta">
                  <div><a href="#"><span class="icon-calendar"></span> Sept 15, 2018</a></div>
                  <div><a href="#"><span class="icon-person"></span> Admin</a></div>
                  <div><a href="#"><span class="icon-chat"></span> 19</a></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-2 col-md-6 mb-5 mb-md-5">
          <div class="ftco-footer-widget mb-4 ml-md-4">
            <h2 class="ftco-heading-2">Services</h2>
            <ul class="list-unstyled">
              <li><a href="#" class="py-2 d-block">Cooked</a></li>
              <li><a href="#" class="py-2 d-block">Deliver</a></li>
              <li><a href="#" class="py-2 d-block">Quality Foods</a></li>
              <li><a href="#" class="py-2 d-block">Mixed</a></li>
            </ul>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-5 mb-md-5">
          <div class="ftco-footer-widget mb-4">
            <h2 class="ftco-heading-2">Have a Questions?</h2>
            <div class="block-23 mb-3">
              <ul>
                <li><span class="icon icon-map-marker"></span><span class="text">203 Fake St. Mountain View, San Francisco, California, USA</span></li>
                <li><a href="#"><span class="icon icon-phone"></span><span class="text">+2 392 3929 210</span></a></li>
                <li><a href="#"><span class="icon icon-envelope"></span><span class="text">info@yourdomain.com</span></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 text-center">

          <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
            Copyright &copy;<script>
              document.write(new Date().getFullYear());
            </script> All rights reserved | This template is made with <i class="icon-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
        </div>
      </div>
    </div>
  </footer>
</body>

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

</html>