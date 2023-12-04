<?php
$port = 3306;         
$username = "user";
$password = "password";
$database = "database";
$host = "db"; 
$conn = new mysqli($host, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
