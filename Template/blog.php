<?php 
session_start();
require_once 'config.php';

// Recenti per il footer
$recentStmt = $pdo->prepare("
    SELECT id, title, created_at, image, author
    FROM blog_posts
    ORDER BY created_at DESC
    LIMIT 2
");
$recentStmt->execute();
$recentPosts = $recentStmt->fetchAll(PDO::FETCH_ASSOC);

function generateExcerpt($text, $maxLength = 100) {
  $text = strip_tags($text);
  if (strlen($text) <= $maxLength) return $text;
  $cut = substr($text, 0, $maxLength);
  $cut = substr($cut, 0, strrpos($cut, ' '));
  return $cut . '...';
}

// 1) Recupera tutte le categorie per la sidebar
$categoriesStmt = $pdo->query("SELECT id,name FROM blog_categories ORDER BY name");
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);

// 2) Legge il filtro categoria (se esiste e numerico)
$categoryId = isset($_GET['category']) && is_numeric($_GET['category'])
  ? (int)$_GET['category'] 
  : null;

// 3) Costruisce la parte WHERE e i parametri per le query
$where = '';
$params = [];
if ($categoryId) {
  $where = 'WHERE category_id = :category';
  $params['category'] = $categoryId;
}

// 4) Conteggio totale per il filtro (per paginazione)
$countSql = "SELECT COUNT(*) FROM blog_posts $where";
$countStmt = $pdo->prepare($countSql);
$countStmt->execute($params);
$totalPosts = $countStmt->fetchColumn();
// numero totale di tutti i post (per la voce “All”)
$allCountStmt = $pdo->query("SELECT COUNT(*) FROM blog_posts");
$allCount = $allCountStmt->fetchColumn();


// 5) Paginazione
$limit = 6;
$page  = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;
$totalPages = ceil($totalPosts / $limit);

// Blocchi di 6 pagine
$pageBlock = ceil($page / 6);
$startPage = ($pageBlock - 1) * 6 + 1;
$endPage   = min($startPage + 5, $totalPages);

// 6) Query dei post paginati + filtro
$sql = "SELECT * FROM blog_posts
        $where
        ORDER BY created_at DESC
        LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
// bind filtro
if ($categoryId) {
  $stmt->bindValue(':category', $categoryId, PDO::PARAM_INT);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">

<head>
	<title>Blog</title>
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
  <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
      <a class="navbar-brand" href="index.php"><span class="flaticon-pizza-1 mr-1"></span>L.M.<br><small>Pizzeria</small></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="oi oi-menu"></span> Menu
      </button>
      <div class="collapse navbar-collapse" id="ftco-nav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
          <li class="nav-item"><a href="menu.php" class="nav-link">Menu</a></li>
          <li class="nav-item"><a href="services.php" class="nav-link">Services</a></li>
          <li class="nav-item active"><a href="blog.php" class="nav-link">Blog</a></li>
          <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
          <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
          <li class="nav-item d-flex align-items-center">
            <?php if (isset($_SESSION['user'])): ?>
              <a href="profilo.php" class="btn btn-primary mr-2">
                <?= htmlspecialchars($_SESSION['user']['nickname']) ?>
              </a>
              <a href="logout.php" class="btn btn-outline-light">Logout</a>
            <?php else: ?>
              <a href="login.php" class="btn btn-primary">Login</a>
            <?php endif; ?>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- END nav -->

  <section class="home-slider owl-carousel img" style="background-image: url(images/bg_1.jpg);">

    <div class="slider-item" style="background-image: url(images/bg_3.jpg);">
      <div class="overlay"></div>
      <div class="container">
        <div class="row slider-text justify-content-center align-items-center">

          <div class="col-md-7 col-sm-12 text-center ftco-animate">
            <h1 class="mb-3 mt-5 bread">Read our Blog</h1>
            <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home</a></span> <span>Blog</span></p>
          </div>
        </div>
      </div>
    </div>
  </section>

<section class="ftco-section">
  <div class="container">
    <!-- Titolo sezione -->
    <div class="row justify-content-center mb-5 pb-3">
      <div class="col-md-7 heading-section ftco-animate text-center">
        <h2 class="mb-4">Blog posts</h2>
        <p>Remain updated with all our latest news, and learn something new about Italian Cooking.</p>
      </div>
    </div>
    <!-- Logica per limitare blog in base alla categoria -->
    <div class="sidebar-box ftco-animate">
  <h3>Categories</h3>
  <ul class="categories">
    <li><a href="blog.php">All <span>(<?= $allCount?>)</span></a></li>
    <?php foreach ($categories as $cat): ?>
      <li>
        <a href="blog.php?category=<?= $cat['id'] ?>">
          <?= htmlspecialchars($cat['name']) ?>
          <span>(
            <?php
              $countStmt = $pdo->prepare("SELECT COUNT(*) FROM blog_posts WHERE category_id = ?");
              $countStmt->execute([$cat['id']]);
              echo $countStmt->fetchColumn();
            ?>
          )</span>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</div>


    <!-- Stampa di tutti i blog post-->
    <div class="row d-flex">
      <?php foreach ($posts as $post): ?>
        <div class="col-md-4 d-flex ftco-animate">
          <div class="blog-entry align-self-stretch">
            <a href="blog-single.php?id=<?= $post['id'] ?>" class="block-20" style="background-image: url('<?= htmlspecialchars($post['image']) ?>');">
            </a>
            <div class="text py-4 d-block">
              <div class="meta">
                <div><a href="#"><?= date('M d, Y', strtotime($post['created_at'])) ?></a></div>
                <div><a href="#"><?= htmlspecialchars($post['author']) ?></a></div>
                <div><a href="#" class="meta-chat"><span class="icon-chat"></span> 0</a></div>
              </div>
              <h3 class="heading mt-2">
                <a href="blog-single.php?id=<?= $post['id'] ?>">
                  <?= htmlspecialchars($post['title']) ?>
                </a>
              </h3>
              <p><?= htmlspecialchars($post['subtitle'] ?? generateExcerpt($post['content'])) ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <div class="row mt-5">
      <div class="col text-center">
        <div class="block-27">
          <ul>
            <?php if ($startPage > 1): ?>
              <li><a href="?page=<?= $startPage - 1 ?>">&lt;</a></li>
            <?php endif; ?>

            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
              <?php if ($i == $page): ?>
                <li class="active"><span><?= $i ?></span></li>
              <?php else: ?>
                <li><a href="?page=<?= $i ?>"><?= $i ?></a></li>
              <?php endif; ?>
            <?php endfor; ?>

            <?php if ($endPage < $totalPages): ?>
              <li><a href="?page=<?= $endPage + 1 ?>">&gt;</a></li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>


  <footer class="ftco-footer ftco-section img">
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
    <?php foreach($recentPosts as $r): ?>
      <div class="block-21 mb-4 d-flex">
        <a 
          class="blog-img mr-4" 
          style="background-image: url('<?= htmlspecialchars($r['image']) ?>');"
          href="blog-single.php?id=<?= $r['id'] ?>"
        ></a>
        <div class="text">
          <h3 class="heading">
            <a href="blog-single.php?id=<?= $r['id'] ?>">
              <?= htmlspecialchars($r['title']) ?>
            </a>
          </h3>
          <div class="meta">
            <div>
              <a href="#">
                <span class="icon-calendar"></span>
                <?= date('M d, Y', strtotime($r['created_at'])) ?>
              </a>
            </div>
            <div>
              <a href="#"><span class="icon-person"></span><?= htmlspecialchars($r['author']) ?></a>
            </div>
            <div>
              <a href="#" class="meta-chat"><span class="icon-chat"></span>0</a>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
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