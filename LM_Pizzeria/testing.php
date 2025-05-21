<?php include_once 'functions.php' ?>
<!DOCTYPE html>
<html>
    <head>
        <title>The Greatest Website Ever</title>
        <meta charset="UTF-8">
    </head>
    <body>
        <h1> pagina di testing </h1> <br>
        <p> Test connessione MySQL <p> <br>
        <?php print("conncetion test: ");
            if ($connection->connect_error) {
                echo "Connection error: " . $connection->connect_error;
            } else {
                echo "✅ Connected successfully to Altervista MySQL";
            }
            ?> <br>
        <?php print("data here: " . searchData("Utenti", "Email", str("default@gmail.com"),"password")[0]); ?> <br>
        <?php print("get PK: " . getPK("utenti")); ?> <br>
        <?php print("getData: " . getData("utenti",str("default@email.com"), "password"));?> <br>
    </body>
</html>

INSERT INTO Utenti (Email, password) VALUES ("default@email.com", "default12345")