<?php
session_start();
    include_once 'db_connect.php'; //import database connection
    include_once 'database_functions.php'; //import database functions
    $email = $_SESSION["email"];
    $password = $_SESSION["pass"];
    $user = getUserField($email,"username");
    echo $email . '<br>';
    echo $password . '<br>';

    echo "welcome " . $user;
?>

<html>
<?php include 'footer.php'; ?>
</html>