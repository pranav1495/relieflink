<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'volunteer') {
    header("Location: ../auth/login.php");
    exit();
}

include '../config/db.php';

$currentUser = $_SESSION['username'];
$chatUser = $_GET['user'] ?? null;

if (!$chatUser || $chatUser === $currentUser) {
    die("Invalid chat user.");
}

// Get chat user details
$userStmt = $conn->prepare("SELECT full_name, photo FROM users WHERE username = ?");
$userStmt->bind_param("s", $chatUser);
$userStmt->execute();
$userRes = $userStmt->get_result();
$chatUserData = $userRes->fetch_assoc();
$chatPhoto = $chatUserData['photo'] ?? 'default.jpg';
$chatName = $chatUserData['full_name'] ?? $chatUser;

// Fetch messages between users
$msgStmt = $conn->prepare("SELECT * FROM messages 
    WHERE (sender_username = ? AND receiver_username = ?) 
       OR (sender_username = ? AND receiver_username = ?) 
    ORDER BY sent_at ASC");
$msgStmt->bind_param("ssss", $currentUser, $chatUser, $chatUser, $currentUser);
$msgStmt->execute();
$msgResult = $msgStmt->get_result();
$messages = [];
while ($row = $msgResult->fetch_assoc()) {
    $messages[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Chat with <?= htmlspecialchars($chatName) ?></title>
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <style>
    body {
      margin: 0;
      background-color: #000;
      color: white;
      font-family: 'Segoe UI', sans-serif;
    }

    .topbar {
      background-color: #198754;
      padding: 1rem;
      display: flex;
      align-items: center;
      gap: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }

    .chat-avatar {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #fff;
    }

    .chat-area {
      padding: 20px;
      height: 75vh;
      overflow-y: auto;
      background-color: #121212;
      border-top: 1px solid #333;
      border-bottom: 1px solid #333;
    }

    .msg-bubble {
      max-width: 60%;
      padding: 12px;
      border-radius: 15px;
      margin: 10px 0;
      position: relative;
      font-size: 0.95rem;
      word-wrap: break-word;
      background-color: #2e2e2e;
      color: white;
    }

    .msg-bubble.you {
      background-color: #008000;
      margin-left: auto;
      text-align: left;
    }

    .msg-bubble small {
      position: absolute;
      bottom: -16px;
      right: 8px;
      font-size: 0.7rem;
      color: #ccc;
    }

    .chat-input {
      padding: 15px;
      background-color: #0f0f0f;
    }

    .chat-input input {
      border-radius: 10px 0 0 10px;
    }

    .chat-input button {
      border-radius: 0 10px 10px 0;
    }

    a.back-btn {
      color: white;
      text-decoration: none;
      font-size: 1.2rem;
      margin-left: auto;
    }

    @media (max-width: 768px) {
      .msg-bubble {
        max-width: 85%;
      }
    }
  </style>
</head>
<body>

<div class="topbar">
  <img src="../assets/images/<?= htmlspecialchars($chatPhoto) ?>" class="chat-avatar">
  <h5 class="mb-0"><?= htmlspecialchars($chatName) ?></h5>
  <a href="chat_list.php" class="back-btn ms-auto">&larr; Back</a>
</div>

<div class="chat-area" id="chatArea">
  <?php if (empty($messages)): ?>
    <p class="text-center text-muted">No messages yet.</p>
  <?php else: ?>
    <?php foreach ($messages as $msg): ?>
      <div class="msg-bubble <?= $msg['sender_username'] === $currentUser ? 'you' : '' ?>">
        <?= htmlspecialchars($msg['message']) ?>
        <small><?= date("h:i A", strtotime($msg['sent_at'])) ?></small>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<form class="chat-input d-flex" action="send_messages.php" method="POST">
  <input type="hidden" name="receiver" value="<?= htmlspecialchars($chatUser) ?>">
  <input type="text" name="message" class="form-control" placeholder="Type a message..." required>
  <button type="submit" class="btn btn-success">Send</button>
</form>

<script>
  const chatArea = document.getElementById('chatArea');
  chatArea.scrollTop = chatArea.scrollHeight;
</script>
</body>
</html>
