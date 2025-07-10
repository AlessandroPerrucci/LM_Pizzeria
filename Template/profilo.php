<?php
session_start();
require_once 'config.php'; // Modifica il percorso in base alla posizione del file

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
$user = $_SESSION['user'];
$email = $user['email'];

// Verifica se l'utente ha -1 in str_preferenze
if ((int)$user['str_preferenze'] === -1) {
    $pdo->prepare("INSERT INTO str_preferenze (dark_mode, keep_logged, pub) VALUES (0, 0, 0)")->execute();
    $new_id = $pdo->lastInsertId();

    // aggiorna utente
    $pdo->prepare("UPDATE utente SET str_preferenze = :id WHERE email = :email")->execute([
        'id' => $new_id,
        'email' => $email
    ]);

    // aggiorna sessione
    $user['str_preferenze'] = $new_id;
    $_SESSION['user']['str_preferenze'] = $new_id;
}

// Recupera le pizze disponibili
$stmt = $pdo->query("SELECT nome FROM pizza WHERE disponibile = 1 ORDER BY nome ASC");
$pizze = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM str_preferenze WHERE id = :id");
$stmt->execute(['id' => $user['str_preferenze']]);
$preferenze = $stmt->fetch(PDO::FETCH_ASSOC);

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
        $favorite_pizza = trim($_POST['favorite_pizza'] ?? null);

        $update = $pdo->prepare("UPDATE utente 
  SET nickname = :nickname, nome = :nome, cognome = :cognome, bio = :bio, favorite_pizza = :favorite_pizza 
  WHERE email = :email");

        $update->execute([
            'nickname' => $nickname,
            'nome' => $nome,
            'cognome' => $cognome,
            'bio' => $bio,
            'favorite_pizza' => $favorite_pizza,
            'email' => $utente_email
        ]);
        $success = true;

        // Aggiorna la sessione per riflettere le modifiche
        $_SESSION['user']['nickname'] = $nickname;
        $_SESSION['user']['nome'] = $nome;
        $_SESSION['user']['cognome'] = $cognome;
        $_SESSION['user']['favorite_pizza'] = $favorite_pizza;


        // Ricarica i dati aggiornati dal DB
        $stmt = $pdo->prepare("SELECT * FROM utente WHERE email = :email");
        $stmt->execute(['email' => $utente_email]);
        $utente = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salva_preferenze'])) {
    $stmt = $pdo->prepare("
        UPDATE str_preferenze 
        SET dark_mode = :dark_mode, keep_logged = :keep_logged, pub = :pub 
        WHERE id = :id
    ");
    $stmt->execute([
        'dark_mode' => isset($_POST['dark_mode']) ? 1 : 0,
        'keep_logged' => isset($_POST['keep_logged']) ? 1 : 0,
        'pub' => isset($_POST['pub']) ? 1 : 0,
        'id' => $user['str_preferenze']
    ]);

    $_SESSION['flash'] = ['tipo' => 'success', 'testo' => 'Preferenze aggiornate con successo.'];
    header("Location: profilo.php");
    exit;
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

<body class="<?= $preferenze['dark_mode'] ? 'dark-mode' : '' ?>">

    <?php $pagina_attiva = ''; ?>
    <?php include 'header.php'; ?>

    <div class="container mt-5">
        <div class="form-profilo" style="<?= $preferenze['dark_mode'] ? 'color:rgb(255, 255, 255) !important; background-color: black !important;' : 'color: #212529 !important; background-color: white !important; ' ?>">
            <h2 class="text-center mb-4" style="<?= $preferenze['dark_mode'] ? 'color:rgb(255, 255, 255) !important;' : 'color: #212529 !important;' ?>">Il tuo profilo</h2>

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
                        <label for="email" class="form-label" style="<?= $preferenze['dark_mode'] ? 'color: #e9ecef !important;' : 'color: #212529 !important;' ?>">Email: <?= htmlspecialchars($utente['email']) ?></label>
                    </div>

                    <!-- Campo Nickname: modificabile -->
                    <div class="mb-3">
                        <label for="nickname" class="form-label" style="<?= $preferenze['dark_mode'] ? 'color: #e9ecef !important;' : 'color: #212529 !important;' ?>">Nickname</label>
                        <input type="text" name="nickname" class="form-control" id="nickname"
                            value="<?= htmlspecialchars($utente['nickname']) ?>" required
                            style="<?= $preferenze['dark_mode'] ? 'border:1px solid white !important; background-color: #212529; color: #e9ecef !important;' : 'border:1px solid black !important; background-color: #e9ecef; color: #212529 !important;' ?>">
                    </div>

                    <div class="mb-3">
                        <label for="nome" class="form-label" style="<?= $preferenze['dark_mode'] ? 'color: #e9ecef !important;' : 'color: #212529 !important;' ?>">Nome</label>
                        <input type="text" name="nome" id="nome" class="form-control"
                            value="<?= htmlspecialchars($utente['nome']) ?>" required
                            style="<?= $preferenze['dark_mode'] ? 'border:1px solid white !important; background-color: #212529; color: #e9ecef !important;' : 'border:1px solid black !important; background-color: #e9ecef; color: #212529 !important;' ?>">
                    </div>

                    <div class="mb-3">
                        <label for="cognome" class="form-label" style="<?= $preferenze['dark_mode'] ? 'color: #e9ecef !important;' : 'color: #212529 !important;' ?>">Cognome</label>
                        <input type="text" name="cognome" id="cognome" class="form-control"
                            value="<?= htmlspecialchars($utente['cognome']) ?>" required
                            style="<?= $preferenze['dark_mode'] ? 'border:1px solid white !important; background-color: #212529; color: #e9ecef !important;' : 'border:1px solid black !important; background-color: #e9ecef; color: #212529 !important;' ?>">
                    </div>
                    <!-- Scelta Pizza Preferita -->
                    <div class="mb-3">
                        <label for="favorite_pizza" class="form-label" style="<?= $preferenze['dark_mode'] ? 'color: #e9ecef !important;' : 'color: #212529 !important;' ?>">Pizza Preferita</label>
                        <select class="form-control" name="favorite_pizza" id="favorite_pizza" style="border-radius: 10px;<?= $preferenze['dark_mode'] ? 'border:1px solid white !important; background-color: #212529; color: #e9ecef !important;' : 'border:1px solid black !important; background-color: #e9ecef; color: #212529 !important;' ?>">
                            <option value="" style="<?= $preferenze['dark_mode'] ? 'border:1px solid white !important; background-color: #212529; color: #e9ecef !important;' : 'border:1px solid black !important; background-color: #e9ecef; color: #212529 !important;' ?>">-- Seleziona --</option>
                            <?php foreach ($pizze as $pizza): ?>
                                <option style="<?= $preferenze['dark_mode'] ? 'border:1px solid white !important; background-color: #212529; color: #e9ecef !important;' : 'border:1px solid black !important; background-color: #e9ecef; color: #212529 !important;' ?>" value="<?= htmlspecialchars($pizza['nome']) ?>" <?= $utente['favorite_pizza'] === $pizza['nome'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($pizza['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>



                    <div class="mb-3">
                        <label for="bio" class="form-label" style="<?= $preferenze['dark_mode'] ? 'color: #e9ecef !important;' : 'color: #212529 !important;' ?>">Bio</label>
                        <textarea name="bio" id="bio" class="form-control" rows="3" style="<?= $preferenze['dark_mode'] ? 'border:1px solid white !important; background-color: #212529; color: #e9ecef !important;' : 'border:1px solid black !important; background-color: #e9ecef; color: #212529 !important;' ?>"><?= htmlspecialchars($utente['bio']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="foto" class="form-label d-block" style="<?= $preferenze['dark_mode'] ? 'color: #e9ecef !important;' : 'color: #212529 !important;' ?>">Modifica foto profilo</label>

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
            <?php if (isset($preferenze)): ?>
                <div class="container mt-5" style="<?= $preferenze['dark_mode'] ? 'color:rgb(255, 255, 255) !important; background-color: black !important;' : 'color: #212529 !important; background-color: white !important; ' ?>">
                    <h4 class="mb-3" style="<?= $preferenze['dark_mode'] ? 'color: #e9ecef !important;' : 'color: #212529 !important;' ?>">Preferenze Profilo</h4>
                    <form method="POST">
                        <div class="form-check text-white">
                            <input class="form-check-input" type="checkbox" name="dark_mode" id="dark_mode" <?= $preferenze['dark_mode'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="dark_mode" style="<?= $preferenze['dark_mode'] ? 'color: #e9ecef !important;' : 'color: #212529 !important;' ?>">Modalità scura</label>
                        </div>
                        <button type="submit" name="salva_preferenze" class="btn-custom-warning mt-2">Salva Preferenze</button>
                    </form>
                </div>
            <?php endif; ?>

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
    <script>
        function selezionaPizza(nome) {
            document.getElementById('favorite_pizza').value = nome;
            document.getElementById('dropdownPizza').textContent = nome;
        }
    </script>
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