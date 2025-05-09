<?php 
$servername = "localhost"; //MySQL server 
$username = "ROOT"; //default XAMPP username
$password = "changeme";     //Default XAMPP password
$dbname = "XAMPP-database";

//Start Connection
$connection = new mysqli($servername,$username,$password,$dbname);
//Check connection
if ($connection->connect_error){
    die("Connection failed: ". mysql_error($connection) .$connection->connect_error);
}

function Conn(){
    global $connection;
    return $connection;
}
?>
