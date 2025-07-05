<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'volunteer') {
    header("Location: ../auth/login.php");
    exit();
}

include realpath(__DIR__ . '/../config/db.php');
$username = $_SESSION['username'];

// Fetch public users
$stmt = $conn->prepare("SELECT username, full_name, photo FROM public_users");
$stmt->execute();
$usersResult = $stmt->get_result();
$publicUsers = [];
while ($row = $usersResult->fetch_assoc()) {
    $publicUsers[] = $row;
}

// Get selected user to chat with
$chatUser = $_GET['user'] ?? '';
$chatMessages = [];
$chatUserDetails = null;
if ($chatUser) {
    $detailsStmt = $conn->prepare("SELECT full_name, photo FROM public_users WHERE username = ?");
    $detailsStmt->bind_param("s", $chatUser);
    $detailsStmt->execute();
    $chatUserDetails = $detailsStmt->get_result()->fetch_assoc();

    $msgStmt = $conn->prepare("SELECT * FROM messages WHERE 
        (sender_username = ? AND receiver_username = ?) OR
        (sender_username = ? AND receiver_username = ?) ORDER BY sent_at ASC");
    $msgStmt->bind_param("ssss", $username, $chatUser, $chatUser, $username);
    $msgStmt->execute();
    $res = $msgStmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $chatMessages[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Volunteer ↔ Public Chat | ReliefLink</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .chat-container {
      max-width: 960px;
      margin: 30px auto;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 16px rgba(0,0,0,0.1);
      background-color: #fff;
      display: flex;
      height: 550px;
    }
    .user-list {
      width: 30%;
      border-right: 1px solid #ddd;
      padding: 15px;
      overflow-y: auto;
    }
    .chat-area {
      width: 70%;
      padding: 15px;
      display: flex;
      flex-direction: column;
    }
    .chat-box {
      flex: 1;
      overflow-y: auto;
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 8px;
      padding: 15px;
      margin: 0 0 1rem 0;
      background-color: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(10px);
      display: flex;
      flex-direction: column;
    }
    .message {
      max-width: 70%;
      margin-bottom: 12px;
      padding: 10px 15px;
      border-radius: 15px;
      position: relative;
      word-wrap: break-word;
      background-color: #e2e3e5;
      color: #000;
      align-self: flex-start;
    }
    .message.you {
      background-color: #d1e7dd;
      align-self: flex-end;
    }
    .message.other {
      align-self: flex-start;
    }
    .message small {
      font-size: 0.7rem;
      color: #666;
      display: block;
      text-align: right;
      margin-top: 4px;
    }
    .input-group input {
      border-radius: 0;
    }
    .user-item {
      display: flex;
      align-items: center;
      padding: 10px;
      border-bottom: 1px solid #eee;
      text-decoration: none;
      color: #333;
    }
    .user-item:hover {
      background-color: #f1f1f1;
    }
    .user-item img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 10px;
    }
    .chat-header {
      display: flex;
      align-items: center;
      margin-bottom: 0.5rem;
    }
    .chat-header img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 10px;
    }
  </style>
</head>
<body>
<div class="container">
  <h4 class="text-center my-4">💬 Volunteer Chat with Public</h4>
  <div class="chat-container">
    <!-- Public User List -->
    <div class="user-list">
      <?php foreach ($publicUsers as $user): ?>
        <a class="user-item" href="?user=<?= urlencode($user['username']) ?>">
          <img src="public/uploads/<?= htmlspecialchars($user['photo']) ?>" alt="User">
          <?= htmlspecialchars($user['full_name']) ?>
        </a>
      <?php endforeach; ?>
    </div>

    <!-- Chat Area -->
    <div class="chat-area">
      <?php if ($chatUser && $chatUserDetails): ?>
        <div class="chat-header">
          <img src="public/uploads/<?= htmlspecialchars($chatUserDetails['photo']) ?>" alt="User">
          <strong><?= htmlspecialchars($chatUserDetails['full_name']) ?></strong>
        </div>

        <div class="chat-box" id="chat-box">
          <?php foreach ($chatMessages as $msg): ?>
            <div class="message <?= $msg['sender_username'] === $username ? 'you' : 'other' ?>">
              <?= htmlspecialchars($msg['message']) ?>
              <small><?= date("d M Y h:i A", strtotime($msg['sent_at'])) ?></small>
            </div>
          <?php endforeach; ?>
        </div>

        <form method="post" action="send_messages.php" id="sendForm">
          <input type="hidden" name="receiver" value="<?= htmlspecialchars($chatUser) ?>">
          <div class="input-group">
            <input type="text" name="message" class="form-control" placeholder="Type your message..." required>
            <button type="submit" class="btn btn-success">Send</button>
          </div>
        </form>
      <?php else: ?>
        <p class="text-muted">Select a public user to begin chatting.</p>
      <?php endif; ?>
    </div>
  </div>
  <div class="container">
  <div class="d-flex justify-content-between align-items-center mt-4 mb-2">
    <a href="volunteer_dashboard.php" class="btn btn-outline-primary">
      <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
  </div>
</div>
<script>
<?php if ($chatUser): ?>
function loadChat() {
 $.get('public/load_chat.php?user=<?= urlencode($chatUser) ?>', function(data) {
    $('#chat-box').html(data);
    $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
  });
}
loadChat();
setInterval(loadChat, 3000);

$('#sendForm').on('submit', function(e) {
  e.preventDefault();
  $.post('http://localhost/ReliefLink/user/send_messages.php', $(this).serialize(), function() {
    loadChat();
    $('#sendForm')[0].reset();
  });
});
<?php endif; ?>
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
