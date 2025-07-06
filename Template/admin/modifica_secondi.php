<?php
session_start();
require_once("../config.php");

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

if (!isset($_SESSION['user']) || ($_SESSION['user']['gruppo'] ?? '') !== 'admin') {
    echo "Accesso riservato solo agli amministratori.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['azione'] === 'aggiungi') {
    try {
        $stmt = $pdo->prepare("INSERT INTO secondo (nome, disponibile, descrizione, prezzo) 
                               VALUES (:nome, :disponibile, :descrizione, :prezzo)");
        $stmt->execute([
            'nome' => $_POST['nome'],
            'disponibile' => isset($_POST['disponibile']) ? 1 : 0,
            'descrizione' => $_POST['descrizione'],
            'prezzo' => $_POST['prezzo']
        ]);
        $_SESSION['flash'] = ['tipo' => 'success', 'testo' => 'Secondo aggiunto con successo.'];
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'testo' => 'Errore: esiste già un secondo con questo nome.'];
        } else {
            $_SESSION['flash'] = ['tipo' => 'danger', 'testo' => 'Errore imprevisto: ' . $e->getMessage()];
        }
    }
    header("Location: modifica_secondi.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['azione'] === 'elimina') {
    $stmt = $pdo->prepare("DELETE FROM secondo WHERE nome = :nome");
    $stmt->execute(['nome' => $_POST['nome']]);
    $_SESSION['flash'] = ['tipo' => 'warning', 'testo' => 'Secondo eliminato.'];
    header("Location: modifica_secondi.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['azione'] === 'modifica') {
    $stmt = $pdo->prepare("UPDATE secondo 
                           SET disponibile = :disponibile, descrizione = :descrizione, prezzo = :prezzo 
                           WHERE nome = :nome");
    $stmt->execute([
        'nome' => $_POST['nome'],
        'disponibile' => isset($_POST['disponibile']) ? 1 : 0,
        'descrizione' => $_POST['descrizione'],
        'prezzo' => $_POST['prezzo']
    ]);
    $_SESSION['flash'] = ['tipo' => 'success', 'testo' => 'Secondo modificato con successo.'];
    header("Location: modifica_secondi.php");
    exit();
}

$stmt = $pdo->query("SELECT * FROM secondo ORDER BY nome ASC");
$secondi = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <title>Gestione Secondi</title>
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
                    <h1 class="mb-3">Gestione Secondi</h1>
                    <p class="breadcrumbs">
                        <a href="../index.php" class="text-white">Home</a>
                        <span class="mx-2 text-white">&gt;</span>
                        <a href="../admin.php" class="text-white">Admin</a>
                        <span class="mx-2 text-white">&gt;</span>
                        <span>Secondi</span>
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
            <div class="row justify-content-center mb-4">
                <div class="col-md-8">
                    <div class="p-4 p-md-5 rounded bg-dark text-white">
                        <h4>Aggiungi nuovo secondo</h4>
                        <form method="POST">
                            <input type="hidden" name="azione" value="aggiungi">
                            <div class="form-group">
                                <label>Nome</label>
                                <input type="text" name="nome" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Descrizione</label>
                                <input type="text" name="descrizione" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Prezzo</label>
                                <input type="number" name="prezzo" step="0.01" class="form-control" required>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="disponibile" class="form-check-input" checked>
                                <label class="form-check-label">Disponibile</label>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Aggiungi</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <h4>Lista Secondi</h4>
                    <div class="table-responsive">
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
        <?php foreach ($secondi as $secondo): ?>
        <tr>
            <form method="POST" class="form-inline">
                <input type="hidden" name="azione" value="modifica">
                <input type="hidden" name="nome" value="<?= htmlspecialchars($secondo['nome']) ?>">
                <td><?= htmlspecialchars($secondo['nome']) ?></td>
                <td><input type="text" name="descrizione" value="<?= htmlspecialchars($secondo['descrizione']) ?>" class="form-control form-control-sm"></td>
                <td><input type="number" name="prezzo" step="0.01" value="<?= $secondo['prezzo'] ?>" class="form-control form-control-sm"></td>
                <td><input type="checkbox" name="disponibile" <?= $secondo['disponibile'] ? 'checked' : '' ?>></td>
                <td>
                    <button type="submit" class="btn btn-sm btn-success">Salva</button>
            </form>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="azione" value="elimina">
                <input type="hidden" name="nome" value="<?= htmlspecialchars($secondo['nome']) ?>">
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Eliminare il secondo?')">Elimina</button>
            </form>
                </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($secondi)): ?>
        <tr><td colspan="5" class="text-center text-muted" style="color:white !important;">Nessun secondo presente.</td></tr>
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