<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Controlla se siamo in una sottocartella (come admin)
$in_subdirectory = (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false) || 
                   (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false) ||
                   (isset($pagina_attiva) && $pagina_attiva == 'onedirup');

// Imposta il prefisso per i link
$link_prefix = $in_subdirectory ? '../' : '';

// Controlla se abbiamo già config.php incluso
if (!isset($pdo)) {
    $config_path = $in_subdirectory ? '../config.php' : 'config.php';
    if (file_exists($config_path)) {
        require_once $config_path;
    }
}

// Recenti per il footer
if (isset($pdo)) {
    $recentStmt = $pdo->prepare("
        SELECT p.id, p.title, p.created_at, p.image, p.author, COUNT(c.id) AS comment_count
        FROM blog_posts p
        LEFT JOIN blog_comments c ON p.id = c.post_id
        GROUP BY p.id
        ORDER BY p.created_at DESC
        LIMIT 2
    ");
    $recentStmt->execute();
    $recentPosts = $recentStmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $recentPosts = [];
}

if (!function_exists('generateExcerpt')) {
    function generateExcerpt($text, $maxLength = 100)
    {
        $text = strip_tags($text);
        if (strlen($text) <= $maxLength) return $text;
        $cut = substr($text, 0, $maxLength);
        $cut = substr($cut, 0, strrpos($cut, ' '));
        return $cut . '...';
    }
}
?>

<footer class="ftco-footer ftco-section img">
    <div class="overlay"></div>
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-3 col-md-6 mb-5 mb-md-5">
                <div class="ftco-footer-widget mb-4">
                    <h2 class="ftco-heading-2">Chi Siamo</h2>
                    <p>Artigiani della pizza autentica, che uniscono le ricette tradizionali italiane ai migliori ingredienti locali. Ogni pizza è preparata a mano con passione, racchiudendo generazioni di tradizione italiana in ogni morso.</p>
                    <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
                        <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
                        <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
                        <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-5 mb-md-5">
                <div class="ftco-footer-widget mb-4">
                    <h2 class="ftco-heading-2">Blog recenti</h2>
                    <?php foreach ($recentPosts as $r): ?>
                        <div class="block-21 mb-4 d-flex">
                            <a
                                class="blog-img mr-4"
                                style="background-image: url('<?= htmlspecialchars($r['image']) ?>');"
                                href="<?= $link_prefix ?>blog-single.php?id=<?= $r['id'] ?>"></a>
                            <div class="text">
                                <h3 class="heading">
                                    <a href="<?= $link_prefix ?>blog-single.php?id=<?= $r['id'] ?>">
                                        <?= htmlspecialchars($r['title']) ?>
                                    </a>
                                </h3>
                                <div class="meta">
                                    <div>
                                        <a href="#">
                                            <span class="icon-calendar"></span>
                                            <?= date('M d, Y', strtotime($r['created_at'])) ?>
                                        </a>
                                    </div>
                                    <div>
                                        <a href="#"><span class="icon-person"></span><?= htmlspecialchars($r['author']) ?></a>
                                    </div>
                                    <div>
                                        <a href="#" class="meta-chat"><span class="icon-chat"></span><?= $r['comment_count'] ?? 0 ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 mb-5 mb-md-5">
                <div class="ftco-footer-widget mb-4 ml-md-4">
                    <h2 class="ftco-heading-2">Services</h2>
                    <ul class="list-unstyled">
                        <li><a href="#" class="py-2 d-block">Cucina</a></li>
                        <li><a href="#" class="py-2 d-block">Consegne</a></li>
                        <li><a href="#" class="py-2 d-block">Qualità dei prodotti</a></li>
                        <li><a href="#" class="py-2 d-block">Misto</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-5 mb-md-5">
                <div class="ftco-footer-widget mb-4">
                    <h2 class="ftco-heading-2">Hai delle domande?</h2>
                    <div class="block-23 mb-3">
                        <ul>
                            <li><span class="icon icon-map-marker"></span><span class="text">Via Antonio Cannavacciuolo, 69, Italia, Napoli, NA, 80125</span></li>
                            <li><a href="#"><span class="icon icon-phone"></span><span class="text">+39 345 571 947</span></a></li>
                            <li><a href="#"><span class="icon icon-envelope"></span><span class="text">info@LMPizza.com</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    Copyright &copy;<script>
                        document.write(new Date().getFullYear());
                    </script> All rights reserved | This template is made with <i class="icon-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
            </div>
        </div>
    </div>
</footer>