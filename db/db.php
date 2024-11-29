
<?php
// Database credentials
$host = "localhost";
$dbname = "paw";
$username = "root"; // Replace with your database username 
$password = "sami12345"; // Replace with your database password

    $conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) 
    die("Connection failed: " . $conn->connect_error);

?>