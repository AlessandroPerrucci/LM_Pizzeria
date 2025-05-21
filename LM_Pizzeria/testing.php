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
        <?php print("Return here: " . searchData("utente", "email", str("default@email.com"),"password")[0] . "<br>"); ?> <br>
        <?php print("Return here: " . searchData("Utente", "email", str("default@email.com"),"password")[0] . "<br>"); ?> <br>
        <?php print("get PK: " . getPK("utente") . "<br>" ); ?> <br>
          <?php print("getData: " . getData("Utente",str("default@email.com"), "password") . "<br>" );?> <br>
    </body>
</html>


this works correctly: SELECT * FROM Utenti WHERE email = "default@email.com"
this does not SELECT * FROM utenti WHERE email = "default@email.com" --> table needs capital