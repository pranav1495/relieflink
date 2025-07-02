<?php
session_start();
include __DIR__ . '/../config/db.php';

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender = $_SESSION['username'];
    $receiver = isset($_POST['receiver']) ? trim($_POST['receiver']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    if (!empty($receiver) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO messages (sender_username, receiver_username, message, sent_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $sender, $receiver, $message);

        if ($stmt->execute()) {
            $_SESSION['message_success'] = "✅ Message sent successfully.";
        } else {
            $_SESSION['message_error'] = "❌ Failed to send message. Please try again.";
        }
    } else {
        $_SESSION['message_error'] = "❌ All fields are required.";
    }

    // Redirect back to the page that submitted the form
    $redirectBack = $_SERVER['HTTP_REFERER'] ?? 'volunteer_dashboard.php';
    header("Location: $redirectBack");
    exit();
}
?>
