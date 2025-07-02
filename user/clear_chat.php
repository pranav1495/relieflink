<?php
session_start();
include __DIR__ . '/../config/db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin = $_POST['admin'];
    $volunteer = $_POST['volunteer'];

    $stmt = $conn->prepare("DELETE FROM messages WHERE 
        (sender_username = ? AND receiver_username = ?) OR 
        (sender_username = ? AND receiver_username = ?)");
    $stmt->bind_param("ssss", $admin, $volunteer, $volunteer, $admin);
    
    if ($stmt->execute()) {
        $_SESSION['chat_notice'] = "✅ Chat cleared successfully.";
    } else {
        $_SESSION['chat_notice'] = "❌ Failed to clear chat.";
    }
}

header("Location: chat_with_volunteer.php?username=" . urlencode($volunteer));
exit();
