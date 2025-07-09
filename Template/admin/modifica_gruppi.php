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

// Carica tutti i privilegi disponibili
$stmt = $pdo->query("SELECT nome, descrizione FROM privilegio ORDER BY nome ASC");
$privilegi_disponibili = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Aggiunta gruppo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['azione'] === 'aggiungi') {
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO gruppo (nome, descrizione) VALUES (:nome, :descrizione)");
        $stmt->execute([
            'nome' => $_POST['nome'],
            'descrizione' => $_POST['descrizione']
        ]);

        if (!empty($_POST['privilegi'])) {
            $stmtPriv = $pdo->prepare("INSERT INTO privilegi_gruppo (nome_gruppo, nome_privilegio) VALUES (:gruppo, :privilegio)");
            foreach ($_POST['privilegi'] as $priv) {
                $stmtPriv->execute([
                    'gruppo' => $_POST['nome'],
                    'privilegio' => $priv
                ]);
            }
        }

        $pdo->commit();
        $_SESSION['flash'] = ['tipo' => 'success', 'testo' => 'Gruppo aggiunto con successo.'];
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['flash'] = ['tipo' => 'danger', 'testo' => 'Errore durante l\'aggiunta: ' . $e->getMessage()];
    }

    header("Location: modifica_gruppi.php");
    exit();
}

// Modifica gruppo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['azione'] === 'modifica') {
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("UPDATE gruppo SET descrizione = :descrizione WHERE nome = :nome");
        $stmt->execute([
            'nome' => $_POST['nome'],
            'descrizione' => $_POST['descrizione']
        ]);

        $pdo->prepare("DELETE FROM privilegi_gruppo WHERE nome_gruppo = :nome")
            ->execute(['nome' => $_POST['nome']]);

        if (!empty($_POST['privilegi'])) {
            $stmtPriv = $pdo->prepare("INSERT INTO privilegi_gruppo (nome_gruppo, nome_privilegio) VALUES (:gruppo, :privilegio)");
            foreach ($_POST['privilegi'] as $priv) {
                $stmtPriv->execute([
                    'gruppo' => $_POST['nome'],
                    'privilegio' => $priv
                ]);
            }
        }

        $pdo->commit();
        $_SESSION['flash'] = ['tipo' => 'success', 'testo' => 'Gruppo modificato con successo.'];
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['flash'] = ['tipo' => 'danger', 'testo' => 'Errore durante la modifica: ' . $e->getMessage()];
    }

    header("Location: modifica_gruppi.php");
    exit();
}

// Eliminazione gruppo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['azione'] === 'elimina') {
    $pdo->prepare("DELETE FROM privilegi_gruppo WHERE nome_gruppo = :nome")->execute(['nome' => $_POST['nome']]);
    $pdo->prepare("DELETE FROM gruppo WHERE nome = :nome")->execute(['nome' => $_POST['nome']]);
    $_SESSION['flash'] = ['tipo' => 'warning', 'testo' => 'Gruppo eliminato.'];
    header("Location: modifica_gruppi.php");
    exit();
}

// Carica tutti i gruppi
$stmt = $pdo->query("SELECT * FROM gruppo ORDER BY nome ASC");
$gruppi = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <title>Gestione Gruppi</title>
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
                    <h1 class="mb-3">Gestione Gruppi</h1>
                    <p class="breadcrumbs">
                        <a href="../index.php" class="text-white">Home</a>
                        <span class="mx-2 text-white">&gt;</span>
                        <a href="../admin.php" class="text-white">Admin</a>
                        <span class="mx-2 text-white">&gt;</span>
                        <span>Gruppi</span>
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

        <!-- Form Aggiunta -->
        <div class="row justify-content-center mb-5">
            <div class="col-md-10">
                <div class="bg-dark p-4 rounded">
                    <form method="POST">
                        <input type="hidden" name="azione" value="aggiungi">
                        <div class="form-group">
                            <label style="color:white;">Nome del gruppo</label>
                            <input autocomplete="off" type="text" name="nome" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label style="color:white;">Descrizione</label>
                            <input autocomplete="off" type="text" name="descrizione" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label style="color:white;">Privilegi associati</label><br>
                            <?php foreach ($privilegi_disponibili as $p): ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="privilegi[]" value="<?= $p['nome'] ?>" id="priv_<?= $p['nome'] ?>">
                                    <label class="form-check-label text-light" for="priv_<?= $p['nome'] ?>"><?= htmlspecialchars($p['nome']) ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="submit" class="btn btn-primary">Aggiungi Gruppo</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabella gruppi -->
        <div class="row">
            <div class="col-12">
                <table class="table table-bordered table-striped bg-white" style="background-color: #343a40 !important; color:white !important;">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nome</th>
                            <th>Descrizione</th>
                            <th>Privilegi</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($gruppi as $gruppo): ?>
                            <?php
                                $stmt = $pdo->prepare("SELECT nome_privilegio FROM privilegi_gruppo WHERE nome_gruppo = ?");
                                $stmt->execute([$gruppo['nome']]);
                                $privilegi_attivi = $stmt->fetchAll(PDO::FETCH_COLUMN);
                            ?>
                            <tr>
                                <form method="POST">
                                    <input type="hidden" name="azione" value="modifica">
                                    <input type="hidden" name="nome" value="<?= htmlspecialchars($gruppo['nome']) ?>">
                                    <td><input autocomplete="off" type="text" name="nome" value="<?= htmlspecialchars($gruppo['nome']) ?>" class="form-control-plaintext text-white" readonly></td>
                                    <td><input autocomplete="off" type="text" name="descrizione" value="<?= htmlspecialchars($gruppo['descrizione']) ?>" class="form-control form-control-sm"></td>
                                    <td>
                                        <?php foreach ($privilegi_disponibili as $p): ?>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="privilegi[]" value="<?= $p['nome'] ?>" id="mod_<?= $gruppo['nome'] ?>_<?= $p['nome'] ?>"
                                                    <?= in_array($p['nome'], $privilegi_attivi) ? 'checked' : '' ?>>
                                                <label class="form-check-label text-light" for="mod_<?= $gruppo['nome'] ?>_<?= $p['nome'] ?>"><?= htmlspecialchars($p['nome']) ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-sm btn-success">Salva</button>
                                </form>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="azione" value="elimina">
                                    <input type="hidden" name="nome" value="<?= htmlspecialchars($gruppo['nome']) ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Eliminare il gruppo?')">Elimina</button>
                                </form>
                                    </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($gruppi)): ?>
                            <tr><td colspan="4" class="text-center text-muted" style="color:white !important;">Nessun gruppo presente.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?php include '../footer.php'; ?>
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/main.js"></script>
</body>
</html>

