<?php 
session_start();
// Import database connection & functions
include_once 'db_connect.php';
include_once 'database_functions.php';

$text = ""; 
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (verifyAdmin($_POST["email"], $_POST["pass"])) {
        echo "success here <br>";
        $_SESSION["email"]= $_POST["email"];
        $_SESSION["pass"] = $_POST["pass"];
        header("Location: admin_page.php"); // Redirect if successful
        exit();
    } else if($_POST["email"] == "" && $_POST["pass"] == ""){
        $text = ""; // Message to display for empty input
    }else{
        $text = "log in failed"; // Message to display on failed login
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Admin log in</title>
    <!--<link href="css/style.css" rel="stylesheet"> -->
    </head>
    <body> 
        <h2>Admin log in credentials</h1>
        <form action="admin_login.php" method="POST">
            Email: <input type="text" name="email"><br>
            Password: <input type="text" name="pass"><br>
            <input type="submit">
        </form>

        <p><?= $text ?></p> <!-- Display error message -->
        <p> create new admin accounts: </p>
        <a href="admin_signup.php">Sign in Admin</a>
    </body>
    <?php include 'footer.php'; ?>
</html>