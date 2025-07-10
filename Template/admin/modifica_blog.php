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
    $author = $_SESSION['user']['nome'] ?? 'Anonimo';
    $image = $_POST['image'] ?? '';
    
    if ($title && $content) {
        $stmt = $pdo->prepare("INSERT INTO blog_posts (title, content, author, image, created_at) VALUES (:title, :content, :author, :image, NOW())");
        $stmt->execute(['title' => $title, 'content' => $content, 'author' => $author, 'image' => $image]);
        $msg = "Post creato con successo.";
    }
}

// [!!] MODIFICA post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifica_post'])) {
    $id = $_POST['post_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_POST['image'];

    $stmt = $pdo->prepare("UPDATE blog_posts SET title = :title, content = :content, image = :image WHERE id = :id");
    $stmt->execute(['title' => $title, 'content' => $content, 'image' => $image, 'id' => $id]);
    $msg = "Post modificato.";
}

// [!!] ELIMINA post
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM blog_posts WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $msg = "Post eliminato.";
}

// [!!] RECUPERA tutti i post
$posts = $pdo->query("SELECT * FROM blog_posts ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gestione Blog</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include '../header.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4">Gestione Blog</h1>
    <?php if (isset($msg)): ?>
        <div class="alert alert-success"> <?= htmlspecialchars($msg) ?> </div>
    <?php endif; ?>

    <!-- [!!] Form creazione nuovo post -->
    <form method="POST" class="mb-5">
        <h3>Crea Nuovo Post</h3>
        <input type="text" name="title" placeholder="Titolo" class="form-control mb-2" required>
        <textarea name="content" placeholder="Contenuto" class="form-control mb-2" rows="5" required></textarea>
        <input type="text" name="image" placeholder="URL Immagine (opzionale)" class="form-control mb-2">
        <button type="submit" name="crea_post" class="btn btn-success">Crea</button>
    </form>

    <!-- [!!] Lista dei post -->
    <h3>Post Esistenti</h3>
    <?php foreach ($posts as $post): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title"> <?= htmlspecialchars($post['title']) ?> </h5>
                <p><small>Autore: <?= htmlspecialchars($post['author']) ?> | <?= $post['created_at'] ?></small></p>
                <form method="POST" class="mb-2">
                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                    <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" class="form-control mb-1">
                    <textarea name="content" class="form-control mb-1" rows="3"><?= htmlspecialchars($post['content']) ?></textarea>
                    <input type="text" name="image" value="<?= htmlspecialchars($post['image']) ?>" class="form-control mb-1">
                    <button type="submit" name="modifica_post" class="btn btn-primary btn-sm">Modifica</button>
                    <a href="?delete=<?= $post['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Sei sicuro?')">Elimina</a>
                </form>
            </div>
        </div>
    <?php endforeach; ?>

    <a href="../admin.php" class="btn btn-secondary mt-4">Torna al pannello admin</a>
</div>

<?php include '../footer.php'; ?>
</body>
</html>
