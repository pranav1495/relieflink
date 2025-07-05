<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // or your DB password
$dbname = 'relieflink';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
