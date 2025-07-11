<?php
require_once("config.php");

// Recupera tutte le recensioni collegate agli utenti
$stmt = $pdo->query("
    SELECT r.*, u.nome AS autore_nome, u.foto_profilo AS autore_foto
    FROM recensione r
    JOIN utente u ON u.recensione = r.id
    ORDER BY r.data DESC
");
$recensioni = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <title>Recensioni</title>
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
    <style>
        .review-card {
            background-color: #1f2329;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            color: white;
        }

        .review-photo {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-top: 10px;
        }

        .profile-pic {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 10px;
        }

        .star {
            color: gold;
        }

        .review-card p {
            white-space: pre-wrap;
            word-break: break-word;
        }
    </style>
</head>

<body>
    <?php $pagina_attiva = 'recensioni'; ?>
    <?php include 'header.php'; ?>

    <section class="slider-item" style="background-image: url('images/bg_3.jpg'); min-height: 300px; position: relative;">
        <div class="overlay" style="background: rgba(0,0,0,0.5); position:absolute; top:0; left:0; right:0; bottom:0;"></div>
        <div class="container" style="position: relative; z-index: 2;">
            <div class="row justify-content-center align-items-center" style="min-height: 300px;">
                <div class="col-md-8 text-center text-white">
                    <h1 class="mb-3">Cosa dicono di noi</h1>
                    <p class="breadcrumbs"><a href="index.php" class="text-white">Home</a> <span class="mx-2 text-white">&gt;</span> <span>Recensioni</span></p>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section">
        <div class="container">
            <h3 class="text-white mb-4">Recensioni dei nostri clienti</h3>

            <?php if (empty($recensioni)): ?>
                <p class="text-light text-center">Nessuna recensione disponibile.</p>
            <?php else: ?>
                <?php foreach ($recensioni as $r): ?>
                    <div class="review-card">
                        <div class="d-flex align-items-center mb-2">
                            <img src="<?= htmlspecialchars($r['autore_foto']) ?>" class="profile-pic">
                            <strong><?= htmlspecialchars($r['autore_nome']) ?></strong>
                            <span class="ml-3 text-muted"><?= date('d/m/Y H:i', strtotime($r['data'])) ?></span>
                        </div>
                        <div class="mb-2">
                            <?php for ($i = 0; $i < $r['stelle']; $i++): ?>
                                <span class="star">★</span>
                            <?php endfor; ?>
                            <?php for ($i = $r['stelle']; $i < 5; $i++): ?>
                                <span class="text-secondary">★</span>
                            <?php endfor; ?>
                        </div>
                        <p><?= nl2br(htmlspecialchars($r['commento'])) ?></p>
                        <?php if ($r['foto']): ?>
                            <img src="images/recensioni/<?= htmlspecialchars($r['foto']) ?>" class="review-photo">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'footer.php'; ?>
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