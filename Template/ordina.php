<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_name = $_SESSION['user'];
    
    // Gestione ordini multipli
    $ordini = [];
    
    // Controlla se ci sono ordini multipli
    if (isset($_POST['ordini']) && is_array($_POST['ordini'])) {
        foreach ($_POST['ordini'] as $index => $ordine) {
            if (!empty($ordine['pizza'])) {
                $ordini[] = [
                    'nome' => $ordine['nome'] ?? '',
                    'pizza' => $ordine['pizza'],
                    'antipasto' => $ordine['antipasto'] ?? null,
                    'contorno' => $ordine['contorno'] ?? null,
                    'secondo' => $ordine['secondo'] ?? null,
                    'bevanda' => $ordine['bevanda'] ?? null,
                    'note' => $ordine['note'] ?? ''
                ];
            }
        }
    }

    if (!empty($ordini)) {
        try {
            $pdo->beginTransaction();

            // Crea un ordine principale con tutte le note delle persone
            $note_ordine = "Ordine per: ";
            $nomi_persone = [];
            foreach ($ordini as $ordine) {
                $nomi_persone[] = $ordine['nome'];
            }
            $note_ordine .= implode(', ', $nomi_persone);

            $stmt = $pdo->prepare("INSERT INTO ordine_online (data_orario, stato, note) VALUES (NOW(), :stato, :note)");
            $stmt->execute([
                'stato' => 'in_preparazione',
                'note' => $note_ordine
            ]);

            $ordine_id = $pdo->lastInsertId();

            // Inserisci ogni elemento dell'ordine mantenendo lo stesso id_ordine
            foreach ($ordini as $ordine) {
                // Inserisci l'antipasto se presente
                if (!empty($ordine['antipasto'])) {
                    $note_antipasto = "Per: " . $ordine['nome'];
                    $stmt = $pdo->prepare("INSERT INTO anti_in_ordini (id_ordine , nome_anti, num_anti) VALUES (:id_ordine, :nome_antipasto,  1)");
                    $stmt->execute([
                        'nome_antipasto' => $ordine['antipasto'],
                        'id_ordine' => $ordine_id,
                    ]);
                }

                // Inserisci la pizza
                $note_pizza = "Per: " . $ordine['nome'];
                if (!empty($ordine['note'])) {
                    $note_pizza .= " - " . $ordine['note'];
                }
                
                $stmt = $pdo->prepare("INSERT INTO pizze_in_ordine (nome_pizza, id_ordine, num_pizze, aggiunte) VALUES (:nome_pizza, :id_ordine, 1, :aggiunte)");
                $stmt->execute([
                    'nome_pizza' => $ordine['pizza'],
                    'id_ordine' => $ordine_id,
                    'aggiunte' => null
                ]);

                // Inserisci il contorno se presente
                if (!empty($ordine['contorno'])) {
                    $stmt = $pdo->prepare("INSERT INTO contorni_in_ordini (id_ordine, nome_contorno,  num_contorni) VALUES (:id_ordine, :nome_contorno,  1)");
                    $stmt->execute([
                        'nome_contorno' => $ordine['contorno'],
                        'id_ordine' => $ordine_id,
                    ]);
                }
                if (!empty($ordine['secondo'])) {
                    $stmt = $pdo->prepare("INSERT INTO secondi_in_ordini (id_ordine, nome_secondo,  num_secondi) VALUES (:id_ordine, :nome_secondo,  1)");
                    $stmt->execute([
                        'nome_secondo' => $ordine['secondo'],
                        'id_ordine' => $ordine_id,
                    ]);
                }
                if (!empty($ordine['bevanda'])) {
                    $stmt = $pdo->prepare("INSERT INTO bevande_in_ordini (id_ordine, nome_bevanda,  num_bevande) VALUES (:id_ordine, :nome_bevanda,  1)");
                    $stmt->execute([
                        'nome_bevanda' => $ordine['bevanda'],
                        'id_ordine' => $ordine_id,
                    ]);
                }
            }

            $pdo->commit();
            $successo = true;
        } catch (Exception $e) {
            $pdo->rollBack();
            $errore = $e->getMessage();
        }
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
        $stmtSec = $pdo->query("SELECT * FROM secondo");
        $secondi = $stmtSec->fetchAll(PDO::FETCH_ASSOC);
        $stmtBev = $pdo->query("SELECT * FROM bevanda");
        $bevande = $stmtBev->fetchAll(PDO::FETCH_ASSOC);
        echo "<div class='container mt-3'><h2>Benvenuto, ". $_SESSION['user'] ."!</h2></div>";
    ?>
        <div class="container py-5">
            <h1 class="text-center mb-5" style="font-family: 'Josefin Sans';">Ordina la tua Pizza</h1>

            <?php if (isset($successo) && $successo): ?>
                <div class="alert alert-success" role="alert">
                    Ordine inviato con successo!
                </div>
            <?php endif; ?>

            <?php if (isset($errore)): ?>
                <div class="alert alert-danger" role="alert">
                    Errore: <?= htmlspecialchars($errore) ?>
                </div>
            <?php endif; ?>

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form method="POST" action="ordina.php" id="orderForm">
                        <div id="ordiniContainer">
                            <!-- Primo ordine -->
                            <div class="ordine-item mb-5" data-ordine="0">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4>Ordine #1</h4>
                                    <button type="button" class="btn btn-sm btn-danger remove-ordine" style="display: none;">
                                        Rimuovi
                                    </button>
                                </div>
                                
                                <div class="form-group mb-4">
                                    <label for="nome0">Nome</label>
                                    <input type="text" class="form-control" id="nome0" name="ordini[0][nome]" placeholder="Inserisci il nome" value="<?= htmlspecialchars($_SESSION['user']) ?>" required>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="antipasto0">Antipasto</label>
                                    <select class="form-control" id="antipasto0" name="ordini[0][antipasto]">
                                        <option value="">Nessun antipasto</option>
                                        <?php foreach ($antipasti as $antipasto): ?>
                                        <option value="<?= htmlspecialchars($antipasto['nome']) ?>">
                                            <?= htmlspecialchars($antipasto['nome']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                    
                                    <label for="pizza0">Pizza</label>
                                    <select class="form-control" id="pizza0" name="ordini[0][pizza]" required>
                                        <option value="">Seleziona una pizza</option>
                                        <?php foreach ($pizze as $pizza): ?>
                                        <option value="<?= htmlspecialchars($pizza['nome']) ?>">
                                            <?= htmlspecialchars($pizza['nome']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <label for="secondo0">Secondo</label>
                                    <select class="form-control" id="secondo0" name="ordini[0][secondo]" required>
                                        <option value="">Nessun secondo</option>
                                        <?php foreach ($secondi as $secondo): ?>
                                        <option value="<?= htmlspecialchars($secondo['nome']) ?>">
                                            <?= htmlspecialchars($secondo['nome']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <label for="contorno0">Contorno</label>
                                    <select class="form-control" id="contorno0" name="ordini[0][contorno]">
                                        <option value="">Nessun contorno</option>
                                        <?php foreach ($contorni as $contorno): ?>
                                        <option value="<?= htmlspecialchars($contorno['nome']) ?>">
                                            <?= htmlspecialchars($contorno['nome']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <label for="bevanda0">Bevanda</label>
                                    <select class="form-control" id="bevanda0" name="ordini[0][bevanda]">
                                        <option value="">Nessuna bevanda</option>
                                        <?php foreach ($bevande as $bevanda): ?>
                                        <option value="<?= htmlspecialchars($bevanda['nome']) ?>">
                                            <?= htmlspecialchars($bevanda['nome']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="note0">Note aggiuntive</label>
                                    <textarea class="form-control" id="note0" name="ordini[0][note]" rows="3" placeholder="Es. senza cipolla, ben cotta..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mb-4">
                            <button type="button" class="btn btn-outline-primary" id="addOrdine">
                                + Aggiungi un altro ordine
                            </button>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Invia Ordine Completo</button>
                    </form>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let ordineCounter = 1;
                
                // Template per nuovo ordine
                const ordineTemplate = `
                    <div class="ordine-item mb-5" data-ordine="{INDEX}">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4>Ordine #{NUMBER}</h4>
                            <button type="button" class="btn btn-sm btn-danger remove-ordine">
                                Rimuovi
                            </button>
                        </div>
                        
                        <div class="form-group mb-4">
                            <label for="nome{INDEX}">Nome</label>
                            <input type="text" class="form-control" id="nome{INDEX}" name="ordini[{INDEX}][nome]" placeholder="Inserisci il nome" required>
                        </div>

                        <div class="form-group mb-4">
                            <label for="antipasto{INDEX}">Antipasto</label>
                            <select class="form-control" id="antipasto{INDEX}" name="ordini[{INDEX}][antipasto]">
                                <option value="">Nessun antipasto</option>
                                <?php foreach ($antipasti as $antipasto): ?>
                                <option value="<?= htmlspecialchars($antipasto['nome']) ?>">
                                    <?= htmlspecialchars($antipasto['nome']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            
                            <label for="pizza{INDEX}">Pizza</label>
                            <select class="form-control" id="pizza{INDEX}" name="ordini[{INDEX}][pizza]" required>
                                <option value="">Seleziona una pizza</option>
                                <?php foreach ($pizze as $pizza): ?>
                                <option value="<?= htmlspecialchars($pizza['nome']) ?>">
                                    <?= htmlspecialchars($pizza['nome']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            
                            <label for="secondo{INDEX}">Secondo</label>
                            <select class="form-control" id="secondo{INDEX}" name="ordini[{INDEX}][secondo]">
                                <option value="">Nessun contorno</option>
                                <?php foreach ($secondi as $secondo): ?>
                                <option value="<?= htmlspecialchars($secondo['nome']) ?>">
                                    <?= htmlspecialchars($secondo['nome']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>

                            <label for="contorno{INDEX}">Contorno</label>
                            <select class="form-control" id="contorno{INDEX}" name="ordini[{INDEX}][contorno]">
                                <option value="">Nessun contorno</option>
                                <?php foreach ($contorni as $contorno): ?>
                                <option value="<?= htmlspecialchars($contorno['nome']) ?>">
                                    <?= htmlspecialchars($contorno['nome']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>

                            <label for="bevanda{INDEX}">Bevanda</label>
                            <select class="form-control" id="bevanda{INDEX}" name="ordini[{INDEX}][bevanda]">
                                <option value="">Nessuna bevanda</option>
                                <?php foreach ($bevande as $bevanda): ?>
                                <option value="<?= htmlspecialchars($bevanda['nome']) ?>">
                                    <?= htmlspecialchars($bevanda['nome']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label for="note{INDEX}">Note aggiuntive</label>
                            <textarea class="form-control" id="note{INDEX}" name="ordini[{INDEX}][note]" rows="3" placeholder="Es. senza cipolla, ben cotta..."></textarea>
                        </div>
                    </div>
                `;

                // Aggiungi nuovo ordine
                document.getElementById('addOrdine').addEventListener('click', function() {
                    const container = document.getElementById('ordiniContainer');
                    const newOrdine = ordineTemplate
                        .replace(/{INDEX}/g, ordineCounter)
                        .replace(/{NUMBER}/g, ordineCounter + 1);
                    
                    container.insertAdjacentHTML('beforeend', newOrdine);
                    ordineCounter++;
                    
                    // Mostra i pulsanti di rimozione se ci sono più ordini
                    updateRemoveButtons();
                });

                // Rimuovi ordine
                document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-ordine') || e.target.closest('.remove-ordine')) {
                        const ordineItem = e.target.closest('.ordine-item');
                        ordineItem.remove();
                        updateRemoveButtons();
                        updateOrdineNumbers();
                    }
                });

                // Aggiorna visibilità pulsanti rimozione
                function updateRemoveButtons() {
                    const ordini = document.querySelectorAll('.ordine-item');
                    const removeButtons = document.querySelectorAll('.remove-ordine');
                    
                    removeButtons.forEach(button => {
                        button.style.display = ordini.length > 1 ? 'inline-block' : 'none';
                    });
                }

                // Aggiorna numerazione ordini
                function updateOrdineNumbers() {
                    const ordini = document.querySelectorAll('.ordine-item');
                    ordini.forEach((ordine, index) => {
                        const header = ordine.querySelector('h4');
                        header.textContent = `Ordine #${index + 1}`;
                    });
                }
            });
        </script>

    <?php 
    } else {
        echo "<div class='container mt-3'><h2>Benvenuto, visitatore!</h2></div>";
        echo "<div class='container mt-3'><p>Per ordinare una pizza, <a href='login.php'>effettua il login</a> o <a href='register.php'>registrati</a>.</p></div>";
    }
    ?>
</body>

</html>