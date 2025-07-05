<?php
session_start();
include __DIR__ . '/../config/db.php';

// Detect user role and username
$sender = null;

if (isset($_SESSION['public_username'])) {
    $sender = $_SESSION['public_username'];
} elseif (isset($_SESSION['username']) && isset($_SESSION['role'])) {
    $sender = $_SESSION['username']; // works for volunteer or admin
} else {
    http_response_code(401);
    exit(); // Unauthorized
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receiver = trim($_POST['receiver'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($receiver === '' || $message === '') {
        http_response_code(400);
        exit(); // Bad Request
    }

    // Insert message into DB
    $stmt = $conn->prepare("INSERT INTO messages (sender_username, receiver_username, message, sent_at) VALUES (?, ?, ?, NOW())");

    if (!$stmt) {
        http_response_code(500);
        exit(); // Internal Server Error
    }

    $stmt->bind_param("sss", $sender, $receiver, $message);
    $stmt->execute();
    $stmt->close();
    exit(); // Silent success
} else {
    http_response_code(405); // Method Not Allowed
    exit();
}
?>
