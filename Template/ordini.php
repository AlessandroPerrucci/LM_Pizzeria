<?php 
session_start();
require_once 'config.php';

// Verifica se l'utente è loggato
if (!isset($_SESSION['mail'])) {
    header("Location: login.php");
    exit();
}

// Query corretta per ottenere gli ordini in corso
$sql = "SELECT ordine_online.stato, ordini_utenti.indirizzo , ordine_online.id
        FROM ordine_online, ordini_utenti 
        WHERE ordine_online.id = ordini_utenti.id_ordine
        AND ordini_utenti.email_utente = :email 
        AND (ordine_online.stato = 'in_preparazione' OR ordine_online.stato = 'in_consegna')";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':email', $_SESSION['mail']);
$stmt->execute();
$ordini = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>L.M. Pizzeria - I tuoi ordini</title>
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
    <?php $pagina_attiva = 'ordini'; ?>
    <?php include 'header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-wrap hero-wrap-2" style="background-image: url('images/bg_3.jpg');">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text align-items-end justify-content-center">
                <div class="col-md-9 ftco-animate text-center">
                    <h1 class="mb-3 bread">I tuoi ordini in corso</h1>
                    <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home</a></span> <span>Ordini</span></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Ordini Section -->
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center mb-5 pb-3">
                <div class="col-md-7 heading-section ftco-animate text-center">
                    <h2 class="mb-4">Stato dei tuoi ordini</h2>
                    <p>Tieni traccia dei tuoi ordini in tempo reale</p>
                </div>
            </div>

            <?php if (empty($ordini)): ?>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="ftco-animate">
                            <div class="icon mb-4">
                                <span class="flaticon-pizza-1" style="font-size: 4rem; color: #fac564;"></span>
                            </div>
                            <h3 class="mb-3">Nessun ordine in corso</h3>
                            <p class="mb-4">Al momento non hai ordini in preparazione o in consegna.</p>
                            <p><a href="ordina.php" class="btn btn-primary py-3 px-4">Ordina ora</a></p>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($ordini as $ordine): ?>
                        <div class="col-md-6 col-lg-4 ftco-animate">
                            <div class="pricing-entry pb-5 text-center">
                                <div>
                                    <div class="icon mb-4">
                                        <?php if ($ordine['stato'] == 'in_preparazione'): ?>
                                            <span class="flaticon-chef" style="font-size: 3rem; color: #fac564;"></span>
                                        <?php else: ?>
                                            <span class="flaticon-bicycle" style="font-size: 3rem; color: #fac564;"></span>
                                        <?php endif; ?>
                                    </div>
                                    <h3 class="mb-3">Ordine #<?php echo $ordine['id']; ?></h3>
                                    
                                    <div class="mb-3">
                                        <?php if ($ordine['stato'] == 'in_preparazione'): ?>
                                            <span class="badge badge-warning p-2" style="font-size: 0.9rem;">
                                                <i class="icon-clock-o mr-1"></i> In preparazione
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-info p-2" style="font-size: 0.9rem;">
                                                <i class="icon-bicycle mr-1"></i> In consegna
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="text-left mx-4">
                                        <p class="mb-2">
                                            <strong><i class="icon-map-marker mr-2"></i>Indirizzo:</strong><br>
                                            <span class="text-muted"><?php echo htmlspecialchars($ordine['indirizzo']); ?></span>
                                        </p>
                                        
                                        <?php if (isset($ordine['data_ordine'])): ?>
                                        <p class="mb-2">
                                            <strong><i class="icon-calendar mr-2"></i>Data ordine:</strong><br>
                                            <span class="text-muted"><?php echo date('d/m/Y H:i', strtotime($ordine['data_ordine'])); ?></span>
                                        </p>
                                        <?php endif; ?>
                                        
                                        <?php if (isset($ordine['totale'])): ?>
                                        <p class="mb-3">
                                            <strong><i class="icon-euro mr-2"></i>Totale:</strong><br>
                                            <span class="price">€<?php echo number_format($ordine['totale'], 2); ?></span>
                                        </p>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($ordine['stato'] == 'in_preparazione'): ?>
                                        <div class="alert alert-warning mx-4">
                                            <small>Il tuo ordine è attualmente in preparazione nella nostra cucina.</small>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-info mx-4">
                                            <small>Il tuo ordine è in viaggio verso di te!</small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="row justify-content-center mt-5">
                    <div class="col-md-8 text-center">
                        <div class="ftco-animate">
                            <h4 class="mb-3">Hai domande sul tuo ordine?</h4>
                            <p class="mb-4">Contattaci per qualsiasi informazione sui tuoi ordini</p>
                            <p>
                                <a href="tel:000-123-456-7890" class="btn btn-primary py-2 px-4 mr-2">
                                    <i class="icon-phone mr-2"></i>Chiama ora
                                </a>
                                <a href="#" class="btn btn-outline-primary py-2 px-4">
                                    <i class="icon-envelope mr-2"></i>Invia email
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <!-- loader -->
    <div id="ftco-loader" class="show fullscreen">
        <svg class="circular" width="48px" height="48px">
            <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
            <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00" />
        </svg>
    </div>

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
    <script src="js/main.js"></script>

</body>

</html>