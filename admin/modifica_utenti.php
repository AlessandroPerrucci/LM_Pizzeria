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
    <?php $pagina_attiva = 'onedirup'; ?>
	<?php include '../header.php'; ?>
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
                    <div class="card p-4 shadow rounded" style="background-color: #343a40 !important;">

                        <form method="POST">
                            <div class="form-group">
                                <label for="email_utente">
                                    <h4>Utente</h4>
                                </label>
                                <select name="email_utente" class="form-control" style="color:white !important; background:#343a40 !important;border: 1px solid #ccc !important;" required>
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
                                <select name="nuovo_gruppo" class="form-control" style="color:white !important; background:#343a40 !important;border: 1px solid #ccc !important;" required>
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
                                    
    <?php $pagina_attiva = 'onedirup'; ?>
	<?php include '../footer.php'; ?>
    
</body>
<script src="../js/jquery.min.js"></script>
<script src="../js/jquery-migrate-3.0.1.min.js"></script>
<script src="../js/popper.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/jquery.easing.1.3.js"></script>
<script src="../js/jquery.waypoints.min.js"></script>
<script src="../js/jquery.stellar.min.js"></script>
<script src="../js/owl.carousel.min.js"></script>
<script src="../js/jquery.magnific-popup.min.js"></script>
<script src="../js/aos.js"></script>
<script src="../js/scrollax.min.js"></script>
<script src="../js/main.js"></script>
</html>