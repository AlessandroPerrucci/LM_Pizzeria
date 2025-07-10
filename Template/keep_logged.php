<?php 
if (!isset($_SESSION['user']) && isset($_COOKIE['email_user'])) {
    require_once 'config.php';

    // Carica l'utente in sessione partendo dal cookie
    $stmt = $pdo->prepare("SELECT * FROM utente WHERE email = :email");
    $stmt->execute(['email' => $_COOKIE['email_user']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user'] = $user;
    } else {
        setcookie("email_user", "", time() - 3600, "/"); // cancella cookie se l'utente non esiste
    }
}
?>