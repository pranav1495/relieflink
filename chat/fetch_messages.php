<?php
session_start();
include __DIR__ . '/../config/db.php';

$sender = $_SESSION['username'] ?? '';
$with = $_GET['with'] ?? '';

if ($sender && $with) {
  $stmt = $conn->prepare("
    SELECT * FROM messages 
    WHERE (sender_username = ? AND receiver_username = ?)
       OR (sender_username = ? AND receiver_username = ?)
    ORDER BY sent_at ASC
  ");
  $stmt->bind_param("ssss", $sender, $with, $with, $sender);
  $stmt->execute();
  $res = $stmt->get_result();

  while ($row = $res->fetch_assoc()) {
    $align = $row['sender_username'] === $sender ? 'text-end bg-primary text-white' : 'text-start bg-light';
    echo "<div class='p-2 mb-1 rounded $align'>{$row['message']}<br><small class='text-muted'>{$row['sent_at']}</small></div>";
  }
}
?>
