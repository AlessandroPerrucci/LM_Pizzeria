<?php
session_start();
    include_once 'db_connect.php'; //import database connection
    include_once 'database_functions.php'; //import database functions
    $email = $_SESSION["email"];
    $password = $_SESSION["pass"];
    $user = getAdminData($email,["firstname"]);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Admin Control</title>
    <!--<link href="css/style.css" rel="stylesheet"> -->
    </head>
    <body>
        <h2>Welcome <?= $user ?> </h2>
    <p>
    </body>

    <?php include 'footer.php'; ?>
</html>