<?php 
include_once "database_functions.php";
include_once "db_connect.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $pass = $_POST["pass"];
    addUser($username,$email,$pass);
}
?>
<html>
<?= "<br>" ?>
<?= "print should be here" ?> <br>
<?= getAllUser(); ?>
    <form action="sign_up.php" method="POST">
            Username: <input type="text" name="username"><br>
            Email: <input type="email" name="email"><br>
            Password: <input type="text" name="pass"><br>
            <input type="submit">
    </form>
    <?php include 'footer.php'; ?>
</html>