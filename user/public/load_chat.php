<?php
session_start();
include __DIR__ . '/../../config/db.php';

header("Content-Type: text/html; charset=UTF-8");

$isPublic = isset($_SESSION['public_username']);
$isVolunteer = isset($_SESSION['username']) && $_SESSION['role'] === 'volunteer';

if (!$isPublic && !$isVolunteer) {
    http_response_code(401);
    exit("❌ Unauthorized access.");
}

$currentUser = $isPublic ? $_SESSION['public_username'] : $_SESSION['username'];
$chatPartner = $_GET['volunteer'] ?? $_GET['user'] ?? '';

if (empty($chatPartner)) {
    http_response_code(400);
    exit("<p class='text-danger'>⚠️ No chat user specified.</p>");
}

$stmt = $conn->prepare("SELECT * FROM messages 
    WHERE (sender_username = ? AND receiver_username = ?) 
       OR (sender_username = ? AND receiver_username = ?)
    ORDER BY sent_at ASC");
$stmt->bind_param("ssss", $currentUser, $chatPartner, $chatPartner, $currentUser);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p class='text-muted text-center'>No messages yet. Say hi 👋</p>";
    exit();
}

while ($msg = $result->fetch_assoc()) {
    $isMe = $msg['sender_username'] === $currentUser;
    $senderName = $isMe ? "You" : htmlspecialchars($msg['sender_username']);
    $message = nl2br(htmlspecialchars($msg['message']));
    $datetime = date("d M Y, h:i A", strtotime($msg['sent_at']));

    echo "<div class='chat-msg' style='text-align: " . ($isMe ? "right" : "left") . ";'>";
    echo "<div style='display: inline-block; max-width: 75%; padding: 10px; border-radius: 10px; margin-bottom: 6px; background-color: " . ($isMe ? "#d1e7ff" : "#f8d7da") . ";'>";
    echo "<div><strong style='color:" . ($isMe ? "#0d6efd" : "#dc3545") . ";'>$senderName</strong></div>";
    echo "<div>$message</div>";
    echo "<div class='timestamp' style='font-size: 0.75rem; color: #555;'>$datetime</div>";
    echo "</div></div>";
}
?>
