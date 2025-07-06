<?php
session_start();
require_once("../config.php");

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// Verifica accesso admin
if (!isset($_SESSION['user']) || ($_SESSION['user']['gruppo'] ?? '') !== 'admin') {
    echo "Accesso riservato solo agli amministratori.";
    exit();
}

// Aggiunta bevanda
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['azione'] === 'aggiungi') {
    try {
        $stmt = $pdo->prepare("INSERT INTO bevanda (nome, centilitri, descrizione, tipologia, prezzo, disponibile) 
                               VALUES (:nome, :centilitri, :descrizione, :tipologia, :prezzo, :disponibile)");
        $stmt->execute([
            'nome' => $_POST['nome'],
            'centilitri' => $_POST['centilitri'],
            'descrizione' => $_POST['descrizione'],
            'tipologia' => $_POST['tipologia'],
            'prezzo' => $_POST['prezzo'],
            'disponibile' => isset($_POST['disponibile']) ? 1 : 0
        ]);
        $_SESSION['flash'] = ['tipo' => 'success', 'testo' => 'Bevanda aggiunta con successo.'];
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'testo' => 'Errore: esiste già una bevanda con questo nome.'];
        } else {
            $_SESSION['flash'] = ['tipo' => 'danger', 'testo' => 'Errore imprevisto: ' . $e->getMessage()];
        }
    }
    header("Location: modifica_bevande.php");
    exit();
}

// Eliminazione
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['azione'] === 'elimina') {
    $stmt = $pdo->prepare("DELETE FROM bevanda WHERE nome = :nome");
    $stmt->execute(['nome' => $_POST['nome']]);
    $_SESSION['flash'] = ['tipo' => 'warning', 'testo' => 'Bevanda eliminata.'];
    header("Location: modifica_bevande.php");
    exit();
}

// Modifica
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['azione'] === 'modifica') {
    $stmt = $pdo->prepare("UPDATE bevanda 
                           SET centilitri = :centilitri, descrizione = :descrizione, tipologia = :tipologia, prezzo = :prezzo, disponibile = :disponibile 
                           WHERE nome = :nome");
    $stmt->execute([
        'nome' => $_POST['nome'],
        'centilitri' => $_POST['centilitri'],
        'descrizione' => $_POST['descrizione'],
        'tipologia' => $_POST['tipologia'],
        'prezzo' => $_POST['prezzo'],
        'disponibile' => isset($_POST['disponibile']) ? 1 : 0
    ]);
    $_SESSION['flash'] = ['tipo' => 'success', 'testo' => 'Bevanda modificata con successo.'];
    header("Location: modifica_bevande.php");
    exit();
}

// Lettura bevande
$stmt = $pdo->query("SELECT * FROM bevanda ORDER BY nome ASC");
$bevande = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <title>Gestione Bevande</title>
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
                <h1 class="mb-3">Gestione Bevande</h1>
                <p class="breadcrumbs">
                    <a href="../index.php" class="text-white">Home</a>
                    <span class="mx-2 text-white">&gt;</span>
                    <a href="../admin.php" class="text-white">Admin</a>
                    <span class="mx-2 text-white">&gt;</span>
                    <span>Bevande</span>
                </p>
            </div>
        </div>
    </div>
</section>
    <section class="ftco-section">
        <?php if ($flash): ?>
    <div class="alert alert-<?= $flash['tipo'] ?> text-center container mt-4" role="alert">
        <?= htmlspecialchars($flash['testo']) ?>
    </div>
<?php endif; ?>
    <div class="container">

        <!-- Form Aggiunta -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="p-4 p-md-5 rounded" style="background-color: #343a40 !important;">
                    <h4>Aggiungi nuova bevanda</h4>
                    <form method="POST">
                        <input type="hidden" name="azione" value="aggiungi">
                        <div class="form-group">
                            <label style="color: white;">Nome</label>
                            <input autocomplete="off" type="text" name="nome" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label style="color: white;">Centilitri</label>
                            <input autocomplete="off" type="number" step="1" name="centilitri" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label style="color: white;">Descrizione</label>
                            <input autocomplete="off" type="text" name="descrizione" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label style="color: white;">Tipologia</label>
                            <input autocomplete="off" type="text" name="tipologia" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label style="color: white;">Prezzo (€)</label>
                            <input autocomplete="off" type="number" step="0.01" name="prezzo" class="form-control" required>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" name="disponibile" class="form-check-input" checked>
                            <label style="color: white;" class="form-check-label">Disponibile</label>
                        </div>
                        <button type="submit" name="add" class="btn btn-primary">Aggiungi</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Lista Bevande -->
        <div class="row mt-5">
            <div class="col-12">
                <h4 class="mb-3">Lista Bevande</h4>
                <div class="table-responsive">
                    <table class="table table-bordered bg-white" style="background-color: #343a40 !important; color:white !important;">
                        <thead class="thead-dark">
                            <tr>
                                <th>Nome</th>
                                <th>Centilitri</th>
                                <th>Descrizione</th>
                                <th>Tipologia</th>
                                <th>Prezzo</th>
                                <th>Disponibile</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bevande as $bevanda): ?>
                            <tr>
                                <form method="POST" class="form-inline">
                                    <input autocomplete="off" type="hidden" name="azione" value="modifica">
                                    <input autocomplete="off" type="hidden" name="nome" value="<?= htmlspecialchars($bevanda['nome']) ?>">
                                    <td><input type="text" name="nome" value="<?= htmlspecialchars($bevanda['nome']) ?>" class="form-control-plaintext text-white" readonly></td>
                                    <td><input autocomplete="off" type="number" name="centilitri" step="0.01" value="<?= $bevanda['centilitri'] ?>" class="form-control form-control-sm"></td>
                                    <td><input autocomplete="off" type="text" name="descrizione" value="<?= htmlspecialchars($bevanda['descrizione']) ?>" class="form-control form-control-sm"></td>
                                    <td><input autocomplete="off" type="text" name="tipologia" value="<?= htmlspecialchars($bevanda['tipologia']) ?>" class="form-control form-control-sm"></td>
                                    <td><input autocomplete="off" type="number" name="prezzo" step="0.01" value="<?= $bevanda['prezzo'] ?>" class="form-control form-control-sm"></td>
                                    <td><input type="checkbox" name="disponibile" <?= $bevanda['disponibile'] ? 'checked' : '' ?>></td>
                                    <td>
                                        <button type="submit" class="btn btn-sm btn-success">Salva</button>
                                </form>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="azione" value="elimina">
                                    <input type="hidden" name="nome" value="<?= htmlspecialchars($bevanda['nome']) ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Eliminare la bevanda?')">Elimina</button>
                                </form>
                                    </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($bevande)): ?>
                            <tr><td colspan="7" class="text-center text-muted" style="color:white !important;">Nessuna bevanda presente.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
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