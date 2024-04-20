<?php

// Database connection parameters
$sname = "localhost"; // Server name 
$uname = "root";            // Username
$password = "";             // Password

$db_name = "ipt101";        // Database name

// Establishing a connection to the database using mysqli_connect()
$conn = mysqli_connect($sname, $uname, $password, $db_name);

// Check if the connection is successful
if(!$conn) {
    echo "connection failed!"; // Display an error message if the connection fails
}