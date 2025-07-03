<?php
session_start();
require_once("../config.php");

// Controllo accesso
if (!isset($_SESSION['user']) || ($_SESSION['user']['gruppo'] ?? '') !== 'admin') {
    echo "Accesso riservato agli amministratori.";
    exit();
}

// Gestione modifica ruolo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifica_gruppo'])) {
    $email = $_POST['email_utente'];
    $nuovo_gruppo = $_POST['nuovo_gruppo'];

    $stmt = $pdo->prepare("UPDATE utente SET gruppo = :gruppo WHERE email = :email");
    $stmt->execute(['gruppo' => $nuovo_gruppo, 'email' => $email]);

    $msg = "Gruppo aggiornato per $email";
}

// Recupera utenti
try {
    $stmt = $pdo->query("SELECT email, gruppo FROM utente ORDER BY email");
    $utenti = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $pdo->query("SELECT nome FROM gruppo ORDER BY nome ASC");
    $gruppi = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$utenti || !$gruppi) {
        echo "<p class='text-danger'>Query riuscita ma nessun utente oppure nessun gruppo trovato.</p>";
    }
} catch (PDOException $e) {
    echo "<p class='text-danger'>Errore SQL: " . $e->getMessage() . "</p>";
}
?>

<!DOCTYPE html>
<html lang="it">

<head>


    <title>Modifica Utenti</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" type="image/x-icon" href="../icons/pizza.ico">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nothing+You+Could+Do" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <link rel="stylesheet" href="../css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="../css/animate.css">

    <link rel="stylesheet" href="../css/owl.carousel.min.css">
    <link rel="stylesheet" href="../css/owl.theme.default.min.css">
    <link rel="stylesheet" href="../css/magnific-popup.css">

    <link rel="stylesheet" href="../css/aos.css">

    <link rel="stylesheet" href="../css/ionicons.min.css">

    <link rel="stylesheet" href="../css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/jquery.timepicker.css">


    <link rel="stylesheet" href="../css/flaticon.css">
    <link rel="stylesheet" href="../css/icomoon.css">
    <link rel="stylesheet" href="../css/style.css">
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
                    <li class="nav-item"><a href="../index.php" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="../menu.php" class="nav-link">Menu</a></li>
                    <li class="nav-item"><a href="../services.php" class="nav-link">Services</a></li>
                    <li class="nav-item"><a href="../blog.php" class="nav-link">Blog</a></li>
                    <li class="nav-item"><a href="../about.php" class="nav-link">About</a></li>
                    <li class="nav-item"><a href="../contact.php" class="nav-link">Contact</a></li>
                    <li class="nav-item active"><a href="../admin.php" class="nav-link">Admin</a></li>
                    <li class="nav-item d-flex align-items-center">
                        <?php if (isset($_SESSION['user'])): ?>
                            <a href="../profilo.php" class="btn btn-primary mr-2">
                                <?= htmlspecialchars($_SESSION['user']['nickname']) ?>
                            </a>
                            <a href="../logout.php" class="btn btn-outline-light">Logout</a>
                        <?php else: ?>
                            <a href="../login.php" class="btn btn-primary">Login</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Hero statica -->
    <section class="slider-item" style="background-image: url('../images/bg_3.jpg'); min-height: 300px; position: relative;">
        <div class="overlay" style="background: rgba(0,0,0,0.5); position:absolute; top:0; left:0; right:0; bottom:0;"></div>
        <div class="container" style="position: relative; z-index: 2;">
            <div class="row justify-content-center align-items-center" style="min-height: 300px;">
                <div class="col-md-8 text-center text-white">
                    <h1 class="mb-3">Gestione Utenti</h1>
                    <p class="breadcrumbs"><a href="../index.php" class="text-white">Home</a> <span class="mx-2 text-white">&gt;</span> <a href="../admin.php" class="text-white">Admin</a> <span class="mx-2 text-white">&gt;</span> <span>Utenti</span></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Sezione centrale -->
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center mb-4">
                <div class="col-md-8 text-center heading-section">
                    <h2 class="mb-4" style="color:white;">Modifica il Ruolo di un Utente</h2>
                    <p>Seleziona l'utente e assegna un nuovo gruppo.</p>
                </div>
            </div>

            <?php if (isset($msg)): ?>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="alert alert-success text-center"><?= htmlspecialchars($msg) ?></div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card p-4 shadow rounded" style="background-color: black !important;">

                        <form method="POST">
                            <div class="form-group">
                                <label for="email_utente">
                                    <h4>Utente</h4>
                                </label>
                                <select name="email_utente" class="form-control" style="color:white !important; background:black !important;border: 1px solid #ccc !important;" required>
                                    <?php foreach ($utenti as $u): ?>
                                        <option value="<?= htmlspecialchars($u['email']) ?>">
                                            <?= htmlspecialchars($u['email']) ?> (<?= $u['gruppo'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="nuovo_gruppo">
                                    <h4>Nuovo Gruppo</h4>
                                </label>
                                <select name="nuovo_gruppo" class="form-control" style="color:white !important; background:black !important;border: 1px solid #ccc !important;" required>
                                    <?php foreach ($gruppi as $gr): ?>
                                        <option value="<?= htmlspecialchars($gr['nome']) ?>">
                                            <?= htmlspecialchars($gr['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group text-center mt-3">
                                <button type="submit" name="modifica_gruppo" class="btn btn-primary px-4 py-2">Aggiorna Ruolo</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mt-4">
                <div class="col-md-4 text-center">
                    <a href="../admin.php" class="btn btn-primary">Torna al Pannello Admin</a>
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
                        <div class="block-21 mb-4 d-flex">
                            <a class="blog-img mr-4" style="background-image: url(../images/image_1.jpg);"></a>
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
                            <a class="blog-img mr-4" style="background-image: url(../images/image_2.jpg);"></a>
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

</html>