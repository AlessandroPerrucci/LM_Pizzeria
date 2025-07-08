<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_name = $_SESSION['user'];
    
    // Decodifica i dati del carrello inviati come JSON
    $carrello_json = $_POST['carrello'] ?? '';
    $carrello = json_decode($carrello_json, true);
    
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

            // Inserisci ogni elemento del carrello
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
    </style>
</head>

<body>
    <?php $pagina_attiva = 'ordina'; ?>
    <?php include 'header.php'; ?>

    <?php 
    $_SESSION['user'] = 'Alessandro'; 
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
                <form method="POST" action="" id="checkoutForm">
                    <input type="hidden" name="carrello" id="carrelloData">
                    <button type="submit" class="btn btn-primary btn-block" id="checkoutBtn" disabled>
                        Conferma Ordine
                    </button>
                </form>
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
        let cart = [];
        
        function addToCart(tipo, nome, prezzo) {
            const personInput = document.getElementById(`person-${tipo}-${nome}`);
            const nomePerson = personInput.value.trim() || '<?= $_SESSION['user'] ?>';
            
            // Controlla se l'articolo esiste già nel carrello
            const existingItem = cart.find(item => 
                item.tipo === tipo && 
                item.nome === nome && 
                item.nome_persona === nomePerson
            );
            
            if (existingItem) {
                existingItem.quantita++;
            } else {
                cart.push({
                    tipo: tipo,
                    nome: nome,
                    prezzo: prezzo,
                    quantita: 1,
                    nome_persona: nomePerson,
                    note: ''
                });
            }
            
            // Pulisci il campo nome persona
            personInput.value = '';
            
            updateCartDisplay();
            
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
            
            // Aggiorna i dati del carrello nel form
            document.getElementById('carrelloData').value = JSON.stringify(cart);
        }
        
        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCartDisplay();
        }
        
        function changeQuantity(index, delta) {
            cart[index].quantita += delta;
            if (cart[index].quantita <= 0) {
                removeFromCart(index);
            } else {
                updateCartDisplay();
            }
        }
        
        function updateNote(index, note) {
            cart[index].note = note;
            updateCartDisplay();
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
        
        // Inizializza il carrello
        updateCartDisplay();
    </script>

    <?php 
    } else {
        echo "<div class='container mt-3'><h2>Benvenuto, visitatore!</h2></div>";
        echo "<div class='container mt-3'><p>Per ordinare, <a href='login.php'>effettua il login</a> o <a href='register.php'>registrati</a>.</p></div>";
    }
    ?>
</body>
</html>