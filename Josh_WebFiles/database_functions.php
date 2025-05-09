<?php 
include_once 'db_connect.php';

/**
 * Simple function to execute queries and return the result
 * Should be used to handle most errors*
 */
function quer($query){
    $que = conn()->query($query);
    return $que;
    
}
/**
 * Returns the value of a field for a specific user
 * 
 * @param string $email the email of a user
 * @param string $field the desired field to retrive (e.g "username", "password")
 * 
 * @return mix  returns the value directly, type depends on the field.
 */
function getUserField($email, $field){
    if(is_null($email) ) {return null;} //check if input couldn't be sanitized
    $user = getUser($email)->fetch_assoc();
    return $user[$field];
    
}
//prints the whole rows, one or more column, or returns a value, format:
// getUserData("name@email.com",["field1","field2"]);
/**
 * Returns an array of fields data, in the order you input.
 * 
 * @param string $email the email of a user
 * @param array $fields an array of fields name (e.g ["username", "pass"])
 * 
 * @return array  returns values in the order of input, (e.g array[0] = username, array[1] = password )
 * 
 */
function getUserData($email, $fields = null ){
    //first check for input validity
    if(!is_string($email) || !is_array($fields) ) {return null; }
        $result = [];
        $user = getUser($email);
        while($row = $user->fetch_assoc()) {
            foreach($fields as $field){
            $result[] = $row[$field];
        }
        return $result;
    }
}
/**
 * adds a single user to the database, it also checks for valid input
 * 
 * @param $username the username of the user
 * @param $email    the email of the user
 * @param $pass     the password of the user
 * 
 * @return string   it returns a string explaining the final state of execution (e.g "success", "username too short", "password empty", ...)
 */
function addUser($username, $email, $pass)
{
    if($username == ""){
        echo "username can't be empty";
    }
    else if($email == ""){
        echo "email can't be empty";
    }
    else if($pass == ""){
        echo "password can't be empty";
    }
    else if(strlen($username) < 6){
        echo "username too short";
    } 
    else if(strlen($pass) <= 4) {
        echo "password needs to be 5 or more in length";
    }
    else{ 
    $query = "INSERT INTO users (username, email, pass) VALUES ('$username', '$email', '$pass');";
    quer($query);
    echo "user added succesfully";
    }
}
function getUser($email){
    $query = "SELECT * FROM users WHERE email = '$email';";
    return quer($query);
}

//checks if the inputed email and password are correctly associated in the database.
function verifyUser($email, $pass){
    if( $pass === getUserField($email,'pass')){
        return TRUE;
    }else{
        return FALSE;
    }
}

/**
 * Returns the value of a field for a specific user
 * 
 * @param string $email the email of the admin
 * @param string $field the desired field to retrive (e.g "username", "password")
 * 
 * @return mix  returns the value directly, type depends on the field.
 */
function getAdminField($email, $field){
    if(is_null($email) ) {return null;} //check if input couldn't be sanitized
    $user = getUser($email)->fetch_assoc();
    return $user[$field];
    
}
//prints the whole rows, one or more column, or returns a value, format:
// getUserData("name@email.com",["field1","field2"]);
/**
 * Returns an array of fields data, in the order you input.
 * 
 * @param string $email the email of a user
 * @param array $fields an array of fields name (e.g ["username", "pass"])
 * 
 * @return array returns values in the order of input, (e.g array[0] = username, array[1] = password )
 * 
 */
function getAdminData($email, $fields = null ){
    //first check for input validity
    if(!is_string($email) || !is_array($fields) ) {return null; }
        $result = [];
        $user = getUser($email);
        while($row = $user->fetch_assoc()) {
            foreach($fields as $field){
            $result[] = $row[$field];
        }
        return $result;
    }
}

function addAdmin($firstname,$lastname,$occupation,$email,$pass){
    $query = "INSERT INTO admins (firstname, lastname, occupation, email, pass) VALUES ('$firstname', '$lastname', '$occupation','$email', '$pass');";
    quer($query);
    echo "user added succesfully";
}
function getAdmin($email){
    $query = "SELECT * FROM admins WHERE email = '$email';";
    return quer($query);
}
function getAllAdmin(){
    $query = "SELECT * FROM admins;";
    getAdminData(quer($query));
}
function verifyAdmin($email, $pass){
    if($pass === getAdminData($email,['pass'])){
        return TRUE;
    }
    else{
        return FALSE;
    }
}
/**
 * Prints entire database, to be used mostly for debugging
 */
function getAllUser()
{
    
  $query = "SELECT * FROM users";
   //this is what makes "getAllUser() work, since $email is not a string, but a query return value, now it 
        //prints everything in this specific format.
        $input = quer($query);
         // Output data for each row
            while($row = $input->fetch_assoc()) {
                echo "ID: " . $row["ID"] . " - Username: " . $row["username"] . " - Email: " . $row["email"] . " ||| Password: " . $row["pass"] . "<br>";
            }

}
/**
 * Retrieves input and sanitizes it before use, if input is invalid or not sanitizable, it returns null.
 * 
 * @param string $name  the input name for $_POST[$name] (i.g "username", "pass", "email")
 * @param string $type  defines which type of sanitizing is needed (i.g "email" = checkEmail( ) )
 * 
 * @return string null  
 */
function getInput($name, $type = "text"){
    if(!isset($_POST[$name])){return null;}
    $value = trim($_POST[$name]);

    switch($type){
        case "email":
            return checkEmail($value); //sanitizes input
        case "pass":
            return checkPassword($value);
        case "text":
            return $value !== "" ? $value : null;
        default:
            return null;
    }

}
/**
 * Removes all or a selection of Symbols from the input and initial and ending spaces.
 * 
 * @param string $input the inputed string you wish to remove symbols from
 * @param array $symbols    an array of singular symbols (or other) you wish to remove (i.g [".", ","])
 * 
 * if $symbols is left empty, than the standard is to remove ['"', "'", '\\', '/', '|', ';', '=', '<', '>', '(', ')']
 * example: input = " myEmai'l@ "return".com " 
 * returns: " myEmail return.com "
 */
function removeSymbols($input, $symbols = null){
    if(!is_string($input) || is_null($input) || empty($input) ) {return null;} //invalid input

    if(is_null($symbols)){
        $symbols = ['"', "'", '\\', '/', '|', ';', '=', '<', '>', '(', ')'];
    }

    foreach($symbols as $symbol){
        $input = str_replace($symbol,'', $input); //removes both the symbol and the space
    }
    $input = trim($input); //removes initial and ending spaces
    return $input;
}
/**
 * verifies if an email is correclty inputed, and sanitizes it removing unecessary symbols.
 * 
 * @param $string $email    the email to verify 
 */
function checkEmail($email){
    if(!is_string($email) || is_null($email)){return null;}
    else{
        return removeSymbols($email,["'",'"','%','\\', '/']);
    }
}
/**
 * Verifies if a password is correctly inputed, and sanitizes it
 * 
 * @param $string $pass the password to verifiy
 */
function checkPassword($pass){
    if(!is_string($pass) || is_null($pass)){return null;}
    else{
        return removeSymbols($pass,["'",'"']);
    }
}
?>
<!--DONE: verify[type] functions -->
<!--DONE: create input functions -->
<!--TODO: integrate new commands -->
<!--TODO: All Admin Commands -->
<!--TODO: Complete documentation -->
<!--TODO: Improve readibility and add comments -->