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

// Aggiunta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['azione'] === 'aggiungi') {
    try {
        $stmt = $pdo->prepare("INSERT INTO antipasto (nome, descrizione, prezzo, disponibile) VALUES (:nome, :descrizione, :prezzo, :disponibile)");
        $stmt->execute([
            'nome' => $_POST['nome'],
            'descrizione' => $_POST['descrizione'],
            'prezzo' => $_POST['prezzo'],
            'disponibile' => isset($_POST['disponibile']) ? 1 : 0
        ]);
        $_SESSION['flash'] = ['tipo' => 'success', 'testo' => 'Antipasto aggiunto con successo.'];
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'testo' => 'Errore: esiste già un antipasto con questo nome.'];
        } else {
            $_SESSION['flash'] = ['tipo' => 'danger', 'testo' => 'Errore imprevisto: ' . $e->getMessage()];
        }
    }
    header("Location: modifica_antipasti.php");
    exit();
}

// Eliminazione
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['azione'] === 'elimina') {
    $stmt = $pdo->prepare("DELETE FROM antipasto WHERE nome = :nome");
    $stmt->execute(['nome' => $_POST['nome']]);
    $_SESSION['flash'] = ['tipo' => 'warning', 'testo' => 'Antipasto eliminato.'];
    header("Location: modifica_antipasti.php");
    exit();
}

// Modifica
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['azione'] === 'modifica') {
    $stmt = $pdo->prepare("UPDATE antipasto SET descrizione = :descrizione, prezzo = :prezzo, disponibile = :disponibile WHERE nome = :nome");
    $stmt->execute([
        'nome' => $_POST['nome'],
        'descrizione' => $_POST['descrizione'],
        'prezzo' => $_POST['prezzo'],
        'disponibile' => isset($_POST['disponibile']) ? 1 : 0
    ]);
    $_SESSION['flash'] = ['tipo' => 'success', 'testo' => 'Antipasto modificato con successo.'];
    header("Location: modifica_antipasti.php");
    exit();
}

// Lettura
$stmt = $pdo->query("SELECT * FROM antipasto ORDER BY nome ASC");
$antipasti = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <title>Gestione Antipasti</title>
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
                    <h1 class="mb-3">Gestione Antipasti</h1>
                    <p class="breadcrumbs">
                        <a href="../index.php" class="text-white">Home</a>
                        <span class="mx-2 text-white">&gt;</span>
                        <a href="../admin.php" class="text-white">Admin</a>
                        <span class="mx-2 text-white">&gt;</span>
                        <span>Antipasti</span>
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center mb-4">
                <div class="col-md-8 text-center">
                    <h2 class="mb-3">Gestione Antipasti</h2>
                </div>
            </div>

            <?php if ($flash): ?>
                <div class="alert alert-<?= $flash['tipo'] ?> text-center" role="alert">
                    <?= htmlspecialchars($flash['testo']) ?>
                </div>
            <?php endif; ?>

            <!-- Form aggiunta -->
            <div class="row justify-content-center mb-5">
                <div class="col-md-8">
                    <div class="bg-dark p-4 rounded">
                        <form method="POST">
                            <input type="hidden" name="azione" value="aggiungi">
                            <div class="form-group">
                                <label style="color:white;">Nome</label>
                                <input type="text" name="nome" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label style="color:white;">Descrizione</label>
                                <input type="text" name="descrizione" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label style="color:white;">Prezzo (€)</label>
                                <input type="number" step="0.01" name="prezzo" class="form-control" required>
                            </div>
                            <div class="form-group form-check">
                                <input type="checkbox" name="disponibile" class="form-check-input" checked>
                                <label class="form-check-label" style="color:white;">Disponibile</label>
                            </div>
                            <button type="submit" class="btn btn-primary">Aggiungi Antipasto</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tabella -->
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-striped bg-white" style="background-color: #343a40 !important; color:white !important;">
                        <thead class="thead-dark">
                            <tr>
                                <th>Nome</th>
                                <th>Descrizione</th>
                                <th>Prezzo</th>
                                <th>Disponibile</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($antipasti as $antipasto): ?>
                                <tr>
                                    <form method="POST" class="form-inline">
                                        <input type="hidden" name="azione" value="modifica">
                                        <input type="hidden" name="nome" value="<?= htmlspecialchars($antipasto['nome']) ?>">
                                        <td><?= htmlspecialchars($antipasto['nome']) ?></td>
                                        <td><input type="text" name="descrizione" value="<?= htmlspecialchars($antipasto['descrizione']) ?>" class="form-control form-control-sm"></td>
                                        <td><input type="number" name="prezzo" step="0.01" value="<?= $antipasto['prezzo'] ?>" class="form-control form-control-sm"></td>
                                        <td><input type="checkbox" name="disponibile" <?= $antipasto['disponibile'] ? 'checked' : '' ?>></td>
                                        <td>
                                            <button type="submit" class="btn btn-sm btn-success">Salva</button>
                                    </form>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="azione" value="elimina">
                                        <input type="hidden" name="nome" value="<?= htmlspecialchars($antipasto['nome']) ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Eliminare l\'antipasto?')">Elimina</button>
                                    </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($antipasti)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted" style="color:white !important;">Nessun antipasto presente.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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