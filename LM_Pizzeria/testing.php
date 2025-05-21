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
        <?php print("email: " . searchData("utente", "email", str("default@email.com"),"email")[0] . "<br>"); ?> <br>
        <?php print("Password: " . searchData("Utente", "email", str("default@email.com"),"password")[0] . "<br>"); ?> <br>
        <?php print("nickname: " . searchData("Utente", "email", str("default@email.com"),"nickname")[0] . "<br>"); ?> <br>
        <?php print("nome: " . searchData("Utente", "email", str("default@email.com"),"nome")[0] . "<br>"); ?> <br>
        <?php print("cognome: " . searchData("Utente", "email", str("default@email.com"),"cognome")[0] . "<br>"); ?> <br>
          <?php print("getData: " . getData("Utente",str("default@email.com"), "password") . "<br>" );?> <br>
        <h3> Everything works as expected! </h3>
    </body>
</html>


