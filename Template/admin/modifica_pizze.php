<?php
session_start();
require_once(dirname(__DIR__) . '/config.php');

// Accesso solo admin
if (!isset($_SESSION['user']) || $_SESSION['user']['gruppo'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Flash message
$flash = $_SESSION['flash_message'] ?? null;
unset($_SESSION['flash_message']);

// Aggiungi pizza
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $nome = trim($_POST['nome']);
    $descrizione = trim($_POST['descrizione']);
    $prezzo = floatval($_POST['prezzo']);
    $disponibile = isset($_POST['disponibile']) ? 1 : 0;
    $tempo_cottura = intval($_POST['tempo_cottura']);
    $ingredienti = $_POST['ingredienti'] ?? [];

    if (!empty($nome) && $prezzo >= 0 && $tempo_cottura >= 0) {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO pizza (nome, descrizione, prezzo, disponibile, tempo_cottura) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nome, $descrizione, $prezzo, $disponibile, $tempo_cottura]);

            $stmtIng = $pdo->prepare("INSERT INTO ingredienti_pizze (nome_pizza, nome_ingrediente) VALUES (?, ?)");
            foreach ($ingredienti as $ingrediente) {
                $stmtIng->execute([$nome, $ingrediente]);
            }

            $pdo->commit();
            $_SESSION['flash_message'] = "Pizza creata con successo.";
        } catch (PDOException $e) {
            $pdo->rollBack();
            $_SESSION['flash_message'] = "Errore nella creazione della pizza.";
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Modifica pizza
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $nome = $_POST['nome'];
    $descrizione = trim($_POST['descrizione']);
    $prezzo = floatval($_POST['prezzo']);
    $disponibile = isset($_POST['disponibile']) ? 1 : 0;
    $tempo_cottura = intval($_POST['tempo_cottura']);
    $ingredienti = $_POST['ingredienti'] ?? [];

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("UPDATE pizza SET descrizione=?, prezzo=?, disponibile=?, tempo_cottura=? WHERE nome=?");
        $stmt->execute([$descrizione, $prezzo, $disponibile, $tempo_cottura, $nome]);

        // elimina ingredienti vecchi
        $pdo->prepare("DELETE FROM ingredienti_pizze WHERE nome_pizza=?")->execute([$nome]);

        // aggiungi nuovi ingredienti
        $stmtIng = $pdo->prepare("INSERT INTO ingredienti_pizze (nome_pizza, nome_ingrediente) VALUES (?, ?)");
        foreach ($ingredienti as $ingrediente) {
            $stmtIng->execute([$nome, $ingrediente]);
        }

        $pdo->commit();
        $_SESSION['flash_message'] = "Pizza aggiornata.";
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['flash_message'] = "Errore durante l'aggiornamento.";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Elimina pizza
if (isset($_GET['delete'])) {
    $nome = $_GET['delete'];
    try {
        $pdo->beginTransaction();
        $pdo->prepare("DELETE FROM ingredienti_pizze WHERE nome_pizza=?")->execute([$nome]);
        $pdo->prepare("DELETE FROM pizza WHERE nome=?")->execute([$nome]);
        $pdo->commit();
        $_SESSION['flash_message'] = "Pizza eliminata.";
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['flash_message'] = "Errore durante l'eliminazione.";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Ingredienti e pizze
$ingredienti = $pdo->query("SELECT nome FROM ingrediente")->fetchAll(PDO::FETCH_COLUMN);
$pizze = $pdo->query("SELECT * FROM pizza ORDER BY nome")->fetchAll();

function getIngredientiPizza($pdo, $nome_pizza)
{
    $stmt = $pdo->prepare("SELECT nome_ingrediente FROM ingredienti_pizze WHERE nome_pizza = ?");
    $stmt->execute([$nome_pizza]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <style>
        table td,
        table th {
            padding: 1rem !important;
            /* aumenta lo spazio interno */
        }
    </style>
    <title>Gestione Pizze</title>
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

    <!-- HERO -->
    <section class="slider-item" style="background-image: url('../images/bg_3.jpg'); min-height: 300px; position: relative;">
        <div class="overlay" style="background: rgba(0,0,0,0.5); position:absolute; top:0; left:0; right:0; bottom:0;"></div>
        <div class="container" style="position: relative; z-index: 2;">
            <div class="row justify-content-center align-items-center" style="min-height: 300px;">
                <div class="col-md-8 text-center text-white">
                    <h1 class="mb-3">Gestione Pizze</h1>
                    <p class="breadcrumbs">
                        <a href="../index.php" class="text-white">Home</a>
                        <span class="mx-2 text-white">&gt;</span>
                        <a href="../admin.php" class="text-white">Admin</a>
                        <span class="mx-2 text-white">&gt;</span>
                        <span>Pizze</span>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section">
        <div class="container">
            <?php if (!empty($flash)): ?>
                <div class="alert alert-info text-center"><?= htmlspecialchars($flash) ?></div>
            <?php endif; ?>

            <!-- FORM AGGIUNTA PIZZA -->
            <div class="row justify-content-center mb-4">
                <div class="col-md-10">
                    <div class="card p-4 shadow bg-dark text-white">
                        <h4 class="mb-3">Crea Nuova Pizza</h4>
                        <form method="POST">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Nome</label>
                                    <input autocomplete="off" type="text" name="nome" class="form-control" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Prezzo (€)</label>
                                    <input autocomplete="off"  type="number" step="0.01" name="prezzo" class="form-control" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Tempo Cottura (min)</label>
                                    <input autocomplete="off" type="number" name="tempo_cottura" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Descrizione</label>
                                <textarea autocomplete="off" name="descrizione" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Ingredienti</label><br>
                                <?php foreach ($ingredienti as $ing): ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="ingredienti[]" id="ing_<?= $ing ?>" value="<?= $ing ?>">
                                        <label class="form-check-label" for="ing_<?= $ing ?>"><?= htmlspecialchars($ing) ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="form-group form-check">
                                <input type="checkbox" name="disponibile" class="form-check-input" id="disponibileNew" checked>
                                <label class="form-check-label" for="disponibileNew">Disponibile</label>
                            </div>
                            <button type="submit" name="add" class="btn btn-primary">Crea Pizza</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ELENCO PIZZE -->
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <h4 class="text-white mb-3">Pizze Esistenti</h4>
                    <table class="table table-dark table-bordered table-striped w-100">
                        <thead>
                            <tr>
                                <th style="width: 10%;">Nome</th>
                                <th style="width: 30%;">Descrizione</th>
                                <th style="width: 10%;">Prezzo</th>
                                <th style="width: 10%;">Disponibile</th>
                                <th style="width: 10%;">Tempo</th>
                                <th style="width: 20%;">Ingredienti</th>
                                <th style="width: 10%;">Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pizze as $pizza):
                                $ingPizza = getIngredientiPizza($pdo, $pizza['nome']);
                            ?>
                                <tr>
                                    <form method="POST">
                                        <td><input  autocomplete="off" type="text" name="nome" class="form-control-plaintext text-white" value="<?= htmlspecialchars($pizza['nome']) ?>" readonly></td>
                                        <td><textarea  autocomplete="off" name="descrizione" class="form-control"><?= htmlspecialchars($pizza['descrizione']) ?></textarea></td>
                                        <td><input  autocomplete="off" type="number" step="0.01" name="prezzo" class="form-control" value="<?= $pizza['prezzo'] ?>"></td>
                                        <td>
                                            <input  autocomplete="off" type="checkbox" name="disponibile" <?= $pizza['disponibile'] ? 'checked' : '' ?>>
                                        </td>
                                        <td><input  autocomplete="off" type="number" name="tempo_cottura" class="form-control" value="<?= $pizza['tempo_cottura'] ?>"></td>
                                        <td class="position-relative">
                                            <div class="dropdown">
                                                <span class="btn btn-secondary dropdown-toggle" onmouseover="this.click();" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Ingredienti
                                                </span>
                                                <div class="dropdown-menu p-3" style="max-height: 300px; overflow-y: auto; background-color: #343a40 !important; color:white;">
                                                    <?php foreach ($ingredienti as $ing): ?>
                                                        <div class="form-check">
                                                            <input class="form-check-input"
                                                                type="checkbox"
                                                                name="ingredienti[]"
                                                                id="chk_<?= $pizza['nome'] ?>_<?= $ing ?>"
                                                                value="<?= $ing ?>"
                                                                <?= in_array($ing, $ingPizza) ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="chk_<?= $pizza['nome'] ?>_<?= $ing ?>">
                                                                <?= htmlspecialchars($ing) ?>
                                                            </label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="submit" name="update" class="btn btn-success btn-sm">Salva</button>
                                            <a href="?delete=<?= urlencode($pizza['nome']) ?>" onclick="return confirm('Eliminare questa pizza?');" class="btn btn-danger btn-sm">Elimina</a>
                                        </td>
                                    </form>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($pizza)): ?>
                            <tr><td colspan="7" class="text-center text-muted" style="color:white !important;">Nessuna pizza presente.</td></tr>
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