<?php


$con=new mysqli("localhost","root","");

$host = "localhost";
$username = "root";
$password = "";
$database = "aspire";

// Create connection
//$con = new mysqli($host, $username, $password, $database);

// Check connection
if ($con->connect_error) {
    echo "Connection failed: " . $con->connect_error;
    die("Sorry, database connection failed");
} else {
    echo "Database connected";
}
?>
