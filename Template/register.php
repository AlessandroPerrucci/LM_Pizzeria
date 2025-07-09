<?php
session_start();
require_once 'config.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"] ?? '');
    $nickname = trim($_POST["nickname"] ?? '');
    $password = trim($_POST["password"] ?? '');
    $conferma = trim($_POST["conferma"] ?? '');
    $nome = trim($_POST["nome"] ?? '');
    $cognome = trim($_POST["cognome"] ?? '');

    if ($email === '' || $nickname === '' || $password === '' || $conferma === '' || $nome === '' || $cognome === '') {
        $errors[] = "Tutti i campi sono obbligatori.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email non valida.";
    } elseif ($password !== $conferma) {
        $errors[] = "Le password non coincidono.";
    } else {
        $stmt = $pdo->prepare("SELECT email FROM utente WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            $errors[] = "Questa email è già registrata.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare("
  INSERT INTO utente (email, nickname, password, str_preferenze, nome, cognome, gruppo, foto_profilo)
  VALUES (:email, :nickname, :password, :str_preferenze, :nome, :cognome, :gruppo, :foto_profilo)
");

            $insert->execute([
                'email' => $email,
                'nickname' => $nickname,
                'password' => $hashed,
                'str_preferenze' => -1,
                'nome' => $nome,
                'cognome' => $cognome,
                'gruppo' => 'user',
                'foto_profilo' => 'images/profilo/default.jpg'
            ]);

            // Recupera l'utente appena creato per salvarlo in sessione
            $stmt = $pdo->prepare("SELECT * FROM utente WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            $_SESSION['user'] = $user;

            // Reindirizza al profilo
            header("Location: profilo.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <title>Registrazione</title>
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
            <h2 class="text-center mb-4" style="color: #212529;">Crea un nuovo account</h2>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control"
                        value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required
                        style="color: #212529 !important; background-color: #fff; border:1px solid !important;">
                </div>

                <div class="mb-3">
                    <label for="nickname" class="form-label">Nickname</label>
                    <input type="text" name="nickname" id="nickname" class="form-control"
                        value="<?= isset($nickname) ? htmlspecialchars($nickname) : '' ?>" required
                        style="color: #212529 !important; background-color: #fff; border:1px solid !important;">
                </div>

                <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" name="nome" id="nome" class="form-control"
                        value="<?= isset($nome) ? htmlspecialchars($nome) : '' ?>" required
                        style="color: #212529 !important; background-color: #fff; border:1px solid !important;">
                </div>

                <div class="mb-3">
                    <label for="cognome" class="form-label">Cognome</label>
                    <input type="text" name="cognome" id="cognome" class="form-control"
                        value="<?= isset($cognome) ? htmlspecialchars($cognome) : '' ?>" required
                        style="color: #212529 !important; background-color: #fff; border:1px solid !important;">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required
                        style="color: #212529 !important; background-color: #fff; border:1px solid !important;">
                </div>

                <div class="mb-3">
                    <label for="conferma" class="form-label">Conferma Password</label>
                    <input type="password" name="conferma" id="conferma" class="form-control" required
                        style="color: #212529 !important; background-color: #fff; border:1px solid !important;">
                </div>

                <button type="submit" class="btn-custom-warning" style="cursor: pointer !important;">Registrati</button>
            </form>

            <div class="text-center mt-3">
                <a href="login.php" class="btn btn-outline-secondary">Hai già un account? Accedi</a>
            </div>
        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

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