<?php 
include_once "database_functions.php";
include_once "db_connect.php";
$occupations = [
    'Website Manager',
    'Worker',
    'Database Manager'
];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $occupation = $_POST["occupation"];
    $email = $_POST["email"];
    $pass = $_POST["pass"];
    addAdmin($firstname,$lastname,$occupation,$email,$pass);
}
?>
<!DOCTYPE html>
<html>
<?= "<br>" ?>
<?= getAllAdmin(); ?>
    <form action="admin_signup.php" method="POST">
            firstname: <input type="text" name="firstname"><br>
            lastname: <input type="text" name="lastname"><br>
            occupation: <select name="occupation"> 
                <?php foreach ($occupations as $option): ?>
                <option value="<?= htmlspecialchars($option) ?>"><?= htmlspecialchars($option) ?></option>
                <?php endforeach; ?> 
            </select> <br>
            Email: <input type="email" name="email"><br>
            Password: <input type="text" name="pass"><br>
            <input type="submit">
    </form>
    <?php include 'footer.php'; ?>
</html>