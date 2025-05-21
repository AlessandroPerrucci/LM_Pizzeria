<?php 
/**
 * the only purpose of this file is to correctly connect to the database, this code was tested on XAMPP
 */
$servername = "localhost";  //MySQL server 
$username = "lmpizzeria";         //default username
$password = " ";     //default password
$dbname = "my_lmpizzeria";

//Start Connection
$connection = new mysqli($servername,$username,$password,$dbname);
//Check connection
if ($connection->connect_error){
    die("Connection failed: ".$connection->connect_error);
}

function Conn(){
    global $connection;
    return $connection;
}
?>
