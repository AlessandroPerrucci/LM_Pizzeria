<?php
session_start();
require_once(dirname(__DIR__) . '/config.php');

// Controllo accesso: solo admin
if (!isset($_SESSION['user']) || $_SESSION['user']['gruppo'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Mostra flash message e lo cancella dopo il primo uso
$flash = $_SESSION['flash_message'] ?? null;
unset($_SESSION['flash_message']);

// Aggiunta ingrediente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $nome = trim($_POST['nome']);
    $descrizione = trim($_POST['descrizione']);

    if (!empty($nome)) {
        $stmt = $pdo->prepare("INSERT INTO ingrediente (nome, descrizione) VALUES (?, ?)");
        try {
            $stmt->execute([$nome, $descrizione]);
            $_SESSION['flash_message'] = "Ingrediente aggiunto con successo.";
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = "Errore: ingrediente già esistente.";
        }
    } else {
        $_SESSION['flash_message'] = "Il nome dell'ingrediente non può essere vuoto.";
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Modifica descrizione ingrediente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $nome = trim($_POST['nome']);
    $descrizione = trim($_POST['descrizione']);

    if (!empty($nome)) {
        $stmt = $pdo->prepare("UPDATE ingrediente SET descrizione = ? WHERE nome = ?");
        $stmt->execute([$descrizione, $nome]);
        $_SESSION['flash_message'] = "Ingrediente aggiornato.";
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Eliminazione ingrediente
if (isset($_GET['delete'])) {
    $nome = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM ingrediente WHERE nome = ?");
    $stmt->execute([$nome]);
    $_SESSION['flash_message'] = "Ingrediente eliminato.";

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Recupera tutti gli ingredienti
$stmt = $pdo->query("SELECT * FROM ingrediente ORDER BY nome ASC");
$ingredienti = $stmt->fetchAll();
?>


<!DOCTYPE html>
<html lang="it">
<head>
    <title>Gestione Ingredienti</title>
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
                <h1 class="mb-3">Gestione Ingredienti</h1>
                <p class="breadcrumbs">
                    <a href="../index.php" class="text-white">Home</a>
                    <span class="mx-2 text-white">&gt;</span>
                    <a href="../admin.php" class="text-white">Admin</a>
                    <span class="mx-2 text-white">&gt;</span>
                    <span>Ingredienti</span>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CONTENUTO -->
<section class="ftco-section">
    <div class="container">
        <?php if (!empty($flash)): ?>
    <div class="alert alert-info text-center"><?= htmlspecialchars($flash) ?></div>
<?php endif; ?>

        <!-- FORM AGGIUNTA -->
        <div class="row justify-content-center mb-4">
            <div class="col-md-8">
                <div class="card p-4 shadow bg-dark text-white" style="background-color: gray-dark !important;">
                    <h4 class="mb-3">Aggiungi Nuovo Ingrediente</h4>
                    <form method="POST">
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" name="nome" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="descrizione">Descrizione</label>
                            <textarea name="descrizione" class="form-control" rows="2"></textarea>
                        </div>
                        <button type="submit" name="add" class="btn btn-primary">Aggiungi</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- TABELLA -->
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h3 class="text-white mb-3">Lista Ingredienti</h3>
                <table class="table table-dark table-bordered">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Descrizione</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ingredienti as $row): ?>
                        <tr>
                            <form method="POST">
                                <td>
                                    <input type="text" name="nome" value="<?= htmlspecialchars($row['nome']) ?>" class="form-control-plaintext text-white" readonly>
                                </td>
                                <td>
                                    <textarea name="descrizione" class="form-control"><?= htmlspecialchars($row['descrizione']) ?></textarea>
                                </td>
                                <td>
                                    <button type="submit" name="update" class="btn btn-success btn-sm">Salva</button>
                                    <a href="?delete=<?= urlencode($row['nome']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Eliminare questo ingrediente?');">Elimina</a>
                                </td>
                            </form>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($bevande)): ?>
                            <tr><td colspan="7" class="text-center text-muted" style="color:white !important;">Nessun ingrediente presente.</td></tr>
                            <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row justify-content-center mt-4">
            <div class="col-md-4 text-center">
                <a href="../admin.php" class="btn btn-primary">Torna al Pannello Admin</a>
            </div>
        </div>
    </div>
</section>

<?php include '../footer.php'; ?>
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
</body>
</html>

