<?php
session_start();
require_once("../config.php");

// [!!] Controllo accesso admin
if (!isset($_SESSION['user']) || ($_SESSION['user']['gruppo'] ?? '') !== 'admin') {
    echo "Accesso riservato agli amministratori.";
    exit();
}

// [!!] CREA nuovo post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crea_post'])) {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $subtitle = $_POST['subtitle'] ?? '';
    $author = $_SESSION['user']['nome'] ?? 'Anonimo';
    $category_id = $_POST['category_id'] ?? null;
    $image = '';
    
    // Gestione upload immagine
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/blog/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($fileExtension, $allowedExtensions)) {
            $fileName = uniqid() . '.' . $fileExtension;
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $image = 'uploads/blog/' . $fileName;
            } else {
                $msg = "Errore nell'upload dell'immagine.";
            }
        } else {
            $msg = "Formato immagine non supportato. Usa JPG, PNG, GIF o WebP.";
        }
    }
    
    if ($title && $content && !isset($msg)) {
        $stmt = $pdo->prepare("INSERT INTO blog_posts (title, subtitle, content, author, image, category_id, created_at) VALUES (:title, :subtitle, :content, :author, :image, :category_id, NOW())");
        $stmt->execute([
            'title' => $title, 
            'subtitle' => $subtitle,
            'content' => $content, 
            'author' => $author, 
            'image' => $image,
            'category_id' => $category_id
        ]);
        $msg = "Post creato con successo.";
    }
}

// [!!] MODIFICA post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifica_post'])) {
    $id = $_POST['post_id'];
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $content = $_POST['content'];
    $category_id = $_POST['category_id'];
    $image = $_POST['current_image']; // Mantieni l'immagine esistente come default

    // Gestione upload nuova immagine
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/blog/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($fileExtension, $allowedExtensions)) {
            $fileName = uniqid() . '.' . $fileExtension;
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                // Elimina la vecchia immagine se esiste
                if ($image && file_exists('../' . $image)) {
                    unlink('../' . $image);
                }
                $image = 'uploads/blog/' . $fileName;
            }
        }
    }

    $stmt = $pdo->prepare("UPDATE blog_posts SET title = :title, subtitle = :subtitle, content = :content, image = :image, category_id = :category_id WHERE id = :id");
    $stmt->execute([
        'title' => $title, 
        'subtitle' => $subtitle,
        'content' => $content, 
        'image' => $image, 
        'category_id' => $category_id,
        'id' => $id
    ]);
    $msg = "Post modificato.";
}

// [!!] ELIMINA post
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // Recupera l'immagine prima di eliminare il post
    $stmt = $pdo->prepare("SELECT image FROM blog_posts WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Elimina il post
    $stmt = $pdo->prepare("DELETE FROM blog_posts WHERE id = :id");
    $stmt->execute(['id' => $id]);
    
    // Elimina l'immagine se esiste
    if ($post && $post['image'] && file_exists('../' . $post['image'])) {
        unlink('../' . $post['image']);
    }
    
    $msg = "Post eliminato.";
}

// [!!] RECUPERA tutti i post
$posts = $pdo->query("SELECT p.*, c.name as category_name FROM blog_posts p LEFT JOIN blog_categories c ON p.category_id = c.id ORDER BY p.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

// [!!] RECUPERA tutte le categorie
$categories = $pdo->query("SELECT * FROM blog_categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

// Funzione per generare excerpt
function generateExcerpt($text, $maxLength = 100) {
    $text = strip_tags($text);
    if (strlen($text) <= $maxLength) return $text;
    $cut = substr($text, 0, $maxLength);
    $cut = substr($cut, 0, strrpos($cut, ' '));
    return $cut . '...';
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <title>Gestione Blog - Admin</title>
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
    <link rel="stylesheet" href="../css/jquery.timepicker.css">
    <link rel="stylesheet" href="../css/flaticon.css">
    <link rel="stylesheet" href="../css/icomoon.css">
    <link rel="stylesheet" href="../css/style.css">
    
    <style>
        .admin-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            overflow: hidden;
        }
        .admin-card-header {
            background: linear-gradient(135deg, #fac564 0%, #f39c12 100%);
            color: white;
            padding: 20px;
            font-weight: 600;
        }
        .admin-card-body {
            padding: 30px;
        }
        .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 12px 15px;
            margin-bottom: 15px;
            color: #333 !important;
            background-color: #fff !important;
        }
        .form-control:focus {
            color: #333 !important;
            background-color: #fff !important;
            border-color: #fac564;
            box-shadow: 0 0 0 0.2rem rgba(250, 197, 100, 0.25);
        }
        .form-control::placeholder {
            color: #999 !important;
        }
        .btn-admin {
            background: linear-gradient(135deg, #fac564 0%, #f39c12 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(243, 156, 18, 0.4);
            color: white;
        }
        .btn-danger-admin {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            border: none;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-danger-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);
            color: white;
        }
        .post-preview {
            border-left: 4px solid #fac564;
            padding-left: 20px;
        }
        .alert-success {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            border: none;
            color: white;
            border-radius: 8px;
        }
        
        /* STILI STANDARDIZZATI PER LE IMMAGINI */
        .blog-image-container {
            width: 100%;
            height: 200px;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 15px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #dee2e6;
        }
        
        .blog-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.3s ease;
        }
        
        .blog-image:hover {
            transform: scale(1.05);
        }
        
        .blog-image-placeholder {
            color: #6c757d;
            font-size: 3rem;
            text-align: center;
        }
        
        /* Immagine corrente più piccola */
        .current-image-container {
            width: 80px;
            height: 60px;
            border-radius: 4px;
            overflow: hidden;
            display: inline-block;
            margin-bottom: 10px;
            border: 1px solid #dee2e6;
        }
        
        .current-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
        
        /* Preview dell'immagine caricata */
        .image-preview {
            max-width: 150px;
            max-height: 100px;
            border-radius: 4px;
            margin-top: 10px;
            border: 1px solid #dee2e6;
            object-fit: cover;
        }
        
        /* File upload styling */
        .file-upload-wrapper {
            position: relative;
            margin-bottom: 15px;
        }
        
        .file-upload-input {
            position: absolute;
            left: -9999px;
            opacity: 0;
        }
        
        .file-upload-label {
            display: block;
            padding: 15px 20px;
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #6c757d;
        }
        
        .file-upload-label:hover {
            background: #e9ecef;
            border-color: #fac564;
            color: #495057;
        }
        
        .file-upload-label.has-file {
            background: #d1ecf1;
            border-color: #bee5eb;
            color: #0c5460;
        }
        
        .file-upload-label i {
            display: block;
            margin-bottom: 5px;
            font-size: 1.5rem;
        }
        
        .file-upload-label small {
            font-size: 0.8rem;
            opacity: 0.7;
        }
    </style>
</head>

<body>
    <?php $pagina_attiva = 'admin'; ?>
    <?php include '../header.php'; ?>

    <!-- Header Section -->
    <section class="home-slider owl-carousel img" style="background-image: url(../images/bg_1.jpg);">
        <div class="slider-item" style="background-image: url(../images/bg_3.jpg);">
            <div class="overlay"></div>
            <div class="container">
                <div class="row slider-text justify-content-center align-items-center">
                    <div class="col-md-7 col-sm-12 text-center ftco-animate">
                        <h1 class="mb-3 mt-5 bread">Gestione Blog</h1>
                        <p class="breadcrumbs"><span class="mr-2"><a href="../index.php">Home</a></span> <span class="mr-2"><a href="../admin.php">Admin</a></span> <span>Blog</span></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="ftco-section">
        <div class="container">
            
            <?php if (isset($msg)): ?>
                <div class="alert alert-success ftco-animate"> 
                    <?= htmlspecialchars($msg) ?> 
                </div>
            <?php endif; ?>

            <!-- Form creazione nuovo post -->
            <div class="admin-card ftco-animate">
                <div class="admin-card-header">
                    <h3 class="mb-0">Crea Nuovo Post</h3>
                </div>
                <div class="admin-card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="title" placeholder="Titolo del post" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="subtitle" placeholder="Sottotitolo (opzionale)" class="form-control">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="file-upload-wrapper">
                                    <input type="file" name="image" id="image-upload" class="file-upload-input" accept="image/*" onchange="previewImage(this, 'image-preview')">
                                    <label for="image-upload" class="file-upload-label">
                                        <i class="ion-ios-camera"></i> Seleziona Immagine
                                        <br><small>JPG, PNG, GIF, WebP</small>
                                    </label>
                                    <img id="image-preview" class="image-preview" style="display: none;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <select name="category_id" class="form-control">
                                    <option value="">Seleziona categoria</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <textarea name="content" placeholder="Contenuto del post" class="form-control" rows="8" required></textarea>
                        
                        <button type="submit" name="crea_post" class="btn btn-admin">
                            <i class="ion-ios-add"></i> Crea Post
                        </button>
                    </form>
                </div>
            </div>

            <!-- Lista dei post esistenti -->
            <div class="row justify-content-center mb-5 pb-3">
                <div class="col-md-7 heading-section ftco-animate text-center">
                    <h2 class="mb-4">Post Esistenti</h2>
                    <p>Gestisci tutti i post del blog. Modifica o elimina i contenuti esistenti.</p>
                </div>
            </div>

            <div class="row">
                <?php foreach ($posts as $post): ?>
                    <div class="col-md-6 ftco-animate">
                        <div class="admin-card">
                            <div class="admin-card-header">
                                <h4 class="mb-0"><?= htmlspecialchars($post['title']) ?></h4>
                                <small>
                                    <?= htmlspecialchars($post['author']) ?> • 
                                    <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?>
                                    <?php if ($post['category_name']): ?>
                                        • <?= htmlspecialchars($post['category_name']) ?>
                                    <?php endif; ?>
                                </small>
                            </div>
                            <div class="admin-card-body">
                                
                                <!-- Anteprima post con immagine standardizzata -->
                                <div class="post-preview mb-4">
                                    <div class="blog-image-container">
                                        <?php if ($post['image']): ?>
                                            <img src="../<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="blog-image">
                                        <?php else: ?>
                                            <div class="blog-image-placeholder">
                                                <i class="ion-ios-image-outline"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if ($post['subtitle']): ?>
                                        <p class="text-muted mb-2"><em><?= htmlspecialchars($post['subtitle']) ?></em></p>
                                    <?php endif; ?>
                                    
                                    <p><?= htmlspecialchars(generateExcerpt($post['content'], 150)) ?></p>
                                </div>

                                <!-- Form modifica -->
                                <form method="POST" enctype="multipart/form-data" class="mb-3">
                                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                    <input type="hidden" name="current_image" value="<?= htmlspecialchars($post['image']) ?>">
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" class="form-control" placeholder="Titolo">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="subtitle" value="<?= htmlspecialchars($post['subtitle'] ?? '') ?>" class="form-control" placeholder="Sottotitolo">
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php if ($post['image']): ?>
                                                <div class="mb-2">
                                                    <small class="text-muted">Immagine corrente:</small><br>
                                                    <div class="current-image-container">
                                                        <img src="../<?= htmlspecialchars($post['image']) ?>" alt="Current" class="current-image">
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <div class="file-upload-wrapper">
                                                <input type="file" name="image" id="image-upload-<?= $post['id'] ?>" class="file-upload-input" accept="image/*" onchange="previewImage(this, 'image-preview-<?= $post['id'] ?>')">
                                                <label for="image-upload-<?= $post['id'] ?>" class="file-upload-label">
                                                    <i class="ion-ios-camera"></i> <?= $post['image'] ? 'Cambia Immagine' : 'Seleziona Immagine' ?>
                                                    <br><small>JPG, PNG, GIF, WebP</small>
                                                </label>
                                                <img id="image-preview-<?= $post['id'] ?>" class="image-preview" style="display: none;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <select name="category_id" class="form-control">
                                                <option value="">Seleziona categoria</option>
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?= $category['id'] ?>" 
                                                        <?= $post['category_id'] == $category['id'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($category['name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <textarea name="content" class="form-control" rows="6" placeholder="Contenuto"><?= htmlspecialchars($post['content']) ?></textarea>
                                    
                                    <div class="mt-3">
                                        <button type="submit" name="modifica_post" class="btn btn-admin btn-sm">
                                            <i class="ion-ios-create"></i> Modifica
                                        </button>
                                        <a href="?delete=<?= $post['id'] ?>" class="btn btn-danger-admin btn-sm ml-2" 
                                           onclick="return confirm('Sei sicuro di voler eliminare questo post?')">
                                            <i class="ion-ios-trash"></i> Elimina
                                        </a>
                                        <a href="../blog-single.php?id=<?= $post['id'] ?>" class="btn btn-outline-primary btn-sm ml-2" target="_blank">
                                            <i class="ion-ios-eye"></i> Visualizza
                                        </a>
                                    </div>
                                </form>
                                
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pulsante ritorno -->
            <div class="text-center mt-5">
                <a href="../admin.php" class="btn btn-outline-primary">
                    <i class="ion-ios-arrow-back"></i> Torna al Pannello Admin
                </a>
            </div>

        </div>
    </section>

    <?php include '../footer.php'; ?>

    <!-- loader -->
    <div id="ftco-loader" class="show fullscreen">
        <svg class="circular" width="48px" height="48px">
            <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
            <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00" />
        </svg>
    </div>

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
    <script src="../js/jquery.animateNumber.min.js"></script>
    <script src="../js/bootstrap-datepicker.js"></script>
    <script src="../js/jquery.timepicker.min.js"></script>
    <script src="../js/scrollax.min.js"></script>
    <script src="../js/main.js"></script>
    
    <script>
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const label = input.nextElementSibling;
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    label.classList.add('has-file');
                    label.innerHTML = '<i class="ion-ios-checkmark"></i> Immagine selezionata<br><small>Clicca per cambiare</small>';
                }
                
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.style.display = 'none';
                label.classList.remove('has-file');
                label.innerHTML = '<i class="ion-ios-camera"></i> Seleziona Immagine<br><small>JPG, PNG, GIF, WebP</small>';
            }
        }
    </script>

</body>

</html>