<?php
session_start();
$conn = new mysqli("localhost", "root", "", "relieflink");

$username = $_SESSION['public_username'] ?? '';
if (!$username) {
    header("Location: public_login.php");
    exit();
}

$need = $_POST['need'] ?? '';
$message = $_POST['message'] ?? '';

$stmt = $conn->prepare("INSERT INTO help_requests (name, email, phone, location, need) VALUES (?, ?, ?, ?, ?)");

// Fetch user info
$user = $conn->query("SELECT * FROM public_users WHERE username = '$username'")->fetch_assoc();
$name = $user['full_name'];
$email = "noreply@relieflink.org";
$phone = "0000000000";  // Update if needed
$location = "Unknown"; // Update if you store this

$stmt->bind_param("sssss", $name, $email, $phone, $location, $need);
$stmt->execute();

header("Location: public_dashboard.php?success=1");
exit();
