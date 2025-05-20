<?php 
/**
 * the only purpose of this file is to correctly connect to the database, this code was tested on XAMPP
 */
$servername = "localhost";  //MySQL server 
$username = "ROOT";         //default username
$password = "changeme";     //default password
$dbname = "XAMPP-database";

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
