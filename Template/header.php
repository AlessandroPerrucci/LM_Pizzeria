<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- onedirup serve per tutte quelle pagine annidate una directory sopra template, ad esempio la dir admin-->
<?php if ($pagina_attiva == 'onedirup'): ?>
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php"><span class="flaticon-pizza-1 mr-1"></span>L.M.<br><small>Pizzeria</small></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="oi oi-menu"></span> Menu
            </button>
            <div class="collapse navbar-collapse" id="ftco-nav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a href="../index.php" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="../menu.php" class="nav-link">Menu</a></li>
                    <li class="nav-item"><a href="../services.php" class="nav-link">Services</a></li>
                    <li class="nav-item"><a href="../blog.php" class="nav-link">Blog</a></li>
                    <li class="nav-item"><a href="../about.php" class="nav-link">About</a></li>
                    <li class="nav-item"><a href="../contact.php" class="nav-link">Contact</a></li>
                    <li class="nav-item active"><a href="../admin.php" class="nav-link">Admin</a></li>
                    <li class="nav-item d-flex align-items-center">
                        <?php if (isset($_SESSION['user'])): ?>
                            <a href="../profilo.php" class="btn btn-primary mr-2">
                                <?= htmlspecialchars($_SESSION['user']['nickname']) ?>
                            </a>
                            <a href="../logout.php" class="btn btn-outline-light">Logout</a>
                        <?php else: ?>
                            <a href="../login.php" class="btn btn-primary">Login</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<?php else: ?>
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <span class="flaticon-pizza-1 mr-1"></span>L.M.<br><small>Pizzeria</small>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
                aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="oi oi-menu"></span> Menu
            </button>

            <div class="collapse navbar-collapse" id="ftco-nav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item <?= ($pagina_attiva ?? '') === 'home' ? 'active' : '' ?>">
                        <a href="index.php" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item <?= ($pagina_attiva ?? '') === 'menu' ? 'active' : '' ?>">
                        <a href="menu.php" class="nav-link">Menu</a>
                    </li>
                    <li class="nav-item <?= ($pagina_attiva ?? '') === 'services' ? 'active' : '' ?>">
                        <a href="services.php" class="nav-link">Services</a>
                    </li>
                    <li class="nav-item <?= ($pagina_attiva ?? '') === 'blog' ? 'active' : '' ?>">
                        <a href="blog.php" class="nav-link">Blog</a>
                    </li>
                    <li class="nav-item <?= ($pagina_attiva ?? '') === 'about' ? 'active' : '' ?>">
                        <a href="about.php" class="nav-link">About</a>
                    </li>
                    <li class="nav-item <?= ($pagina_attiva ?? '') === 'contact' ? 'active' : '' ?>">
                        <a href="contact.php" class="nav-link">Contact</a>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <?php if (isset($_SESSION['user']) && ($_SESSION['user']['gruppo'] ?? '') === 'admin'): ?>
                            <a href="admin.php" class="btn btn-primary mr-2">Admin</a>
                        <?php endif; ?>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <?php if (isset($_SESSION['user'])): ?>
                            <a href="profilo.php" class="btn btn-primary mr-2">
                                <?= htmlspecialchars($_SESSION['user']['nickname']) ?>
                            </a>
                            <a href="logout.php" class="btn btn-outline-light">Logout</a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-primary">Login</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<?php endif; ?>