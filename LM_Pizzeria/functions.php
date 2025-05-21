<?php 
include_once 'db_connect.php';

/**
 * Simple function that makes queries easier to read
 * 
 * @param string $query     the query to execute
 * 
 * @return query    returns raw query data (unusable directly)
 */
function quer($query)
{
    $que = conn()->query($query);
    return $que;
}

/**
 * Function that returns the Name of the primary key column,
 * this has to be set up manually in the code, it doesn't do any query
 * 
 * @param string $table   the table name (i.e "users", "foods", etc...)
 * 
 * @return string   the name of the primary key of $table
 */
function getPK($table)
{
    switch ($table) {
        case "utenti":
            return "email";
        case "pizze":
            return "ID_pizza";
        case "ordine":
            return "ID_ordine";
        case "servizio":
            return "ID_servizio";
        default:
            return "ID";
    }
}
/**
 *  prevent SQL injection by identifying symbols
 * 
 * @param string $string    the string that needs to be sanitized
 * 
 * @return string   returns a clean string, where all symbols are correctly recognizes (prevents SQL injection)
 */
function sanitizeString($string)
{
    if (is_array($string)) {
        return array_map(__METHOD__, $string);
    }
    if (!empty($string) && is_string($string)) {
        return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $string);
    }
    return $string;
}
/**
 * Removes all or a selection of Symbols from the input and initial and ending spaces.
 * 
 * @param string $input the inputed string you wish to remove symbols from
 * @param array $symbols    an array of singular symbols (or other) you wish to remove (i.g [".", ","])
 * 
 * if $symbols is left empty, than the standard is to remove [' " ', " ' ", '\\', '/', '|', ';', '=', '<', '>', '(', ')']
 * example: input = " myEmai'l@ "return".com " 
 * returns: " myEmail return.com "
 */
function removeSymbols($input, $symbols = null)
{
    if (!is_string($input) || is_null($input) || empty($input)) {
        return null;
    } //invalid input

    if (is_null($symbols)) {
        $symbols = ['"', "'", '\\', '/', '|', ';', '=', '<', '>', '(', ')'];
    }

    foreach ($symbols as $symbol) {
        $input = str_replace($symbol, '', $input); //removes both the symbol and the space
    }
    $input = trim($input); //removes initial and ending spaces
    return $input;
}

/**
 * takes a a variable inputed by a user in the website, and sanitizes based on the expected type, than returns it
 * 
 * @param string $name     the name of the variable (its the name you used in the input field)
 * @param string $type     the expected input type, like: string, int, email, password, etc...
 * 
 * @return mixed    returns sanitized and well formatted values.
 */
function getInput($name, $type = "string")
{
    if (!isset($_POST[$name])) {
        return null;
    } #checks if there is any input to take
    $value = trim($_POST[$name]);
    if (is_null($value)) {
        return null;
    }

    switch ($type) {
        case "string":
            return sanitizeString($value);
        case "int":
            return $value;
        case "email":
            $value = removeSymbols($value);
            return strtolower($value);
    }
}

#vvvvvvvvvvvvvvvvvvvvvvvvvvvv[v THESE FUNCTIONS ARE NOT TO BE USED DIRECTLY, THEY ARE USED BY OTHER FUNCTIONS TO WORK v]vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv

/**
 * 
 * @param string $table    The table that contains the item
 * @param string/int $id       The item ID (Primary Key) used to identify it
 * 
 * @return query    returns the entire query data raw.
 */
function getItem($table, $id)
{
    $pk = getPK($table);
    $query = "SELECT * FROM $table WHERE $pk = '$id'; ";
    return quer($query);
}
/**
 * Searches data from the database without using the PK
 *  returns the query raw, with all the rows with the corresponding value
 * 
 * @param string $table     the name of the table
 * @param string $column    the name of the column to search
 * @param mixed $value      the value to search for, the type string must be correctly quoted by using string($value), otherwise function will fail.
 * 
 * @return mixed
 */
function searchItem($table, $column, $value)
{
    $query = "SELECT * FROM $table WHERE $column = $value";
    return quer($query);
}
#^^^^^^^^^^^^^^^^^^^^^^^^^^^^[^ THESE FUNCTIONS ARE NOT TO BE USED DIRECTLY, THEY ARE USED BY OTHER FUNCTIONS TO WORK ^]^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

/**
 * Returns a specific field value using the PK to search for the item
 * 
 * @param $table    The name of the table you need to query
 * @param $id       The ID of the item
 * @param $field    The desired field of the item
 * 
 * @return value    returns a value of any type (i.e String, int, bool, etc...)
 */
function getData($table, $id, $field)
{
    if (is_null($id)) {
        return null;
    } #minimal filtering
    $table = ucfirst($table); #table name always starts with a capital.
    $item = getItem($table, $id)->fetch_assoc();
    if(is_null($item)){
        return 'Nothing returned';
    }
    return $item;
}
/**
 * Searches a column from the table, for all rows with corresponding value
 * 
 * @param string $table  The desired table
 * @param string $Searchfield  The field to search in
 * @param mixed $Searchvalue   The value of the variable to search
 * @param mixed $returnField   The desired field of the found row to return (i.e password)
 * 
 * example execution:
 * ("users", "email", "default@gmail.com", "username")
 * finds in the "email" column the inputed email, then returns the associated username.
 * 
 * @return mixed $array returns an array of values in the order they are found.
 */
function searchData($table, $searchField, $searchValue, $returnField)
{
    if (is_null($searchField) || is_null($searchValue) || is_null($returnField) ||is_null($table)) {
        return null;
    } #minimal filtering
    echo "stuff: ". $table . "<br>" ;echo  "  " . $searchField . "<br>" ; echo $searchValue . "<br>"; echo $returnField . "<br>";
    $table = ucfirst($table); #table name always starts with a capital.
    echo "capitalized: " . $table . "<br>";
    $result = [];
    $item = searchItem($table, $searchField, $searchValue); #returns whole query of row
    while ($row = $item->fetch_assoc()) {
        $result[] = $row[$returnField];
    }
    echo "result direct: " . $result[0] . "<br>"; 
    echo "result raw: " . print_r($result) . "<br>" ;
    if(count($result)=== 0 ||empty($array)){
        return "Nothing returned";
    }
    return $result;
}

/**
 * Currently just a blue print for a "addUser" function, 
 * to be completed after database is set up
 */
function addUsers($email, $username, $password, $info1, $info2)
{
    $query = "INSERT INTO users (email, username, pass, info1, info2) VALUES 
        (\'$email\',\'$username\', \'$password\',\'$info1\', \'$info2\');";
    quer($query);
    echo "user added succesfully";
}
/**
 * Verifies a user by checking if the password associated with $email in the database
 * correspond to the inputed $pass
 * 
 * @param string $email     The target email
 * @param string $pass      The password to check
 * 
 * @return bool     the password is Corret(TRUE)/Wrong(FALSE)
 */
function verifyUser($email, $pass)
{
    $array = searchData("users", "email", str($email), "pass");
    $password = $array[0];
    echo $password;
    if ($pass === $password) {
        return TRUE;
    } else {
        return FALSE;
    }
}

/**
 * formats an input to be read as a string, str(test) = "test"
 * @param mixed $string     the variable you desire to turn into a formatted string
 * 
 * @return string       correctly formatted string
 */
function str($string)
{
    $string = "\"$string\"";
    return $string;
}

?>