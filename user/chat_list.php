<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'volunteer') {
    header("Location: ../auth/login.php");
    exit();
}

$username = $_SESSION['username'];
include '../config/db.php';

// Fetch all conversations where the current user is sender or receiver
$convoStmt = $conn->prepare("
  SELECT 
    u.username, u.full_name, u.photo,
    m.message, m.sent_at, m.sender_username
  FROM users u
  JOIN (
    SELECT 
      CASE 
        WHEN sender_username = ? THEN receiver_username 
        ELSE sender_username 
      END AS contact_user,
      MAX(sent_at) as latest_time
    FROM messages 
    WHERE sender_username = ? OR receiver_username = ?
    GROUP BY contact_user
  ) last_msg ON last_msg.contact_user = u.username
  JOIN messages m ON 
    ((m.sender_username = ? AND m.receiver_username = u.username) OR
     (m.sender_username = u.username AND m.receiver_username = ?))
    AND m.sent_at = last_msg.latest_time
  ORDER BY last_msg.latest_time DESC
");
$convoStmt->bind_param("sssss", $username, $username, $username, $username, $username);
$convoStmt->execute();
$chats = $convoStmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Chats | ReliefLink</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <style>
    body {
      background-color: #000;
      color: white;
      font-family: 'Segoe UI', sans-serif;
    }

    .chat-list-container {
      width: 100%;
      max-width: 500px;
      margin: auto;
      padding-top: 20px;
    }

    .chat-item {
      display: flex;
      align-items: center;
      padding: 10px;
      border-bottom: 1px solid #222;
      cursor: pointer;
      transition: background 0.2s ease-in-out;
      position: relative;
    }

    .chat-item:hover {
      background-color: #121212;
    }

    .chat-avatar {
      width: 55px;
      height: 55px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 15px;
      border: 2px solid #444;
    }

    .chat-info {
      flex: 1;
    }

    .chat-name {
      font-weight: bold;
      font-size: 1rem;
      margin-bottom: 3px;
      color: #f1f1f1;
    }

    .chat-preview {
      font-size: 0.9rem;
      color: #aaa;
    }

    .chat-time {
      font-size: 0.75rem;
      color: #888;
      position: absolute;
      right: 15px;
      top: 12px;
    }

    .unread-dot {
      width: 10px;
      height: 10px;
      background-color: #0af;
      border-radius: 50%;
      position: absolute;
      right: 15px;
      bottom: 12px;
    }

    .search-bar {
      width: 100%;
      margin-bottom: 10px;
    }

    .search-bar input {
      width: 100%;
      background-color: #111;
      color: white;
      border: 1px solid #333;
      padding: 8px 12px;
      border-radius: 8px;
    }

    .scrollable {
      max-height: 80vh;
      overflow-y: auto;
    }
  </style>
</head>
<body>
<div class="chat-list-container">
  <h4 class="text-center mb-4">💬 Chat Messages</h4>
  
  <div class="search-bar">
    <input type="text" placeholder="Search..." onkeyup="filterChats(this.value)">
  </div>

  <div class="scrollable" id="chat-list">
    <?php while ($chat = $chats->fetch_assoc()): ?>
      <a href="chat_with_user.php?user=<?= urlencode($chat['username']) ?>" class="text-decoration-none">
        <div class="chat-item">
          <img src="../assets/images/<?= htmlspecialchars($chat['photo']) ?>" class="chat-avatar" alt="">
          <div class="chat-info">
            <div class="chat-name"><?= htmlspecialchars($chat['full_name']) ?></div>
            <div class="chat-preview">
              <?= htmlspecialchars($chat['sender_username'] === $username ? "You: " : "") ?>
              <?= htmlspecialchars($chat['message']) ?>
            </div>
          </div>
          <div class="chat-time"><?= date("h:i A", strtotime($chat['sent_at'])) ?></div>
          <div class="unread-dot"></div>
        </div>
      </a>
    <?php endwhile; ?>
  </div>
</div>

<script>
  function filterChats(value) {
    const items = document.querySelectorAll(".chat-item");
    value = value.toLowerCase();
    items.forEach(item => {
      const name = item.querySelector(".chat-name").textContent.toLowerCase();
      item.style.display = name.includes(value) ? "flex" : "none";
    });
  }
</script>
</body>
</html>
