<?php
session_start();
require_once("../config.php");

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

if (!isset($_SESSION['user']) || ($_SESSION['user']['gruppo'] ?? '') !== 'admin') {
    echo "Accesso riservato solo agli amministratori.";
    exit();
}

// Aggiunta privilegio
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['azione'] === 'aggiungi') {
    try {
        $stmt = $pdo->prepare("INSERT INTO privilegio (nome, descrizione) VALUES (:nome, :descrizione)");
        $stmt->execute([
            'nome' => $_POST['nome'],
            'descrizione' => $_POST['descrizione']
        ]);
        $_SESSION['flash'] = ['tipo' => 'success', 'testo' => 'Privilegio aggiunto con successo.'];
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'testo' => 'Errore: esiste già un privilegio con questo nome.'];
        } else {
            $_SESSION['flash'] = ['tipo' => 'danger', 'testo' => 'Errore imprevisto: ' . $e->getMessage()];
        }
    }
    header("Location: modifica_privilegi.php");
    exit();
}

// Modifica privilegio
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['azione'] === 'modifica') {
    $stmt = $pdo->prepare("UPDATE privilegio SET descrizione = :descrizione WHERE nome = :nome");
    $stmt->execute([
        'nome' => $_POST['nome'],
        'descrizione' => $_POST['descrizione']
    ]);
    $_SESSION['flash'] = ['tipo' => 'success', 'testo' => 'Privilegio modificato con successo.'];
    header("Location: modifica_privilegi.php");
    exit();
}

// Eliminazione privilegio
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['azione'] === 'elimina') {
    $pdo->prepare("DELETE FROM privilegi_gruppo WHERE nome_privilegio = :nome")->execute(['nome' => $_POST['nome']]);
    $pdo->prepare("DELETE FROM privilegio WHERE nome = :nome")->execute(['nome' => $_POST['nome']]);
    $_SESSION['flash'] = ['tipo' => 'warning', 'testo' => 'Privilegio eliminato.'];
    header("Location: modifica_privilegi.php");
    exit();
}

// Lettura privilegi
$stmt = $pdo->query("SELECT * FROM privilegio ORDER BY nome ASC");
$privilegi = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <title>Gestione Privilegi</title>
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
                    <h1 class="mb-3">Gestione Privilegi</h1>
                    <p class="breadcrumbs">
                        <a href="../index.php" class="text-white">Home</a>
                        <span class="mx-2 text-white">&gt;</span>
                        <a href="../admin.php" class="text-white">Admin</a>
                        <span class="mx-2 text-white">&gt;</span>
                        <span>Privilegi</span>
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

        <!-- Form aggiunta -->
        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
                <div class="bg-dark p-4 rounded">
                    <form method="POST">
                        <input autocomplete="off" type="hidden" name="azione" value="aggiungi">
                        <div class="form-group">
                            <label style="color:white;">Nome privilegio</label>
                            <input autocomplete="off" type="text" name="nome" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label style="color:white;">Descrizione</label>
                            <input autocomplete="off" type="text" name="descrizione" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Aggiungi Privilegio</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabella privilegi -->
        <div class="row">
            <div class="col-12">
                <table class="table table-bordered table-striped bg-white" style="background-color: #343a40 !important; color:white !important;">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nome</th>
                            <th>Descrizione</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($privilegi as $p): ?>
                        <tr>
                            <form method="POST">
                                <input type="hidden" name="azione" value="modifica">
                                <input type="hidden" name="nome" value="<?= htmlspecialchars($p['nome']) ?>">
                                <td><input autocomplete="off" type="text" name="nome" value="<?= htmlspecialchars($p['nome']) ?>" class="form-control-plaintext text-white" readonly></td>
                                <td><input autocomplete="off" type="text" name="descrizione" value="<?= htmlspecialchars($p['descrizione']) ?>" class="form-control form-control-sm"></td>
                                <td>
                                    <button type="submit" class="btn btn-sm btn-success">Salva</button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="azione" value="elimina">
                                <input type="hidden" name="nome" value="<?= htmlspecialchars($p['nome']) ?>">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Eliminare il privilegio?')">Elimina</button>
                            </form>
                                </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($privilegi)): ?>
                        <tr><td colspan="3" class="text-center text-muted" style="color:white !important;">Nessun privilegio presente.</td></tr>
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
