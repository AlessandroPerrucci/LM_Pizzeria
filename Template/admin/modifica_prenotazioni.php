<?php
session_start();
require_once("../config.php");

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

if (!isset($_SESSION['user']) || ($_SESSION['user']['gruppo'] ?? '') !== 'admin') {
    echo "Accesso riservato solo agli amministratori.";
    exit();
}

// Modifica prenotazione
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['azione'] === 'modifica') {
    $stmt = $pdo->prepare("UPDATE prenotazione SET data_ = :data_, periodo = :periodo, num_persone = :num_persone, note = :note, email_utente = :email_utente WHERE id = :id");
    $stmt->execute([
        'id' => $_POST['id'],
        'data_' => $_POST['data_'],
        'periodo' => $_POST['periodo'],
        'num_persone' => $_POST['num_persone'],
        'note' => $_POST['note'],
        'email_utente' => $_POST['email_utente']
    ]);
    $_SESSION['flash'] = ['tipo' => 'success', 'testo' => 'Prenotazione modificata con successo.'];
    header("Location: modifica_prenotazioni.php");
    exit();
}

// Eliminazione prenotazione
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['azione'] === 'elimina') {
    $stmt = $pdo->prepare("DELETE FROM prenotazione WHERE id = :id");
    $stmt->execute(['id' => $_POST['id']]);
    $_SESSION['flash'] = ['tipo' => 'warning', 'testo' => 'Prenotazione eliminata.'];
    header("Location: modifica_prenotazioni.php");
    exit();
}

// Aggiunta prenotazione
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['azione'] === 'aggiungi') {
    // Controlla che l'email esista nella tabella utente
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM utente WHERE email = :email");
    $stmt->execute(['email' => $_POST['email_utente']]);
    $email_esiste = $stmt->fetchColumn();

    if ($email_esiste) {
        try {
            $stmt = $pdo->prepare("INSERT INTO prenotazione (data_, periodo, num_persone, note, email_utente)
                                   VALUES (:data_, :periodo, :num_persone, :note, :email_utente)");
            $stmt->execute([
                'data_' => $_POST['data_'],
                'periodo' => $_POST['periodo'],
                'num_persone' => $_POST['num_persone'],
                'note' => $_POST['note'],
                'email_utente' => $_POST['email_utente']
            ]);
            $_SESSION['flash'] = ['tipo' => 'success', 'testo' => 'Prenotazione aggiunta con successo.'];
        } catch (PDOException $e) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'testo' => 'Errore durante l\'aggiunta: ' . $e->getMessage()];
        }
    } else {
        $_SESSION['flash'] = ['tipo' => 'danger', 'testo' => 'Errore: l\'email inserita non è registrata.'];
    }

    header("Location: modifica_prenotazioni.php");
    exit();
}



// Recupera prenotazioni
$stmt = $pdo->query("SELECT * FROM prenotazione ORDER BY data_ DESC, periodo ASC");
$prenotazioni = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <title>Gestione Prenotazioni</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" type="image/x-icon" href="../icons/pizza.ico">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nothing+You+Could+Do" rel="stylesheet">

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
    <!-- HERO SECTION -->
    <section class="slider-item" style="background-image: url('../images/bg_3.jpg'); min-height: 300px; position: relative;">
        <div class="overlay" style="background: rgba(0,0,0,0.5); position:absolute; top:0; left:0; right:0; bottom:0;"></div>
        <div class="container" style="position: relative; z-index: 2;">
            <div class="row justify-content-center align-items-center" style="min-height: 300px;">
                <div class="col-md-8 text-center text-white">
                    <h1 class="mb-3">Gestione Prenotazioni</h1>
                    <p class="breadcrumbs">
                        <a href="../index.php" class="text-white">Home</a>
                        <span class="mx-2 text-white">&gt;</span>
                        <a href="../admin.php" class="text-white">Admin</a>
                        <span class="mx-2 text-white">&gt;</span>
                        <span>Prenotazioni</span>
                    </p>
                </div>
            </div>
        </div>
    </section>


    <section class="ftco-section">
        <div class="container">

            <?php if ($flash): ?>
                <div class="alert alert-<?= $flash['tipo'] ?> text-center" role="alert">
                    <?= htmlspecialchars($flash['testo']) ?>
                </div>
            <?php endif; ?>
            <!-- Form aggiunta manuale -->
            <div class="row justify-content-center mb-4">
                <div class="col-md-10">
                    <div class="bg-dark p-4 rounded">
                        <h4 class="text-white mb-3">Aggiungi nuova prenotazione</h4>
                        <form method="POST">
                            <input type="hidden" name="azione" value="aggiungi">
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label style="color:white;">Data</label>
                                    <input autocomplete="off" type="date" name="data_" class="form-control" required>
                                </div>
                                <div class="form-group col-md-2">
                                    <label style="color:white;">Periodo</label>
                                    <select name="periodo" class="form-control bg-dark text-light" style="background-color: #343a40 !important; color: white !important;" required>
                                        <option value="AM">AM</option>
                                        <option value="PM">PM</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label style="color:white;">N. Persone</label>
                                    <input autocomplete="off" type="number" name="num_persone" class="form-control" min="1" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label style="color:white;">Email utente</label>
                                    <input autocomplete="off" type="email" name="email_utente" class="form-control" required>
                                </div>
                                <div class="form-group col-md-10 mt-2">
                                    <label style="color:white;">Note</label>
                                    <input autocomplete="off" type="text" name="note" class="form-control">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Aggiungi Prenotazione</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered bg-white" style="background-color: #343a40 !important; color:white !important;">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Data</th>
                            <th>Periodo</th>
                            <th>N. Persone</th>
                            <th>Note</th>
                            <th>Email Utente</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prenotazioni as $p): ?>
                            <tr>
                                <form method="POST">
                                    <input type="hidden" name="azione" value="modifica">
                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                    <td><input autocomplete="off" type="text" name="nome" value="<?= htmlspecialchars($p['id']) ?>" class="form-control-plaintext text-white" readonly></td>
                                    <td><input autocomplete="off" type="date" name="data_" value="<?= $p['data_'] ?>" class="form-control form-control-sm"></td>
                                    <td>
                                        <select name="periodo" class="form-control form-control-sm" style="background-color: #343a40 !important; color: white !important;">
                                            <option value="AM" <?= $p['periodo'] === 'AM' ? 'selected' : '' ?>>AM</option>
                                            <option value="PM" <?= $p['periodo'] === 'PM' ? 'selected' : '' ?>>PM</option>
                                        </select>
                                    </td>
                                    <td><input autocomplete="off" type="number" name="num_persone" value="<?= $p['num_persone'] ?>" class="form-control form-control-sm" min="1"></td>
                                    <td><input autocomplete="off" type="text" name="note" value="<?= htmlspecialchars($p['note']) ?>" class="form-control form-control-sm"></td>
                                    <td><input autocomplete="off" type="email" name="email_utente" value="<?= htmlspecialchars($p['email_utente']) ?>" class="form-control form-control-sm"></td>
                                    <td>
                                        <button type="submit" class="btn btn-sm btn-success">Salva</button>
                                </form>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="azione" value="elimina">
                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Eliminare la prenotazione?')">Elimina</button>
                                </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($prenotazioni)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted" style="color:white !important;">Nessuna prenotazione presente.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

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