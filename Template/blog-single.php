<?php
session_start();
require_once 'config.php';
#------[Codice commenti]-------
$post_id = $_GET['id'] ?? null;

if (!$post_id) {
  die("ID post mancante.");
}

// Recupera commenti per questo post
$stmt = $pdo->prepare("
  SELECT c.content, c.created_at, u.nickname, u.foto_profilo 
  FROM blog_comments c 
  JOIN utente u ON c.user_email = u.email 
  WHERE c.post_id = :post_id 
  ORDER BY c.created_at DESC
");
$stmt->execute(['post_id' => $post_id]);
$commenti = $stmt->fetchAll(PDO::FETCH_ASSOC);


#------[robba]---------

// --- Verifica ID valido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header('Location: blog.php');
  exit;
}
$postId = (int) $_GET['id'];

// --- Recupera il post
$stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = :id");
$stmt->execute(['id' => $postId]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$post) {
  header('Location: blog.php');
  exit;
}

// --- Recupera nome categoria
$categoryName = null;
if (!empty($post['category_id'])) {
  $catStmt = $pdo->prepare("SELECT name FROM blog_categories WHERE id = ?");
  $catStmt->execute([$post['category_id']]);
  $categoryName = $catStmt->fetchColumn();
}

// --- Funzione di escape
function h($text) {
  return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

// --- Sidebar: tutte le categorie + conteggi
$categories = $pdo->query("SELECT id, name FROM blog_categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM blog_posts WHERE category_id = ?");
$counts = [];
foreach ($categories as $cat) {
  $countStmt->execute([$cat['id']]);
  $counts[$cat['id']] = $countStmt->fetchColumn();
}

// --- Recent posts (ultimi 3)
$recentPosts = $pdo
  ->query("SELECT id, title, image, author, created_at FROM blog_posts ORDER BY created_at DESC LIMIT 3")
  ->fetchAll(PDO::FETCH_ASSOC);

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
  if (!isset($_SESSION['user'])) {
    die("Accesso negato.");
  }

  $comment = trim($_POST['comment']);
  $email = $_SESSION['user']['email'];

  if ($comment !== '') {
    $stmt = $pdo->prepare("
      INSERT INTO blog_comments (post_id, user_email, content)
      VALUES (:post_id, :user_email, :content)
    ");
    $stmt->execute([
      'post_id' => $post_id,
      'user_email' => $email,
      'content' => $comment
    ]);
    
    // 🔄 Reload per evitare reinvio su refresh
    header("Location: blog-single.php?id=$post_id");
    exit;
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?= h($post['title']) ?> - Blog</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- CSS e font -->
  <link rel="icon" href="./icons/pizza.ico">
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
  <!-- NAVBAR -->
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

  <!-- HERO -->
  <section class="home-slider owl-carousel img" style="background-image: url(images/bg_1.jpg);">
    <div class="slider-item" style="background-image: url(images/bg_3.jpg);">
      <div class="overlay"></div>
      <div class="container">
        <div class="row slider-text justify-content-center align-items-center">
          <div class="col-md-7 text-center ftco-animate">
            <h1 class="mb-3 mt-5 bread">Read our Blog</h1>
            <p class="breadcrumbs">
              <span class="mr-2"><a href="index.php">Home</a></span>
              <span class="mr-2"><a href="blog.php">Blog</a></span>
              <span>Blog Single</span>
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- SINGLE POST + SIDEBAR -->
  <section class="ftco-section ftco-degree-bg">
    <div class="container">
      <div class="row">
        <!-- Post -->
        <div class="col-md-8 ftco-animate">
          <h2 class="mb-3"><?= h($post['title']) ?></h2>
          <?php if (!empty($post['image'])): ?>
            <p><img src="<?= h($post['image']) ?>" alt="" class="img-fluid"></p>
          <?php endif; ?>
          <p><?= nl2br(h($post['content'])) ?></p>

          <!-- Tag/Categoria -->
          <div class="tag-widget post-tag-container mb-5 mt-5">
            <div class="tagcloud">
              <?php if ($categoryName): ?>
                <a href="blog.php?category=<?= $post['category_id'] ?>" class="tag-cloud-link">
                  <?= h($categoryName) ?>
                </a>
              <?php endif; ?>
            </div>
          </div>

          <!-- Commenti -->
          <h4>💬 Commenti</h4>
<?php if (empty($commenti)): ?>
  <p>Nessun commento ancora.</p>
<?php else: ?>
  <?php foreach ($commenti as $c): ?>
    <div class="comment-box mb-3 p-3 border rounded bg-white">
      <div class="d-flex align-items-center mb-2">
        <img src="<?= htmlspecialchars($c['foto_profilo']) ?>" class="rounded-circle mr-2" width="40" height="40">
        <strong><?= htmlspecialchars($c['nickname']) ?></strong>
        <small class="text-muted ml-2"><?= htmlspecialchars($c['created_at']) ?></small>
      </div>
      <p><?= nl2br(htmlspecialchars($c['content'])) ?></p>
    </div>
  <?php endforeach; ?>
<?php endif; ?>
<!-- Form per scrivere commento -->
 <?php if (isset($_SESSION['user'])): ?>
  <form method="post" class="mt-4">
    <div class="form-group">
      <label for="comment">Lascia un commento:</label>
      <textarea name="comment" id="comment" class="form-control" rows="3" required></textarea>
    </div>
    <button type="submit" name="submit_comment" class="btn btn-warning">Invia commento</button>
  </form>
<?php else: ?>
  <p><a href="login.php">Accedi</a> per lasciare un commento.</p>
<?php endif; ?>


        </div>

        <!-- Sidebar -->
        <div class="col-md-4 sidebar ftco-animate">
          <!-- Search -->
          <div class="sidebar-box">
            <form action="#" class="search-form">
              <div class="form-group">
                <div class="icon"><span class="icon-search"></span></div>
                <input type="text" class="form-control" placeholder="Search...">
              </div>
            </form>
          </div>

          <!-- Categories -->
          <div class="sidebar-box ftco-animate">
            <h3>Categories</h3>
            <ul class="categories">
              <li><a href="blog.php">All <span>(<?= array_sum($counts) ?>)</span></a></li>
              <?php foreach ($categories as $cat): ?>
                <li>
                  <a href="blog.php?category=<?= $cat['id'] ?>">
                    <?= h($cat['name']) ?> <span>(<?= $counts[$cat['id']] ?>)</span>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>

          <!-- Recent Posts -->
          <div class="sidebar-box ftco-animate">
            <h3>Recent Blog</h3>
            <?php foreach ($recentPosts as $r): ?>
              <div class="block-21 mb-4 d-flex">
                <a class="blog-img mr-4"
                   style="background-image: url('<?= h($r['image']) ?>');"></a>
                <div class="text">
                  <h3 class="heading">
                    <a href="blog-single.php?id=<?= $r['id'] ?>"><?= h($r['title']) ?></a>
                  </h3>
                  <div class="meta">
                    <div><a href="#"><span class="icon-calendar"></span> <?= date('M d, Y', strtotime($r['created_at'])) ?></a></div>
                    <div><a href="#"><span class="icon-person"></span> <?= h($r['author']) ?></a></div>
                    <div><a href="#" class="meta-chat"><span class="icon-chat"></span> 0</a></div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <!-- Paragraph -->
          <div class="sidebar-box ftco-animate">
            <h3>Paragraph</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus itaque …</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="ftco-footer ftco-section img">
    <div class="overlay"></div>
    <div class="container">
      <div class="row mb-5">
        <!-- About Us -->
        <div class="col-lg-3 col-md-6 mb-5 mb-md-5">
          <div class="ftco-footer-widget mb-4">
            <h2 class="ftco-heading-2">About Us</h2>
            <p>Far far away, behind the word mountains …</p>
            <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
              <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
              <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
              <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
            </ul>
          </div>
        </div>
        <!-- Recent Blog -->
        <div class="col-lg-4 col-md-6 mb-5 mb-md-5">
          <div class="ftco-footer-widget mb-4">
            <h2 class="ftco-heading-2">Recent Blog</h2>
            <?php foreach ($recentPosts as $r): ?>
              <div class="block-21 mb-4 d-flex">
                <a class="blog-img mr-4"
                   style="background-image: url('<?= h($r['image']) ?>');"></a>
                <div class="text">
                  <h3 class="heading">
                    <a href="blog-single.php?id=<?= $r['id'] ?>"><?= h($r['title']) ?></a>
                  </h3>
                  <div class="meta">
                    <div><a href="#"><span class="icon-calendar"></span> <?= date('M d, Y', strtotime($r['created_at'])) ?></a></div>
                    <div><a href="#"><span class="icon-person"></span> <?= h($r['author']) ?></a></div>
                    <div><a href="#" class="meta-chat"><span class="icon-chat"></span> 0</a></div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <!-- Services -->
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
        <!-- Have a Questions? -->
        <div class="col-lg-3 col-md-6 mb-5 mb-md-5">
          <div class="ftco-footer-widget mb-4">
            <h2 class="ftco-heading-2">Have a Questions?</h2>
            <div class="block-23 mb-3">
              <ul>
                <li><span class="icon icon-map-marker"></span><span class="text">203 Fake St. …</span></li>
                <li><a href="#"><span class="icon icon-phone"></span><span class="text">+2 392 3929 210</span></a></li>
                <li><a href="#"><span class="icon icon-envelope"></span><span class="text">info@yourdomain.com</span></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <!-- Copyright -->
      <div class="row">
        <div class="col-md-12 text-center">
          <p>
            Copyright &copy;<script>document.write(new Date().getFullYear());</script>
            All rights reserved | Made with <i class="icon-heart" aria-hidden="true"></i> by Colorlib
          </p>
        </div>
      </div>
    </div>
  </footer>

  <!-- loader e script -->
  <div id="ftco-loader" class="show fullscreen">…</div>
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
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY&sensor=false"></script>
  <script src="js/google-map.js"></script>
  <script src="js/main.js"></script>
</body>
</html>
