<?php
session_start();
require_once 'config.php';

// Inizializza il carrello nella sessione se non esiste
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Gestione AJAX per aggiornare il carrello
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
    header('Content-Type: application/json');

    switch ($_POST['action']) {
        case 'add_to_cart':
            $item = [
                'tipo' => $_POST['tipo'],
                'nome' => $_POST['nome'],
                'prezzo' => floatval($_POST['prezzo']),
                'quantita' => 1,
                'nome_persona' => $_POST['nome_persona'],
                'note' => ''
            ];

            // Cerca se l'articolo esiste già
            $found = false;
            foreach ($_SESSION['cart'] as &$cartItem) {
                if (
                    $cartItem['tipo'] === $item['tipo'] &&
                    $cartItem['nome'] === $item['nome'] &&
                    $cartItem['nome_persona'] === $item['nome_persona']
                ) {
                    $cartItem['quantita']++;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $_SESSION['cart'][] = $item;
            }

            echo json_encode(['success' => true, 'cart' => $_SESSION['cart']]);
            exit;

        case 'remove_from_cart':
            $index = intval($_POST['index']);
            if (isset($_SESSION['cart'][$index])) {
                array_splice($_SESSION['cart'], $index, 1);
            }
            echo json_encode(['success' => true, 'cart' => $_SESSION['cart']]);
            exit;

        case 'change_quantity':
            $index = intval($_POST['index']);
            $delta = intval($_POST['delta']);

            if (isset($_SESSION['cart'][$index])) {
                $_SESSION['cart'][$index]['quantita'] += $delta;
                if ($_SESSION['cart'][$index]['quantita'] <= 0) {
                    array_splice($_SESSION['cart'], $index, 1);
                }
            }
            echo json_encode(['success' => true, 'cart' => $_SESSION['cart']]);
            exit;

        case 'update_note':
            $index = intval($_POST['index']);
            $note = $_POST['note'];

            if (isset($_SESSION['cart'][$index])) {
                $_SESSION['cart'][$index]['note'] = $note;
            }
            echo json_encode(['success' => true, 'cart' => $_SESSION['cart']]);
            exit;

        case 'get_cart':
            echo json_encode(['cart' => $_SESSION['cart']]);
            exit;
    }
}

// Gestione ordine
if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST['action']) && isset($_POST['indirizzo'])) {
    $user_name = $_SESSION['user'];
    $indirizzo = $_POST['indirizzo'];
    $numero = $_POST['numero'];
    $metodo_pagamento = $_POST['metodo_pagamento'];

    // Usa il carrello dalla sessione
    $carrello = $_SESSION['cart'];

    if (!empty($carrello)) {
        try {
            $pdo->beginTransaction();

            // Crea un ordine principale
            $note_ordine = "Ordine online - " . count($carrello) . " articoli";

            $stmt = $pdo->prepare("INSERT INTO ordine_online (data_orario, stato, note) VALUES (NOW(), :stato, :note)");
            $stmt->execute([
                'stato' => 'in_preparazione',
                'note' => $note_ordine
            ]);

            $ordine_id = $pdo->lastInsertId();

            // Inserisci i dati di consegna nella nuova tabella
            $stmt = $pdo->prepare("INSERT INTO ordini_utenti (id_ordine, email_utente, indirizzo, numero, metodo_pagamento) VALUES (:id_ordine, :email_utente, :indirizzo, :numero, :metodo_pagamento)");
            $stmt->execute([
                'id_ordine' => $ordine_id,
                'email_utente' => $_SESSION['mail'],
                'indirizzo' => $indirizzo,
                'numero' => $numero,
                'metodo_pagamento' => $metodo_pagamento
            ]);

            // Inserisci ogni elemento del carrello (resto del codice uguale)
            foreach ($carrello as $item) {
                $note_item = "Per: " . $item['nome_persona'];
                if (!empty($item['note'])) {
                    $note_item .= " - " . $item['note'];
                }

                switch ($item['tipo']) {
                    case 'pizza':
                        $stmt = $pdo->prepare("INSERT INTO pizze_in_ordine (nome_pizza, id_ordine, num_pizze, aggiunte) VALUES (:nome, :id_ordine, :quantita, :note)");
                        $stmt->execute([
                            'nome' => $item['nome'],
                            'id_ordine' => $ordine_id,
                            'quantita' => $item['quantita'],
                            'note' => $note_item
                        ]);
                        break;
                    case 'antipasto':
                        $stmt = $pdo->prepare("INSERT INTO anti_in_ordini (id_ordine, nome_anti, num_anti) VALUES (:id_ordine, :nome, :quantita)");
                        $stmt->execute([
                            'id_ordine' => $ordine_id,
                            'nome' => $item['nome'],
                            'quantita' => $item['quantita']
                        ]);
                        break;
                    case 'secondo':
                        $stmt = $pdo->prepare("INSERT INTO secondi_in_ordini (id_ordine, nome_secondo, num_secondi) VALUES (:id_ordine, :nome, :quantita)");
                        $stmt->execute([
                            'id_ordine' => $ordine_id,
                            'nome' => $item['nome'],
                            'quantita' => $item['quantita']
                        ]);
                        break;
                    case 'contorno':
                        $stmt = $pdo->prepare("INSERT INTO contorni_in_ordini (id_ordine, nome_contorno, num_contorni) VALUES (:id_ordine, :nome, :quantita)");
                        $stmt->execute([
                            'id_ordine' => $ordine_id,
                            'nome' => $item['nome'],
                            'quantita' => $item['quantita']
                        ]);
                        break;
                    case 'bevanda':
                        $stmt = $pdo->prepare("INSERT INTO bevande_in_ordini (id_ordine, nome_bevanda, num_bevande) VALUES (:id_ordine, :nome, :quantita)");
                        $stmt->execute([
                            'id_ordine' => $ordine_id,
                            'nome' => $item['nome'],
                            'quantita' => $item['quantita']
                        ]);
                        break;
                }
            }

            $pdo->commit();

            // Svuota il carrello dopo l'ordine
            $_SESSION['cart'] = [];

            $successo = true;
        } catch (Exception $e) {
            $pdo->rollBack();
            $errore = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <title>L.M. Pizzeria - Ordina Online</title>
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

    <style>
        .product-card {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            transition: all 0.3s ease;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .category-section {
            margin-bottom: 3rem;
        }

        .category-title {
            color: #d4a574;
            font-family: 'Josefin Sans', sans-serif;
            border-bottom: 2px solid #d4a574;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        .cart-sidebar {
            position: fixed;
            top: 0;
            right: -400px;
            width: 400px;
            height: 100vh;
            background: white;
            box-shadow: -5px 0 15px rgba(0,0,0,0.1);
            transition: right 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }

        .cart-sidebar.open {
            right: 0;
        }

        .cart-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            display: none;
        }

        .cart-overlay.active {
            display: block;
        }

        .cart-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1001;
            background: #d4a574;
            color: white;
            border: none;
            border-radius: 50px;
            padding: 15px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .cart-btn:hover {
            background: #c19660;
            transform: scale(1.05);
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }

        .cart-item {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .qty-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .qty-btn {
            background: #d4a574;
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .qty-btn:hover {
            background: #c19660;
        }

        .add-to-cart-btn {
            background: #d4a574;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .add-to-cart-btn:hover {
            background: #c19660;
            transform: scale(1.05);
        }

        .person-select {
            margin-bottom: 10px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
        }
        .cart-sidebar input,
        .cart-sidebar select,
        .cart-sidebar textarea {
            color: #000 !important;
            background-color: #fff !important;
            border: 1px solid #ddd !important;
        }

        .cart-sidebar input::placeholder {
            color: #666 !important;
        }

        .cart-sidebar label {
            color: #000 !important;
            font-weight: 500;
        }

        .cart-sidebar h5 {
            color: #000 !important;
        }

        /* Assicurati che anche i campi delle note siano leggibili */
        .cart-item input[type="text"] {
            color: #000 !important;
            background-color: #fff !important;
        }
    </style>
</head>

<body>
    <?php $pagina_attiva = 'ordina'; ?>
    <?php include 'header.php'; ?>

    <?php
    $_SESSION['user'] = 'Alessandro';
    $_SESSION['mail'] = 'perrucciale1808@gmail.com';
    if (isset($_SESSION['user'])) {
        // Recupera tutti i prodotti
        $stmt = $pdo->query("SELECT * FROM pizza ORDER BY nome");
        $pizze = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $pdo->query("SELECT * FROM antipasto ORDER BY nome");
        $antipasti = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $pdo->query("SELECT * FROM secondo ORDER BY nome");
        $secondi = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $pdo->query("SELECT * FROM contorno ORDER BY nome");
        $contorni = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $pdo->query("SELECT * FROM bevanda ORDER BY nome");
        $bevande = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <!-- Pulsante Carrello -->
    <button class="cart-btn" onclick="toggleCart()">
        🛒 Carrello
        <span class="cart-badge" id="cartBadge">0</span>
    </button>

    <!-- Overlay Carrello -->
    <div class="cart-overlay" id="cartOverlay" onclick="closeCart()"></div>

    <!-- Sidebar Carrello -->
    <div class="cart-sidebar" id="cartSidebar">
        <div class="p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Il tuo Carrello</h3>
                <button class="btn btn-link" onclick="closeCart()">✕</button>
            </div>

            <div id="cartItems"></div>

            <div class="mt-4 pt-4 border-top">
                <button type="button" class="btn btn-primary btn-block" id="checkoutBtn" disabled onclick="showCheckoutForm()">
                    Conferma Ordine
                </button>

                <!-- Form di checkout (inizialmente nascosto) -->
                <div id="checkoutForm" style="display: none;" class="mt-3">
                    <h5>Dati per la consegna</h5>
                    <form method="POST" action="" id="orderForm">
                        <div class="form-group">
                            <label>Indirizzo:</label>
                            <input type="text" name="indirizzo" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Numero telefono:</label>
                            <input type="tel" name="numero" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Metodo di pagamento:</label>
                            <select name="metodo_pagamento" class="form-control" required>
                                <option value="">Seleziona...</option>
                                <option value="contanti">Contanti alla consegna</option>
                                <option value="carta">Carta di credito</option>
                                <option value="paypal">PayPal</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">Conferma e Ordina</button>
                        <button type="button" class="btn btn-secondary btn-block mt-2" onclick="hideCheckoutForm()">Annulla</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 style="font-family: 'Josefin Sans';">Benvenuto, <?= $_SESSION['user'] ?>!</h1>
            <p class="lead">Scegli i tuoi prodotti preferiti e aggiungili al carrello</p>
        </div>

        <?php if (isset($successo) && $successo): ?>
            <div class="alert-success">
                Ordine inviato con successo! Grazie per aver scelto L.M. Pizzeria.
            </div>
        <?php endif; ?>

        <?php if (isset($errore)): ?>
            <div class="alert-danger">
                Errore: <?= htmlspecialchars($errore) ?>
            </div>
        <?php endif; ?>

        <!-- Sezione Pizze -->
        <div class="category-section">
            <h2 class="category-title">🍕 Pizze</h2>
            <div class="row">
                <?php foreach ($pizze as $pizza): ?>
                    <div class="col-md-4 mb-4">
                        <div class="product-card p-4">
                            <h5><?= htmlspecialchars($pizza['nome']) ?></h5>
                            <p class="text-muted mb-3"><?= htmlspecialchars($pizza['descrizione'] ?? 'Deliziosa pizza della casa') ?></p>
                            <div class="person-select">
                                <label class="small">Per chi:</label>
                                <input type="text" class="form-control form-control-sm"
                                    placeholder="Nome persona"
                                    id="person-pizza-<?= $pizza['nome'] ?>">
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="font-weight-bold">€ <?= number_format($pizza['prezzo'] ?? 8.00, 2) ?></span>
                                <button class="add-to-cart-btn"
                                    onclick="addToCart('pizza', '<?= htmlspecialchars($pizza['nome']) ?>', <?= $pizza['prezzo'] ?? 8.00 ?>)">
                                    Aggiungi
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Sezione Antipasti -->
        <div class="category-section">
            <h2 class="category-title">🥗 Antipasti</h2>
            <div class="row">
                <?php foreach ($antipasti as $antipasto): ?>
                    <div class="col-md-4 mb-4">
                        <div class="product-card p-4">
                            <h5><?= htmlspecialchars($antipasto['nome']) ?></h5>
                            <p class="text-muted mb-3"><?= htmlspecialchars($antipasto['descrizione'] ?? 'Antipasto della casa') ?></p>
                            <div class="person-select">
                                <label class="small">Per chi:</label>
                                <input type="text" class="form-control form-control-sm"
                                    placeholder="Nome persona"
                                    id="person-antipasto-<?= $antipasto['nome'] ?>">
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="font-weight-bold">€ <?= number_format($antipasto['prezzo'] ?? 5.00, 2) ?></span>
                                <button class="add-to-cart-btn"
                                    onclick="addToCart('antipasto', '<?= htmlspecialchars($antipasto['nome']) ?>', <?= $antipasto['prezzo'] ?? 5.00 ?>)">
                                    Aggiungi
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Sezione Secondi -->
        <div class="category-section">
            <h2 class="category-title">🍖 Secondi</h2>
            <div class="row">
                <?php foreach ($secondi as $secondo): ?>
                    <div class="col-md-4 mb-4">
                        <div class="product-card p-4">
                            <h5><?= htmlspecialchars($secondo['nome']) ?></h5>
                            <p class="text-muted mb-3"><?= htmlspecialchars($secondo['descrizione'] ?? 'Secondo piatto della casa') ?></p>
                            <div class="person-select">
                                <label class="small">Per chi:</label>
                                <input type="text" class="form-control form-control-sm"
                                    placeholder="Nome persona"
                                    id="person-secondo-<?= $secondo['nome'] ?>">
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="font-weight-bold">€ <?= number_format($secondo['prezzo'] ?? 12.00, 2) ?></span>
                                <button class="add-to-cart-btn"
                                    onclick="addToCart('secondo', '<?= htmlspecialchars($secondo['nome']) ?>', <?= $secondo['prezzo'] ?? 12.00 ?>)">
                                    Aggiungi
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Sezione Contorni -->
        <div class="category-section">
            <h2 class="category-title">🥬 Contorni</h2>
            <div class="row">
                <?php foreach ($contorni as $contorno): ?>
                    <div class="col-md-4 mb-4">
                        <div class="product-card p-4">
                            <h5><?= htmlspecialchars($contorno['nome']) ?></h5>
                            <p class="text-muted mb-3"><?= htmlspecialchars($contorno['descrizione'] ?? 'Contorno fresco') ?></p>
                            <div class="person-select">
                                <label class="small">Per chi:</label>
                                <input type="text" class="form-control form-control-sm"
                                    placeholder="Nome persona"
                                    id="person-contorno-<?= $contorno['nome'] ?>">
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="font-weight-bold">€ <?= number_format($contorno['prezzo'] ?? 4.00, 2) ?></span>
                                <button class="add-to-cart-btn"
                                    onclick="addToCart('contorno', '<?= htmlspecialchars($contorno['nome']) ?>', <?= $contorno['prezzo'] ?? 4.00 ?>)">
                                    Aggiungi
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Sezione Bevande -->
        <div class="category-section">
            <h2 class="category-title">🥤 Bevande</h2>
            <div class="row">
                <?php foreach ($bevande as $bevanda): ?>
                    <div class="col-md-4 mb-4">
                        <div class="product-card p-4">
                            <h5><?= htmlspecialchars($bevanda['nome']) ?></h5>
                            <p class="text-muted mb-3"><?= htmlspecialchars($bevanda['descrizione'] ?? 'Bevanda rinfrescante') ?></p>
                            <div class="person-select">
                                <label class="small">Per chi:</label>
                                <input type="text" class="form-control form-control-sm"
                                    placeholder="Nome persona"
                                    id="person-bevanda-<?= $bevanda['nome'] ?>">
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="font-weight-bold">€ <?= number_format($bevanda['prezzo'] ?? 3.00, 2) ?></span>
                                <button class="add-to-cart-btn"
                                    onclick="addToCart('bevanda', '<?= htmlspecialchars($bevanda['nome']) ?>', <?= $bevanda['prezzo'] ?? 3.00 ?>)">
                                    Aggiungi
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        function showCheckoutForm() {
            document.getElementById('checkoutBtn').style.display = 'none';
            document.getElementById('checkoutForm').style.display = 'block';
        }

        function hideCheckoutForm() {
            document.getElementById('checkoutBtn').style.display = 'block';
            document.getElementById('checkoutForm').style.display = 'none';
        }

        // Carica il carrello dalla sessione al caricamento della pagina
        let cart = <?= json_encode($_SESSION['cart']) ?>;

        function addToCart(tipo, nome, prezzo) {
            const personInput = document.getElementById(`person-${tipo}-${nome}`);
            const nomePerson = personInput.value.trim() || '<?= $_SESSION['user'] ?>';

            // Invia richiesta AJAX per aggiungere al carrello
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=add_to_cart&tipo=${encodeURIComponent(tipo)}&nome=${encodeURIComponent(nome)}&prezzo=${prezzo}&nome_persona=${encodeURIComponent(nomePerson)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cart = data.cart;
                    updateCartDisplay();

                    // Pulisci il campo nome persona
                    personInput.value = '';

                    // Mostra feedback visivo
                    const button = event.target;
                    const originalText = button.textContent;
                    button.textContent = 'Aggiunto!';
                    button.style.background = '#28a745';
                    setTimeout(() => {
                        button.textContent = originalText;
                        button.style.background = '#d4a574';
                    }, 1000);
                }
            })
            .catch(error => {
                console.error('Errore:', error);
            });
        }

        function updateCartDisplay() {
            const cartItems = document.getElementById('cartItems');
            const cartBadge = document.getElementById('cartBadge');
            const checkoutBtn = document.getElementById('checkoutBtn');

            // Aggiorna badge
            const totalItems = cart.reduce((sum, item) => sum + item.quantita, 0);
            cartBadge.textContent = totalItems;

            // Aggiorna contenuto carrello
            if (cart.length === 0) {
                cartItems.innerHTML = '<p class="text-muted">Il carrello è vuoto</p>';
                checkoutBtn.disabled = true;
            } else {
                let html = '';
                let total = 0;

                cart.forEach((item, index) => {
                    const itemTotal = item.prezzo * item.quantita;
                    total += itemTotal;

                    html += `
                        <div class="cart-item">
                            <div class="d-flex justify-content-between mb-2">
                                <strong>${item.nome}</strong>
                                <button class="btn btn-sm btn-outline-danger" onclick="removeFromCart(${index})">
                                    ✕
                                </button>
                            </div>
                            <div class="small text-muted mb-2">
                                ${item.tipo.charAt(0).toUpperCase() + item.tipo.slice(1)} • Per: ${item.nome_persona}
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="qty-controls">
                                    <button class="qty-btn" onclick="changeQuantity(${index}, -1)">-</button>
                                    <span class="mx-2">${item.quantita}</span>
                                    <button class="qty-btn" onclick="changeQuantity(${index}, 1)">+</button>
                                </div>
                                <span class="font-weight-bold">€ ${itemTotal.toFixed(2)}</span>
                            </div>
                            <div class="mb-2">
                                <input type="text" class="form-control form-control-sm"
                                    placeholder="Note aggiuntive..."
                                    value="${item.note}"
                                    onchange="updateNote(${index}, this.value)">
                            </div>
                        </div>
                    `;
                });

                html += `
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex justify-content-between">
                            <strong>Totale: €${total.toFixed(2)}</strong>
                        </div>
                    </div>
                `;

                cartItems.innerHTML = html;
                checkoutBtn.disabled = false;
            }
        }

        function removeFromCart(index) {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=remove_from_cart&index=${index}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cart = data.cart;
                    updateCartDisplay();
                }
            })
            .catch(error => {
                console.error('Errore:', error);
            });
        }

        function changeQuantity(index, delta) {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=change_quantity&index=${index}&delta=${delta}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cart = data.cart;
                    updateCartDisplay();
                }
            })
            .catch(error => {
                console.error('Errore:', error);
            });
        }

        function updateNote(index, note) {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=update_note&index=${index}&note=${encodeURIComponent(note)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cart = data.cart;
                    // Non aggiornare display per evitare perdita focus
                }
            })
            .catch(error => {
                console.error('Errore:', error);
            });
        }

        function toggleCart() {
            const sidebar = document.getElementById('cartSidebar');
            const overlay = document.getElementById('cartOverlay');

            if (sidebar.classList.contains('open')) {
                closeCart();
            } else {
                sidebar.classList.add('open');
                overlay.classList.add('active');
            }
        }

        function closeCart() {
            const sidebar = document.getElementById('cartSidebar');
            const overlay = document.getElementById('cartOverlay');

            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        }

        // Inizializza il carrello al caricamento della pagina
        updateCartDisplay();
    </script>

    <?php
    } else {
        echo "<div class='container mt-3'><h2>Benvenuto, visitatore!</h2></div>";
        echo "<div class='container mt-3'><p>Per ordinare, <a href='login.php'>effettua il login</a> o <a href='register.php'>registrati</a>.</p></div>";
    }
    ?>
</body>