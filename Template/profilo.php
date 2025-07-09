<?php
session_start();
require_once 'config.php'; // Modifica il percorso in base alla posizione del file

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$utente_email = $_SESSION['user']['email'];
$errors = [];
$success = false;

// Recupera i dati aggiornati dal DB usando l'email come identificatore
$stmt = $pdo->prepare("SELECT * FROM utente WHERE email = :email");
$stmt->execute(['email' => $utente_email]);
$utente = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function emailToFilename($email, $extension)
    {
        // Rende l'email un nome file valido
        $base = strtolower(preg_replace('/[^a-z0-9]/i', '_', $email));
        return "images/profilo/" . $base . "." . $extension;
    }
    // Gestione upload foto profilo
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($ext, $allowed)) {
            $nome_file = emailToFilename($utente_email, $ext);

            // Elimina eventuale immagine precedente se esiste e ha estensione diversa
            foreach ($allowed as $formato) {
                $candidato = emailToFilename($utente_email, $formato);
                if (file_exists($candidato) && $candidato !== $nome_file) {
                    unlink($candidato);
                }
            }

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $nome_file)) {
                $stmtFoto = $pdo->prepare("UPDATE utente SET foto_profilo = :foto WHERE email = :email");
                $stmtFoto->execute([
                    'foto' => $nome_file,
                    'email' => $utente_email
                ]);
                $success = true;
            } else {
                $errors[] = "Errore durante il salvataggio dell'immagine.";
            }
        } else {
            $errors[] = "Formato immagine non valido (consentiti: JPG, PNG, GIF, WEBP).";
        }
    }

    // L'email non è modificabile, quindi recuperiamo solo i campi modificabili
    $nickname = trim($_POST["nickname"] ?? '');
    $nome = trim($_POST["nome"] ?? '');
    $cognome = trim($_POST["cognome"] ?? '');
    $bio = trim($_POST["bio"] ?? '');

    // Puoi aggiungere ulteriori controlli (es. validazione del nickname, ecc.)
    if ($nickname === '' || $nome === '' || $cognome === '') {
        $errors[] = "Nickname, Nome e Cognome sono obbligatori.";
    } else {
        $update = $pdo->prepare("UPDATE utente 
          SET nickname = :nickname, nome = :nome, cognome = :cognome, bio = :bio 
          WHERE email = :email");
        $update->execute([
            'nickname' => $nickname,
            'nome'     => $nome,
            'cognome'  => $cognome,
            'bio'      => $bio,
            'email'    => $utente_email
        ]);
        $success = true;

        // Aggiorna la sessione per riflettere le modifiche
        $_SESSION['user']['nickname'] = $nickname;
        $_SESSION['user']['nome'] = $nome;
        $_SESSION['user']['cognome'] = $cognome;

        // Ricarica i dati aggiornati dal DB
        $stmt = $pdo->prepare("SELECT * FROM utente WHERE email = :email");
        $stmt->execute(['email' => $utente_email]);
        $utente = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <title>Profilo Utente</title>
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
</head>

<body>

    <?php $pagina_attiva = ''; ?>
    <?php include 'header.php'; ?>

    <div class="container mt-5">
        <div class="form-profilo">
            <h2 class="text-center mb-4" style="color: #212529 !important;">Il tuo profilo</h2>

            <div class="text-center mb-4">
                <?php if (!empty($utente['foto_profilo']) && file_exists($utente['foto_profilo'])): ?>
                    <?php
                    $src = $utente['foto_profilo'] ?: 'images/profilo/default.jpg';
                    ?>
                    <img src="<?= $src ?>?v=<?= time() ?>" alt="Foto Profilo" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #ffc107;">
                <?php else: ?>
                    <img src="images/profilo/default.png" alt="Foto profilo" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #ffc107;">
                <?php endif; ?>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success">Profilo aggiornato con successo!</div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <div class="container mt-5">
                <form method="post" enctype="multipart/form-data">
                    <!-- Campo Email: solo visualizzazione -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email: <?= htmlspecialchars($utente['email']) ?></label>
                    </div>

                    <!-- Campo Nickname: modificabile -->
                    <div class="mb-3">
                        <label for="nickname" class="form-label">Nickname</label>
                        <input type="text" name="nickname" class="form-control" id="nickname"
                            value="<?= htmlspecialchars($utente['nickname']) ?>" required
                            style="border:1px solid !important;background-color: #e9ecef; color: #212529 !important;">
                    </div>

                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" name="nome" id="nome" class="form-control"
                            value="<?= htmlspecialchars($utente['nome']) ?>" required
                            style="border:1px solid !important;background-color: #e9ecef; color: #212529 !important;">
                    </div>

                    <div class="mb-3">
                        <label for="cognome" class="form-label">Cognome</label>
                        <input type="text" name="cognome" id="cognome" class="form-control"
                            value="<?= htmlspecialchars($utente['cognome']) ?>" required
                            style="border:1px solid !important;background-color: #e9ecef; color: #212529 !important;">
                    </div>

                    <div class="mb-3">
                        <label for="bio" class="form-label">Bio</label>
                        <textarea name="bio" id="bio" class="form-control" rows="3" style="border:1px solid !important;background-color: #e9ecef; color: #212529 !important;"><?= htmlspecialchars($utente['bio']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="foto" class="form-label d-block">Modifica foto profilo</label>

                        <!-- Label che funge da pulsante -->
                        <label for="foto" class="btn btn-custom-warning text-center" style="width: 200px;">
                            Scegli una nuova foto
                        </label>

                        <!-- Input nascosto -->
                        <input type="file" name="foto" id="foto" accept="image/*" style="display: none;" onchange="mostraNomeFile(this)">

                        <!-- Spazio per mostrare il nome del file -->
                        <div id="nome-file" class="mt-2 text-muted" style="font-size: 0.9rem;"></div>
                    </div>

                    <button type="submit" class="btn-custom-warning">Aggiorna Profilo</button>
                </form>
            </div>
            <div class="text-center" style="margin-top: 15px !important;">
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-migrate-3.0.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <script>
        function mostraNomeFile(input) {
            const file = input.files[0];
            const nomeDiv = document.getElementById("nome-file");
            if (file) {
                nomeDiv.textContent = "File selezionato: " + file.name;
            } else {
                nomeDiv.textContent = "";
            }
        }
    </script>

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

</html>