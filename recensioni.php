<?php
session_start();
require_once("config.php");

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$utente = $_SESSION['user'] ?? null;

// Gestione invio recensione
if ($utente && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['invia_recensione'])) {
    $stelle = (int)$_POST['stelle'];
    $commento = trim($_POST['commento']);
    $foto = null;

    // Validazione stelle
    if ($stelle < 1 || $stelle > 5) $stelle = 5;

    // Salvataggio immagine se caricata
    if (!empty($_FILES['foto']['name'])) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $filename = uniqid('rec_') . '.' . $ext;
        $dest = "images/recensioni/$filename";
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $dest)) {
            $foto = $filename;
        }
    }

    // Controlla se l'utente ha già una recensione associata
    $stmt = $pdo->prepare("SELECT recensione FROM utente WHERE email = :email");
    $stmt->execute(['email' => $utente['email']]);
    $rec_id = $stmt->fetchColumn();

    if ($rec_id) {
        // Recupera il nome file foto della vecchia recensione
        $stmt = $pdo->prepare("SELECT foto FROM recensione WHERE id = :id");
        $stmt->execute(['id' => $rec_id]);
        $foto_vecchia = $stmt->fetchColumn();

        // Elimina la recensione dal DB
        $pdo->prepare("DELETE FROM recensione WHERE id = :id")->execute(['id' => $rec_id]);

        // Elimina la foto dal filesystem, se esiste
        if ($foto_vecchia && file_exists("images/recensioni/$foto_vecchia")) {
            unlink("images/recensioni/$foto_vecchia");
        }
    }

    // Inserimento recensione
    $stmt = $pdo->prepare("INSERT INTO recensione (stelle, commento, data, foto) VALUES (:stelle, :commento, NOW(), :foto)");
    $stmt->execute([
        'stelle' => $stelle,
        'commento' => $commento,
        'foto' => $foto
    ]);
    $rec_id = $pdo->lastInsertId();

    // Associa la recensione all'utente
    $stmt = $pdo->prepare("UPDATE utente SET recensione = :recensione WHERE email = :email");
    $stmt->execute([
        'recensione' => $rec_id,
        'email' => $utente['email']
    ]);

    $_SESSION['flash'] = ['tipo' => 'success', 'testo' => 'Recensione inviata con successo.'];
    header("Location: recensioni.php");
    exit();
}

if ($utente && isset($_POST['elimina_recensione'])) {
    $rec_id = (int)$_POST['recensione_id'];

    // 1. Recupera la foto associata alla recensione
    $stmt = $pdo->prepare("SELECT foto FROM recensione WHERE id = :id");
    $stmt->execute(['id' => $rec_id]);
    $foto = $stmt->fetchColumn();

    // 2. Elimina la recensione dal DB
    $pdo->prepare("DELETE FROM recensione WHERE id = :id")->execute(['id' => $rec_id]);

    // 3. Scollega l'utente dalla recensione
    $pdo->prepare("UPDATE utente SET recensione = NULL WHERE email = :email")
        ->execute(['email' => $utente['email']]);

    // 4. Elimina la foto dal filesystem, se presente
    if ($foto) {
        $nome_file = basename($foto); // in caso ci sia il path nel DB
        $path = "images/recensioni/" . $nome_file;
        if (file_exists($path)) {
            unlink($path);
        }
    }

    $_SESSION['flash'] = ['tipo' => 'success', 'testo' => 'Recensione eliminata.'];
    header("Location: recensioni.php");
    exit();
}


$recensione_utente = null;
$altre_recensioni = [];

// Recensione dell’utente loggato
if ($utente) {
    $stmt = $pdo->prepare("SELECT r.*, u.nickname AS autore_nome, u.foto_profilo AS autore_foto
                           FROM recensione r JOIN utente u ON u.recensione = r.id
                           WHERE u.email = :email");
    $stmt->execute(['email' => $utente['email']]);
    $recensione_utente = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Tutte le altre recensioni
$stmt = $pdo->prepare("SELECT r.*, u.nickname AS autore_nome, u.foto_profilo AS autore_foto
                       FROM recensione r JOIN utente u ON u.recensione = r.id
                       WHERE u.email != :email
                       ORDER BY r.data DESC");
$stmt->execute(['email' => $utente['email'] ?? '']);
$altre_recensioni = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <title>Recensioni</title>
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
    <link rel="stylesheet" href="css/stile_personalizzato.css">
    <style>
        .review-card p {
            word-break: break-word;
            overflow-wrap: break-word;
        }



        .review-card {
            background: #212529;
            color: white;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-radius: 10px;
        }

        .review-photo {
            max-height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }

        .profile-pic {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }

        .star {
            color: gold;
        }
    </style>
</head>

<body>
    <?php $pagina_attiva = 'recensioni'; ?>
    <?php include 'header.php'; ?>

    <section class="slider-item" style="background-image: url('images/bg_3.jpg'); min-height: 300px; position: relative;">
    <div class="overlay" style="background: rgba(0,0,0,0.5); position:absolute; top:0; left:0; right:0; bottom:0;"></div>
    <div class="container" style="position: relative; z-index: 2;">
        <div class="row justify-content-center align-items-center" style="min-height: 300px;">
            <div class="col-md-8 text-center text-white">
                <h1 class="mb-3">Cosa dicono di noi</h1>
                <p class="breadcrumbs"><a href="index.php" class="text-white">Home</a> <span class="mx-2 text-white">&gt;</span> <span>Recensioni</span></p>
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

            <!-- Form nuova recensione -->
            <?php if ($utente): ?>
                <div class="row justify-content-center mb-5">
                    <div class="col-md-10">
                        <div class="bg-dark p-4 rounded">
                            <h4 class="text-white mb-3">Lascia una recensione</h4>
                            <form method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label class="text-white">Stelle</label>
                                    <select style="background-color: #343a40 !important; color:white !important;" name="stelle" class="form-control" required>
                                        <?php for ($i = 5; $i >= 1; $i--): ?>
                                            <option value="<?= $i ?>"><?= $i ?> ⭐</option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label autocomplete="off" class="text-white">Commento</label>
                                    <textarea name="commento" class="form-control" rows="3" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="text-white d-block">Foto (opzionale)</label>
                                    <label class="btn btn-primary">
                                        Seleziona file
                                        <input type="file" name="foto" accept="image/*" hidden onchange="updateFileName(this)">
                                    </label>
                                    <span id="file-name" class="ml-2 text-light"></span>
                                </div>
                                <button type="submit" name="invia_recensione" class="btn btn-primary">Invia Recensione</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($recensione_utente): ?>
                <div class="mb-5">
                    <h4 class="text-white">La tua recensione</h4>
                    <div class="review-card">
                        <div class="d-flex align-items-center mb-2">
                            <img src="<?= htmlspecialchars($recensione_utente['autore_foto']) ?>" class="profile-pic mr-2">
                            <strong><?= htmlspecialchars($recensione_utente['autore_nome']) ?></strong>
                            <span class="ml-3 text-muted"><?= date('d/m/Y H:i', strtotime($recensione_utente['data'])) ?></span>
                        </div>
                        <div class="mb-2">
                            <?php for ($i = 0; $i < $recensione_utente['stelle']; $i++): ?>
                                <span class="star">★</span>
                            <?php endfor; ?>
                            <?php for ($i = $recensione_utente['stelle']; $i < 5; $i++): ?>
                                <span class="text-secondary">★</span>
                            <?php endfor; ?>
                        </div>
                        <p><?= nl2br(htmlspecialchars($recensione_utente['commento'])) ?></p>
                        <?php if ($recensione_utente['foto']): ?>
                            <img src="images/recensioni/<?= htmlspecialchars($recensione_utente['foto']) ?>" class="review-photo mt-2">
                        <?php endif; ?>
                        <form method="POST" class="mt-3">
                            <input type="hidden" name="recensione_id" value="<?= $recensione_utente['id'] ?>">
                            <button type="submit" name="elimina_recensione" class="btn btn-danger btn-sm">Elimina la tua recensione</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>


            <!-- Lista recensioni -->
            <h4 class="text-white mt-4">Recensioni degli altri utenti</h4>
            <?php foreach ($altre_recensioni as $r): ?>
                <div class="review-card">
                    <div class="d-flex align-items-center mb-2">
                        <img src="<?= htmlspecialchars($r['autore_foto']) ?>" class="profile-pic mr-2">
                        <strong><?= htmlspecialchars($r['autore_nome']) ?></strong>
                        <span class="ml-3 text-muted"><?= date('d/m/Y H:i', strtotime($r['data'])) ?></span>
                    </div>
                    <div class="mb-2">
                        <?php for ($i = 0; $i < $r['stelle']; $i++): ?>
                            <span class="star">★</span>
                        <?php endfor; ?>
                        <?php for ($i = $r['stelle']; $i < 5; $i++): ?>
                            <span class="text-secondary">★</span>
                        <?php endfor; ?>
                    </div>
                    <p><?= nl2br(htmlspecialchars($r['commento'])) ?></p>
                    <?php if ($r['foto']): ?>
                        <img src="images/recensioni/<?= htmlspecialchars($r['foto']) ?>" class="review-photo mt-2">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <?php if (empty($altre_recensioni)): ?>
                <p class="text-white text-center">Nessun'altra recensione presente.</p>
            <?php endif; ?>


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
<script src="js/jquery.animateNumber.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/jquery.timepicker.min.js"></script>
<script src="js/scrollax.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
<script src="js/google-map.js"></script>
<script src="js/main.js"></script>
<script>
    function updateFileName(input) {
        const fileName = input.files[0]?.name || '';
        document.getElementById('file-name').textContent = fileName;
    }
</script>


</html>