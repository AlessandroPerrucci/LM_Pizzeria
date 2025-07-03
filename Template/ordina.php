<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_name = $_SESSION['user'];
    $pizza_id = $_POST['pizza'] ?? null;
    $note = $_POST['note'] ?? '';

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO ordine_online (data_orario,stato, note) VALUES (NOW(),:stato, :note)");
        $stmt->execute([
            'stato' => 'in_preparazione',
            'note' => $note
        ]);

        $ordine_id = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO pizze_in_ordine (nome_pizza, id_ordine,num_pizze,aggiunte) VALUES (:nome_pizza, :id_ordine, 1, null)");
        $stmt->execute([
            'nome_pizza' => $pizza_id,
            'id_ordine' => $ordine_id
        ]);

        $pdo->commit();

        $successo = true;
    } catch (Exception $e) {
        $pdo->rollBack();
        $errore = $e->getMessage();
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
	<title>L.M. Pizzeria</title>
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
</head>

<body>
    <?php $pagina_attiva = 'ordina'; ?>
    <?php include 'header.php'; ?>

    <?php 
    $_SESSION['user'] = 'Alessandro'; 
    if (isset($_SESSION['user'])) {
        $stmt = $pdo->query("SELECT * FROM pizza");
        $pizze = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmtCon = $pdo->query("SELECT * FROM contorno");
        $contorni = $stmtCon->fetchAll(PDO::FETCH_ASSOC);
        $stmtAnti = $pdo->query("SELECT * FROM antipasto");
        $antipasti = $stmtAnti->fetchAll(PDO::FETCH_ASSOC);
        echo "<div class='container mt-3'><h2>Benvenuto, ". $_SESSION['user'] ."!</h2></div>";
    ?>
        <div class="container py-5">
            <h1 class="text-center mb-5" style="font-family: 'Josefin Sans';">Ordina la tua Pizza</h1>

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form method="POST" action="ordina.php">
                        <div class="form-group mb-4">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" id="nome" placeholder="Inserisci il tuo nome" value="<?= htmlspecialchars($_SESSION['user']) ?>" required>
                        </div>

                        <div class="form-group mb-4">
                            <label for="antipasto">Antipasto</label>
                            <select class="form-control" id="antipasto" name="antipasto">
                                <?php foreach ($antipasti as $antipasto): ?>
                                <option value="<?= htmlspecialchars($antipasto['nome']) ?>">
                                    <?= htmlspecialchars($antipasto['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                            </select>
                            <label for="pizza">Pizza</label>
                            <select class="form-control" id="pizza" name="pizza">
                                <?php foreach ($pizze as $pizza): ?>
                                <option value="<?= htmlspecialchars($pizza['nome']) ?>">
                                    <?= htmlspecialchars($pizza['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                            </select>
                            <label for="contorno">Contorno</label>
                            <select class="form-control" id="contorno" name="contorno">
                                <?php foreach ($contorni as $contorno): ?>
                                <option value="<?= htmlspecialchars($contorno['nome']) ?>">
                                    <?= htmlspecialchars($contorno['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                            </select>

                        </div>

                        <div class="form-group mb-4">
                            <label for="note">Note aggiuntive</label>
                            <textarea class="form-control" id="note" rows="3" placeholder="Es. senza cipolla, ben cotta..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Invia Ordine</button>
                    </form>
                </div>
            </div>
        </div>
    <?php 
    } else {
        echo "<div class='container mt-3'><h2>Benvenuto, visitatore!</h2></div>";
        echo "<div class='container mt-3'><p>Per ordinare una pizza, <a href='login.php'>effettua il login</a> o <a href='register.php'>registrati</a>.</p></div>";
    }
    ?>
</body>


</html>
