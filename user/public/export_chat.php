<?php
session_start();
include __DIR__ . '/../../config/db.php';

if (!isset($_SESSION['public_username']) || !isset($_GET['volunteer'])) {
    die("Unauthorized access.");
}

$public = $_SESSION['public_username'];
$volunteer = $_GET['volunteer'];

$stmt = $conn->prepare("SELECT sender_username, receiver_username, message, sent_at FROM messages 
  WHERE (sender_username = ? AND receiver_username = ?) OR (sender_username = ? AND receiver_username = ?)
  ORDER BY sent_at ASC");
$stmt->bind_param("ssss", $public, $volunteer, $volunteer, $public);
$stmt->execute();
$messages = $stmt->get_result();

header("Content-Type: text/plain");
header("Content-Disposition: attachment; filename=chat_export.txt");

while ($row = $messages->fetch_assoc()) {
    echo "[" . $row['sent_at'] . "] " . $row['sender_username'] . ": " . $row['message'] . "\n";
}
exit;
?>
