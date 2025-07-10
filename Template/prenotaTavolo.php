<?php 
session_start();
require_once 'config.php';

// Controllo se l'utente è loggato
if (!isset($_SESSION['mail'])) {
    header('Location: login.php');
    exit();
}

$email_utente = $_SESSION['mail'];
$messaggio = '';
$tipo_messaggio = '';

// Gestione della prenotazione
$tavoli_disponibili = [];
$step = 1; // Step 1: selezione numero posti, Step 2: selezione tavolo

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cerca_tavoli'])) {
    $numero_posti = (int)$_POST['numero_posti'];
    $data = $_POST['data'];
    $orario = $_POST['orario'];
    
    // Trova tavoli disponibili
    $stmt_tavoli = $pdo->prepare("
        SELECT t.id_tavolo, t.numero_posti 
        FROM tavolo t 
        WHERE t.numero_posti >= ? 
        AND t.id_tavolo NOT IN (
            SELECT p.id_tavolo 
            FROM prenotazione p 
            WHERE p.data = ? AND p.orario = ? AND p.prenotato = 1
        )
        ORDER BY t.numero_posti ASC
    ");
    $stmt_tavoli->execute([$numero_posti, $data, $orario]);
    $tavoli_disponibili = $stmt_tavoli->fetchAll(PDO::FETCH_ASSOC);
    
    $step = 2;
}

// Gestione della prenotazione specifica tavolo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prenota_tavolo'])) {
    $id_tavolo = (int)$_POST['id_tavolo'];
    $data = $_POST['data'];
    $orario = $_POST['orario'];
    $numero_posti = (int)$_POST['numero_posti'];
    
    try {
        // Controllo se l'utente ha già una prenotazione per quella data e orario
        $stmt_check = $pdo->prepare("SELECT * FROM prenotazione WHERE email_utente = ? AND data = ? AND orario = ?");
        $stmt_check->execute([$email_utente, $data, $orario]);
        
        if ($stmt_check->rowCount() > 0) {
            $messaggio = "Hai già una prenotazione per questa data e orario.";
            $tipo_messaggio = "warning";
        } else {
            // Verifica che il tavolo sia ancora disponibile
            $stmt_verifica = $pdo->prepare("
                SELECT COUNT(*) as count 
                FROM prenotazione 
                WHERE id_tavolo = ? AND data = ? AND orario = ? AND prenotato = 1
            ");
            $stmt_verifica->execute([$id_tavolo, $data, $orario]);
            $verifica = $stmt_verifica->fetch();
            
            if ($verifica['count'] > 0) {
                $messaggio = "Spiacenti, il tavolo selezionato non è più disponibile.";
                $tipo_messaggio = "error";
            } else {
                // Inserisci la prenotazione
                $query = "INSERT INTO prenotazione (id_tavolo, email_utente, prenotato, data, orario, num_persone) VALUES (?, ?, 1, ?, ?, ?)";
                $params = [$id_tavolo, $email_utente, $data, $orario, $numero_posti];
                
                $stmt_prenota = $pdo->prepare($query);
                $stmt_prenota->execute($params);
                
                $messaggio = "Prenotazione effettuata con successo! Tavolo " . $id_tavolo . " prenotato per il " . date('d/m/Y', strtotime($data)) . " - " . ucfirst($orario);
                $tipo_messaggio = "success";
                $step = 1; // Torna al primo step
            }
        }
    } catch (PDOException $e) {
        $messaggio = "Errore durante la prenotazione. Riprova più tardi.";
        $tipo_messaggio = "error";
    }
}

// Recupera le prenotazioni esistenti dell'utente
$stmt_prenotazioni = $pdo->prepare("
    SELECT p.*, t.numero_posti 
    FROM prenotazione p 
    JOIN tavolo t ON p.id_tavolo = t.id_tavolo 
    WHERE p.email_utente = ? AND p.prenotato = 1 
    ORDER BY p.data DESC, p.orario DESC
");
$stmt_prenotazioni->execute([$email_utente]);
$prenotazioni = $stmt_prenotazioni->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Prenota Tavolo - L.M. Pizzeria</title>
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
        .reservation-form {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        
        .alert-success {
            color: #3c763d;
            background-color: #dff0d8;
            border-color: #d6e9c6;
        }
        
        .alert-warning {
            color: #8a6d3b;
            background-color: #fcf8e3;
            border-color: #faebcc;
        }
        
        .alert-error {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
        }
        
        .form-group label {
    font-weight: 500;
    color: #333;
    margin-bottom: 8px;
}

.reservation-form h3,
.reservation-form h5,
.reservation-form p,
.reservation-form .text-muted {
    color: #333 !important;
}
.reservation-form .form-control,
.reservation-form .form-control option,
.reservation-form select,
.reservation-form input {
    color: #333 !important;
}
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 5px;
            padding: 12px 15px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #fac564;
            box-shadow: 0 0 0 0.2rem rgba(250, 197, 100, 0.25);
        }
        
        .btn-reservation {
            background: linear-gradient(45deg, #fac564, #f39c12);
            border: none;
            padding: 15px 40px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .btn-reservation:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(243, 156, 18, 0.3);
        }
        
        .reservation-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border-left: 4px solid #fac564;
        }
        
        .reservation-card h5 {
            color: #f39c12;
            margin-bottom: 15px;
        }
        
        .reservation-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .reservation-info span {
            font-weight: 500;
        }
        
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('images/bg_1.jpg');
            background-size: cover;
            background-position: center;
            padding: 100px 0;
            color: white;
            text-align: center;
        }
        .tavolo-card {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.tavolo-card:hover {
    border-color: #fac564;
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.tavolo-card .card-body {
    padding: 20px;
}

.tavolo-card .card-title {
    color: #f39c12;
    font-weight: 600;
    margin-bottom: 15px;
}

.badge-info {
    background-color: #17a2b8;
    font-size: 14px;
    padding: 8px 12px;
}

.btn-success {
    background-color: #28a745;
    border-color: #28a745;
    padding: 10px 20px;
    font-weight: 500;
}

.btn-success:hover {
    background-color: #218838;
    border-color: #1e7e34;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    padding: 10px 20px;
}
    </style>
</head>

<body>
    <?php $pagina_attiva = 'prenota'; ?>
    <?php include 'header.php'; ?>
    
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 ftco-animate">
                    <h1 class="mb-4">Prenota il tuo tavolo</h1>
                    <p class="mb-4">Assicurati il tuo posto nella nostra autentica pizzeria italiana</p>
                </div>
            </div>
        </div>
    </section>
    
    <section class="ftco-section">
        <div class="container">
            <div class="row">
                <!-- Form di prenotazione -->
                <div class="col-md-8 ftco-animate">
                    <div class="reservation-form">
    <h3 class="mb-4">Nuova Prenotazione</h3>
    
    <?php if (!empty($messaggio)): ?>
        <div class="alert alert-<?php echo $tipo_messaggio; ?>">
            <?php echo htmlspecialchars($messaggio); ?>
        </div>
    <?php endif; ?>
    
    <?php if ($step == 1): ?>
        <!-- Step 1: Selezione base -->
        <form method="POST" action="">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="data">Data</label>
                        <input type="date" class="form-control" id="data" name="data" 
                               min="<?php echo date('Y-m-d'); ?>" 
                               max="<?php echo date('Y-m-d', strtotime('+30 days')); ?>" 
                               value="<?php echo isset($_POST['data']) ? $_POST['data'] : ''; ?>"
                               required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="orario">Orario</label>
                        <select class="form-control" id="orario" name="orario" required>
                            <option value="">Seleziona orario</option>
                            <option value="pomeriggio" <?php echo (isset($_POST['orario']) && $_POST['orario'] == 'pomeriggio') ? 'selected' : ''; ?>>Pranzo (12:00 - 15:00)</option>
                            <option value="sera" <?php echo (isset($_POST['orario']) && $_POST['orario'] == 'sera') ? 'selected' : ''; ?>>Cena (19:00 - 23:00)</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="numero_posti">Numero di posti</label>
                <select class="form-control" id="numero_posti" name="numero_posti" required>
                    <option value="">Seleziona numero di posti</option>
                    <option value="2" <?php echo (isset($_POST['numero_posti']) && $_POST['numero_posti'] == '2') ? 'selected' : ''; ?>>2 persone</option>
                    <option value="4" <?php echo (isset($_POST['numero_posti']) && $_POST['numero_posti'] == '4') ? 'selected' : ''; ?>>4 persone</option>
                    <option value="6" <?php echo (isset($_POST['numero_posti']) && $_POST['numero_posti'] == '6') ? 'selected' : ''; ?>>6 persone</option>
                    <option value="8" <?php echo (isset($_POST['numero_posti']) && $_POST['numero_posti'] == '8') ? 'selected' : ''; ?>>8 persone</option>
                    <option value="10" <?php echo (isset($_POST['numero_posti']) && $_POST['numero_posti'] == '10') ? 'selected' : ''; ?>>10 persone</option>
                </select>
            </div>
            
            <div class="form-group text-center">
                <button type="submit" name="cerca_tavoli" class="btn btn-reservation">
                    <span class="icon-search mr-2"></span>
                    Cerca Tavoli Disponibili
                </button>
            </div>
        </form>
        
    <?php elseif ($step == 2): ?>
        <!-- Step 2: Selezione tavolo -->
        <div class="mb-4">
            <h5>Tavoli disponibili per <?php echo $_POST['numero_posti']; ?> persone</h5>
            <p class="text-muted">Data: <?php echo date('d/m/Y', strtotime($_POST['data'])); ?> - <?php echo ucfirst($_POST['orario']); ?></p>
        </div>
        
        <?php if (!empty($tavoli_disponibili)): ?>
            <div class="row">
                <?php foreach ($tavoli_disponibili as $tavolo): ?>
                    <div class="col-md-6 mb-3">
                        <div class="card tavolo-card">
                            <div class="card-body text-center">
                                <h5 class="card-title">Tavolo <?php echo $tavolo['id_tavolo']; ?></h5>
                                <p class="card-text">
                                    <span class="badge badge-info"><?php echo $tavolo['numero_posti']; ?> posti</span>
                                </p>
                                <form method="POST" action="" style="display: inline;">
                                    <input type="hidden" name="id_tavolo" value="<?php echo $tavolo['id_tavolo']; ?>">
                                    <input type="hidden" name="data" value="<?php echo $_POST['data']; ?>">
                                    <input type="hidden" name="orario" value="<?php echo $_POST['orario']; ?>">
                                    <input type="hidden" name="numero_posti" value="<?php echo $_POST['numero_posti']; ?>">
                                    <button type="submit" name="prenota_tavolo" class="btn btn-success btn-sm">
                                        Prenota questo tavolo
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center mt-4">
                <form method="POST" action="">
                    <button type="submit" class="btn btn-secondary">
                        <span class="icon-arrow-left mr-2"></span>
                        Torna alla ricerca
                    </button>
                </form>
            </div>
            
        <?php else: ?>
            <div class="alert alert-warning">
                <strong>Nessun tavolo disponibile!</strong><br>
                Non ci sono tavoli disponibili per <?php echo $_POST['numero_posti']; ?> persone nella data e orario selezionati.
            </div>
            
            <div class="text-center">
                <form method="POST" action="">
                    <button type="submit" class="btn btn-secondary">
                        <span class="icon-arrow-left mr-2"></span>
                        Torna alla ricerca
                    </button>
                </form>
            </div>
        <?php endif; ?>
        
    <?php endif; ?>
</div>
                </div>
                
                <!-- Informazioni e orari -->
                <div class="col-md-4 ftco-animate">
                    <div class="reservation-form">
                        <h4 class="mb-4">Informazioni</h4>
                        
                        <div class="mb-4">
                            <h5><span class="icon-clock-o mr-2"></span>Orari di apertura</h5>
                            <p class="mb-2"><strong>Pranzo:</strong> 12:00 - 15:00</p>
                            <p><strong>Cena:</strong> 19:00 - 23:00</p>
                        </div>
                        
                        <div class="mb-4">
                            <h5><span class="icon-phone mr-2"></span>Contatti</h5>
                            <p class="mb-2">Tel: 000 (123) 456 7890</p>
                            <p>Email: info@lmpizzeria.com</p>
                        </div>
                        
                        <div class="mb-4">
                            <h5><span class="icon-info mr-2"></span>Note</h5>
                            <p class="small">Le prenotazioni possono essere effettuate fino a 30 giorni in anticipo. 
                            Per gruppi superiori a 10 persone, contattaci telefonicamente.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Le tue prenotazioni -->
            <?php if (!empty($prenotazioni)): ?>
            <div class="row mt-5">
                <div class="col-md-12 ftco-animate">
                    <h3 class="mb-4">Le tue prenotazioni</h3>
                    
                    <?php foreach ($prenotazioni as $prenotazione): ?>
                        <div class="reservation-card">
                            <h5>Prenotazione #<?php echo $prenotazione['id_tavolo']; ?></h5>
                            <div class="reservation-info">
                                <span>Data:</span>
                                <span><?php echo date('d/m/Y', strtotime($prenotazione['data'])); ?></span>
                            </div>
                            <div class="reservation-info">
                                <span>Orario:</span>
                                <span><?php echo ucfirst($prenotazione['orario']); ?></span>
                            </div>
                            <div class="reservation-info">
                                <span>Tavolo:</span>
                                <span><?php echo $prenotazione['id_tavolo']; ?> (<?php echo $prenotazione['numero_posti']; ?> posti)</span>
                            </div>
                            <div class="reservation-info">
                                <span>Stato:</span>
                                <span class="badge badge-success">Confermata</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>
    
    <?php include 'footer.php'; ?>
    
    <!-- loader -->
    <div id="ftco-loader" class="show fullscreen">
        <svg class="circular" width="48px" height="48px">
            <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/>
            <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/>
        </svg>
    </div>
    
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
    <script src="js/main.js"></script>
    
    <script>
        // Impostare la data minima a oggi
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('data');
            const today = new Date().toISOString().split('T')[0];
            dateInput.min = today;
            
            // Impostare la data massima a 30 giorni da oggi
            const maxDate = new Date();
            maxDate.setDate(maxDate.getDate() + 30);
            dateInput.max = maxDate.toISOString().split('T')[0];
        });
    </script>
</body>
</html>