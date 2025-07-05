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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }

    .chat-container {
      display: flex;
      flex-direction: row;
      height: 550px;
      width: 100%;
      max-width: 960px;
      margin: 10px auto;
      border-radius: 12px;
      background-color: #fff;
      overflow: hidden;
      box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    }

    .user-list {
      width: 30%;
      border-right: 1px solid #ddd;
      padding: 15px;
      overflow-y: auto;
    }

    .user-item {
      display: flex;
      align-items: center;
      padding: 8px;
      border-bottom: 1px solid #eee;
      text-decoration: none;
      color: #333;
    }

    .user-item:hover {
      background-color: #f1f1f1;
    }

    .user-item img {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 10px;
    }

    .chat-area {
      width: 70%;
      padding: 15px;
      display: flex;
      flex-direction: column;
    }

    .chat-header {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
    }

    .chat-header img {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      margin-right: 10px;
    }

    .chat-box {
      flex: 1;
      overflow-y: auto;
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 12px;
      background-color: #fff;
      margin-bottom: 10px;
    }

    .message {
      max-width: 75%;
      margin-bottom: 10px;
      padding: 10px 15px;
      border-radius: 15px;
      word-wrap: break-word;
      background-color: #e2e3e5;
      align-self: flex-start;
    }

    .message.you {
      background-color: #d1e7dd;
      align-self: flex-end;
    }

    .message small {
      display: block;
      font-size: 0.7rem;
      color: #666;
      margin-top: 5px;
      text-align: right;
    }

    .input-group {
      display: flex;
      flex-direction: row;
      gap: 0.5rem;
      width: 100%;
    }

    .input-group input[type="text"] {
  flex-grow: 1;
  height: 42px;
  border-radius: 2px;
  width: 100%;
  padding: 0.5rem 0.75rem;
  border: 1px solid #ccc;
  font-size: 1rem;
}


    .input-group button {
      height: 42px;
      padding: 0 20px;
      border-radius: 6px;
      font-size: 1rem;
    }

    @media (max-width: 768px) {
      .chat-container {
        flex-direction: column;
        height: auto;
        max-width: 100%;
        margin: 0;
        border-radius: 0;
        box-shadow: none;
      }

      .user-list {
        width: 100%;
        height: auto;
        display: flex;
        overflow-x: auto;
        gap: 10px;
        border-bottom: 1px solid #ccc;
        padding: 10px;
      }

      .user-item {
        flex: 0 0 auto;
        border: 1px solid #ddd;
        border-radius: 10px;
        white-space: nowrap;
        padding: 6px 10px;
        background-color: #f8f9fa;
      }

      .chat-area {
        width: 100%;
        padding: 10px;
      }

      .chat-box {
        max-height: 300px;
        min-height: 200px;
      }

      .input-group {
        flex-direction: column;
        align-items: stretch;
        gap: 0.5rem;
      }

      .input-group input,
      .input-group button {
        width: 100%;
      }
    }
  </style>
</head>
<body>

<h5 class="text-center my-3"><i class="fas fa-comments"></i> Volunteer Chat with Public</h5>

<div class="chat-container">
  <!-- Public Users -->
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
        <?php if (empty($chatMessages)): ?>
          <p class="text-muted">No messages yet. Say hi 👋</p>
        <?php endif; ?>
        <?php foreach ($chatMessages as $msg): ?>
          <div class="message <?= $msg['sender_username'] === $username ? 'you' : '' ?>">
            <?= htmlspecialchars($msg['message']) ?>
            <small><?= date("d M Y h:i A", strtotime($msg['sent_at'])) ?></small>
          </div>
        <?php endforeach; ?>
      </div>

      <form id="sendForm" method="post" action="send_messages.php">
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

<div class="text-center mt-3">
  <a href="volunteer_dashboard.php" class="btn btn-outline-primary">
    <i class="fas fa-arrow-left"></i> Back to Dashboard
  </a>
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
    $.post('send_messages.php', $(this).serialize(), function() {
      loadChat();
      $('#sendForm')[0].reset();
    });
  });
<?php endif; ?>
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
