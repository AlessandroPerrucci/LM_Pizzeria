<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" href="<?= $link_prefix ?>index.php">
            <span class="flaticon-pizza-1 mr-1"></span>L.M.<br><small>Pizzeria</small>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" 
                aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="oi oi-menu"></span> Menu
        </button>
        
        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item <?= (!$in_subdirectory && ($pagina_attiva ?? '') === 'home') ? 'active' : '' ?>">
                    <a href="<?= $link_prefix ?>../index.php" class="nav-link">Home</a>
                </li>
                <li class="nav-item <?= (!$in_subdirectory && ($pagina_attiva ?? '') === 'menu') ? 'active' : '' ?>">
                    <a href="<?= $link_prefix ?>../menu.php" class="nav-link">Menu</a>
                </li>
                <li class="nav-item <?= (!$in_subdirectory && ($pagina_attiva ?? '') === 'services') ? 'active' : '' ?>">
                    <a href="<?= $link_prefix ?>../services.php" class="nav-link">Services</a>
                </li>
                <li class="nav-item <?= (!$in_subdirectory && ($pagina_attiva ?? '') === 'blog') ? 'active' : '' ?>">
                    <a href="<?= $link_prefix ?>../blog.php" class="nav-link">Blog</a>
                </li>
                <li class="nav-item <?= (!$in_subdirectory && ($pagina_attiva ?? '') === 'about') ? 'active' : '' ?>">
                    <a href="<?= $link_prefix ?>../about.php" class="nav-link">Chi Siamo</a>
                </li>
                <li class="nav-item <?= (!$in_subdirectory && ($pagina_attiva ?? '') === 'contact') ? 'active' : '' ?>">
                    <a href="<?= $link_prefix ?>../contact.php" class="nav-link">Contatti</a>
                </li>
                <li class="nav-item <?= (!$in_subdirectory && ($pagina_attiva ?? '') === 'recensioni') ? 'active' : '' ?>">
                    <a href="<?= isset($_SESSION['user']) ? $link_prefix.'recensioni.php' : $link_prefix.'../rec_notlogin.php' ?>" class="nav-link">
                        Recensioni
                    </a>
                </li>
                
                <li class="nav-item d-flex align-items-center">
                    <?php if (isset($_SESSION['user']) && ($_SESSION['user']['gruppo'] ?? '') === 'admin'): ?>
                        <a href="<?= $link_prefix ?>../admin.php" class="btn btn-primary mr-2">Admin</a>
                    <?php endif; ?>
                </li>
                <li class="nav-item d-flex align-items-center">
                    <?php if (isset($_SESSION['user'])): ?>
                        <?php
                        $nickname_completo = htmlspecialchars($_SESSION['user']['nickname'] ?? 'Utente');
                        $nickname = mb_strlen($nickname_completo) > 15 ? mb_substr($nickname_completo, 0, 12) . '…' : $nickname_completo;
                        ?>
                        <a href="<?= $link_prefix ?>../profilo.php"
                            class="btn btn-primary mr-2 btn-nickname"
                            title="<?= $nickname_completo ?>">
                            <?= $nickname ?>
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item d-flex align-items-center">
                        <?php if (isset($_SESSION['user']) && ($_SESSION['user']['gruppo'] ?? '') === 'admin'): ?>
                            <a href="../admin.php" class="btn btn-primary mr-2">Admin</a>
                        <?php endif; ?>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <?php if (isset($_SESSION['user'])): ?>
                            <?php
                            $nickname_completo = htmlspecialchars($_SESSION['user']['nickname'] ?? 'Utente');
                            $nickname = mb_strlen($nickname_completo) > 15 ? mb_substr($nickname_completo, 0, 12) . '…' : $nickname_completo;
                            ?>
                            <a href="../profilo.php"
                                class="btn btn-primary mr-2 btn-nickname"
                                title="<?= $nickname_completo ?>">
                                <?= $nickname ?>
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
        <div class="container-fluid px-3">
            <a class="navbar-brand me-auto" href="index.php">
                <span class="flaticon-pizza-1 mr-1"></span>L.M.<br><small>Pizzeria</small>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
                aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="oi oi-menu"></span> Menu
            </button>

            <div class="collapse navbar-collapse" id="ftco-nav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item <?= ($pagina_attiva ?? '') === 'home' ? 'active' : '' ?>">
                        <a href="index.php" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item <?= ($pagina_attiva ?? '') === 'ordina' || ($pagina_attiva ?? '') === 'menu' ? 'active' : '' ?>">
                        <?php if (isset($_SESSION['user'])){
                            echo '<a href="ordina.php" class="nav-link">Ordina</a>';
                            
                        }
                        else{
                            echo '<a href="menu.php" class="nav-link">Menu</a>';
                        }
                        ?>
                    </li>
                    <li class="nav-item <?= ($pagina_attiva ?? '') === 'ordini' ? 'active' : '' ?>">
                        <?php if (isset($_SESSION['user']) && !(($_SESSION['user']['gruppo'] ?? '') === 'admin')){
                            echo '<a href="ordini.php" class="nav-link">Stato ordini</a>';
                            
                        }
                        ?>
                    </li>
                    <li class="nav-item <?= ($pagina_attiva ?? '') === 'prenota' ? 'active' : '' ?>">
                        <?php if (isset($_SESSION['user']) && !(($_SESSION['user']['gruppo'] ?? '') === 'admin')){
                            echo '<a href="prenotaTavolo.php" class="nav-link">Prenota Tavolo</a>';
                            
                        }
                        ?>
                    </li>
                    <li class="nav-item <?= ($pagina_attiva ?? '') === 'services' ? 'active' : '' ?>">
                        <a href="services.php" class="nav-link">Servizi</a>
                    </li>
                    <li class="nav-item <?= ($pagina_attiva ?? '') === 'blog' ? 'active' : '' ?>">
                        <a href="blog.php" class="nav-link">Blog</a>
                    </li>
                    <li class="nav-item <?= ($pagina_attiva ?? '') === 'about' ? 'active' : '' ?>">
                        <a href="about.php" class="nav-link">Chi siamo</a>
                    </li>
                    <li class="nav-item <?= ($pagina_attiva ?? '') === 'contact' ? 'active' : '' ?>">
                        <a href="contact.php" class="nav-link">Contatti</a>
                    </li>
                    <li class="nav-item <?= ($pagina_attiva ?? '') === 'recensioni' ? 'active' : '' ?>">
                        <a href="<?= isset($_SESSION['user']) ? 'recensioni.php' : 'rec_notlogin.php' ?>" class="nav-link">
                            Recensioni
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item d-flex align-items-center">
                        <?php if (isset($_SESSION['user']) && ($_SESSION['user']['gruppo'] ?? '') === 'admin'): ?>
                            <a href="admin.php" class="btn btn-primary mr-2">Admin</a>
                        <?php endif; ?>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <?php if (isset($_SESSION['user'])): ?>
                            <?php
                            $nickname_completo = htmlspecialchars($_SESSION['user']['nickname'] ?? 'Utente');
                            $nickname = mb_strlen($nickname_completo) > 15 ? mb_substr($nickname_completo, 0, 12) . '…' : $nickname_completo;
                            ?>
                            <a href="profilo.php"
                                class="btn btn-primary mr-2 btn-nickname"
                                title="<?= $nickname_completo ?>">
                                <?= $nickname ?>
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