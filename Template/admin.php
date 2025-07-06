<?php
session_start();
require_once("config.php"); // connessione al DB


// Verifica login
if (!isset($_SESSION['user'])) {
    echo "Accesso negato. Effettua prima il login.";
    exit();
}

$user = $_SESSION['user']; // contiene tutti i dati dell'utente
$gruppo = $user['gruppo'] ?? '';

if ($gruppo !== 'admin') {
    echo "Accesso riservato solo agli amministratori.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <title>Admin</title>
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
    <?php $pagina_attiva = ''; ?>
    <?php include 'header.php'; ?>
    <!-- Sezione Hero Header -->
    <section class="slider-item" style="background-image: url('../images/bg_3.jpg'); min-height: 400px;">
        <div class="overlay" style=" position: absolute; top: 0; left: 0; right: 0; bottom: 0;"></div>
        <div class="container" style="position: relative; z-index: 2;">
            <div class="row justify-content-center align-items-center" style="min-height: 400px;">
                <div class="col-md-8 text-center slider-text" style="color: white;">
                    <h1 class="mb-3">Benvenuto, <?= htmlspecialchars($user['nome'] ?? 'Admin') ?></h1>
                    <p class="breadcrumbs"><a href="../index.php" style="color: #ccc;">Home</a> <span class="mx-2">&gt;</span> <span>Admin</span></p>
                </div>
            </div>
        </div>
    </section>


    <!-- Sezione Contenuto Admin -->
    <section class="ftco-section">
        <div class="container mt-5">
            <!-- Titolo Sezione e introduzione -->
            <div class="row justify-content-center mb-5 pb-3">
                <div class="col-md-7 heading-section ftco-animate text-center">
                    <h2 class="mb-4">Pannello di Amministrazione</h2>
                    <p>Seleziona un'operazione da eseguire.</p>
                </div>
            </div>
            <!-- Griglia di azioni admin -->
            <div class="row">
                <!-- Colonna 1: Gestione Pizze -->
                <div class="col-md-4 ftco-animate">
                    <div class="media d-block text-center block-6 services">
                        <div class="icon d-flex justify-content-center align-items-center mb-5">
                            <span class="flaticon-pizza-1"></span>
                        </div>
                        <div class="media-body">
                            <h3 class="heading" style="color:#fff;">Gestione Pizze</h3>
                            <p style="color:#fff;">Aggiungi, modifica o rimuovi le pizze dal menu.</p>
                            <p><a href="admin/modifica_pizze.php" class="btn btn-primary">Gestisci</a></p>
                        </div>
                    </div>
                </div>
                <!-- Colonna 2: Gestione Ingredienti -->
                <div class="col-md-4 ftco-animate">
                    <div class="media d-block text-center block-6 services">
                        <div class="icon d-flex justify-content-center align-items-center mb-5">
                            <span class="flaticon-diet"></span>
                        </div>
                        <div class="media-body">
                            <h3 class="heading" style="color:#fff;">Gestione Ingredienti</h3>
                            <p style="color:#fff;">Gestisci gli ingredienti disponibili per le pizze.</p>
                            <p><a href="admin/modifica_ingredienti.php" class="btn btn-primary">Gestisci</a></p>
                        </div>
                    </div>
                </div>
                <!-- Colonna 3: Gestione Utenti -->
                <div class="col-md-4 ftco-animate">
                    <div class="media d-block text-center block-6 services">
                        <div class="icon d-flex justify-content-center align-items-center mb-5">
                            <span class="icon-person"></span>
                        </div>
                        <div class="media-body">
                            <h3 class="heading" style="color:#fff;">Gestione Utenti</h3>
                            <p style="color:#fff;">Visualizza la lista degli utenti e modificane i ruoli.</p>
                            <p><a href="admin/modifica_utenti.php" class="btn btn-primary">Gestisci</a></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="ftco-section">
        <div class="container mt-5">
            <!-- Griglia di azioni admin -->
            <div class="row">
                <!-- Colonna 1: Gestione Pizze -->
                <div class="col-md-4 ftco-animate">
                    <div class="media d-block text-center block-6 services">
                        <div class="icon d-flex justify-content-center align-items-center mb-5">
                            <span class="flaticon-chef"></span>
                        </div>
                        <div class="media-body">
                            <h3 class="heading" style="color:#fff;">Gestione Antipasti</h3>
                            <p style="color:#fff;">Aggiungi, modifica o rimuovi gli antipasti dal menu.</p>
                            <p><a href="admin/modifica_antipasti.php" class="btn btn-primary">Gestisci</a></p>
                        </div>
                    </div>
                </div>
                <!-- Colonna 2: Gestione Ingredienti -->
                <div class="col-md-4 ftco-animate">
                    <div class="media d-block text-center block-6 services">
                        <div class="icon d-flex justify-content-center align-items-center mb-5">
                            <span class="flaticon-chef"></span>
                        </div>
                        <div class="media-body">
                            <h3 class="heading" style="color:#fff;">Gestione Secondi</h3>
                            <p style="color:#fff;">Aggiungi, modifica o rimuovi i secondi dal menu.</p>
                            <p><a href="admin/modifica_secondi.php" class="btn btn-primary">Gestisci</a></p>
                        </div>
                    </div>
                </div>
                <!-- Colonna 3: Gestione Utenti -->
                <div class="col-md-4 ftco-animate">
                    <div class="media d-block text-center block-6 services">
                        <div class="icon d-flex justify-content-center align-items-center mb-5">
                            <span class="flaticon-diet"></span>
                        </div>
                        <div class="media-body">
                            <h3 class="heading" style="color:#fff;">Gestione Bevande</h3>
                            <p style="color:#fff;">Aggiungi, modifica o rimuovi le bevande dal menu.</p>
                            <p><a href="admin/modifica_bevande.php" class="btn btn-primary">Gestisci</a></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="ftco-section">
        <div class="container mt-5">
            <!-- Griglia di azioni admin -->
            <div class="row">
                <!-- Colonna 1: Gestione Pizze -->
                <div class="col-md-4 ftco-animate">
                    <div class="media d-block text-center block-6 services">
                        <div class="icon d-flex justify-content-center align-items-center mb-5">
                            <span class="flaticon-chef"></span>
                        </div>
                        <div class="media-body">
                            <h3 class="heading" style="color:#fff;">Gestione Contorni</h3>
                            <p style="color:#fff;">Aggiungi, modifica o rimuovi i contorni dal menu.</p>
                            <p><a href="admin/modifica_contorni.php" class="btn btn-primary">Gestisci</a></p>
                        </div>
                    </div>
                </div>

                <!-- Colonna 2 -->
                <div class="col-md-4 ftco-animate">
                    <div class="media d-block text-center block-6 services">
                        <div class="icon d-flex justify-content-center align-items-center mb-5">
                            <span class="icon-person"></span>
                        </div>
                        <div class="media-body">
                            <h3 class="heading" style="color:#fff;">Gestione Prenotazioni</h3>
                            <p style="color:#fff;">Aggiungi, modifica o rimuovi le prenotazioni</p>
                            <p><a href="admin/modifica_prenotazioni.php" class="btn btn-primary">Gestisci</a></p>
                        </div>
                    </div>
                </div>

                <!-- Colonna 3 -->
                <div class="col-md-4 ftco-animate">
                    <div class="media d-block text-center block-6 services">
                        <div class="icon d-flex justify-content-center align-items-center mb-5">
                            <span class="icon-person"></span>
                        </div>
                        <div class="media-body">
                            <h3 class="heading" style="color:#fff;">Gestione Gruppi</h3>
                            <p style="color:#fff;">Aggiungi, modifica o rimuovi le impostazioni sui gruppi</p>
                            <p><a href="admin/modifica_gruppi.php" class="btn btn-primary">Gestisci</a></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <section class="ftco-section">
        <div class="container mt-5">
            <!-- Griglia di azioni admin -->
            <div class="row">
                <!-- Colonna 1: Gestione Pizze -->
                <div class="col-md-4 ftco-animate">
                    <div class="media d-block text-center block-6 services">
                        <div class="icon d-flex justify-content-center align-items-center mb-5">
                            <span class="icon-person"></span>
                        </div>
                        <div class="media-body">
                            <h3 class="heading" style="color:#fff;">Gestione Privilegi</h3>
                            <p style="color:#fff;">Aggiungi, modifica o rimuovi i privilegi.</p>
                            <p><a href="admin/modifica_privilegi.php" class="btn btn-primary">Gestisci</a></p>
                        </div>
                    </div>
                </div>


            </div>
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
<script src="js/scrollax.min.js"></script>
<script src="js/main.js"></script>


</html>