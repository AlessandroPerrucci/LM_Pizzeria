<?php 
session_start();
// Import database connection & functions
include_once 'db_connect.php';
include_once 'database_functions.php';

$text = ""; 
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    print("input 1: " . $_POST["email"] . " | ");
    print("input 2: " . $_POST["pass"] . " | "); 
    $email = getInput("email", "email");
    $pass = getInput("pass", "pass");
    print("input 1: " . $email . " | ");
    print("input 2: " . $pass . " | "); 
    if(is_null($email) || is_null($pass) || !verifyUser($email, $pass)){$text = "log in failed";}
   else{
        echo "success here <br>";
        $_SESSION["email"]= $_POST["email"];
        $_SESSION["pass"] = $_POST["pass"];
        header("Location: user_page.php"); // Redirect if successful
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>The Greatest Website Ever</title>
    <meta charset="UTF-8">
</head>
<body>
    <main>
        <h1>Login Test</h1>
        <p>Please attempt to login successfully</p>

        <form action="Database_test.php" method="POST">
            Email: <input type="text" name="email"><br>
            Password: <input type="text" name="pass"><br>
            <input type="submit">
        </form>

        <p><?= $text ?></p> <!-- Display error message -->
        <p>you don't have an account? make one! </p>
        <a href="sign_up.php">sign up here!</a>
        <br> <br> <br> <br>
        <?php  getAllUser(); ?> <br>
        <?php $text =  "      This \" is 'a' test \" < for input (>) "; ?>
        <?php print("w".$text); ?> <br> <br>
        <?php print("w". removeSymbols($text,["'", '"']));?>
    </main>

    
</body>

<?php include 'footer.php'; ?>
</html>
