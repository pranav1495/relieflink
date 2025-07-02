<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'volunteer') {
    header("Location: ../auth/login.php");
    exit();
}

$username = $_SESSION['username'];
include __DIR__ . '/../config/db.php';

// Fetch user details
$userQuery = $conn->prepare("SELECT full_name, photo FROM users WHERE username = ?");
$userQuery->bind_param("s", $username);
$userQuery->execute();
$userResult = $userQuery->get_result();
$userData = $userResult->fetch_assoc();
$photo = $userData['photo'] ?? 'default.jpg';
$full_name = $userData['full_name'] ?? $username;

// Fetch victim help requests (latest 5)
$requestQuery = $conn->query("SELECT * FROM help_requests ORDER BY id DESC LIMIT 5");

// Fetch public messages
$publicMessages = $conn->query("SELECT * FROM messages WHERE receiver_username = 'all' ORDER BY sent_at ASC");

// Fetch private messages with admin
$messages = [];
$stmt = $conn->prepare("SELECT * FROM messages WHERE (sender_username = ? AND receiver_username = 'admin') OR (sender_username = 'admin' AND receiver_username = ?) ORDER BY sent_at ASC");
$stmt->bind_param("ss", $username, $username);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Volunteer Dashboard | Relief-Link</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #e0f7fa, #e8f5e9);
      min-height: 100vh;
    }

    .topbar {
      background: rgba(25, 135, 84, 0.9);
      color: white;
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      position: relative;
    }

    .avatar {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #fff;
    }

    .dropdown-menu {
      max-height: 300px;
      overflow-y: auto;
    }

    .glass-card {
      backdrop-filter: blur(10px);
      background-color: rgba(255,255,255,0.85);
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      padding: 1.5rem;
      margin-bottom: 2rem;
    }

    .chat-box {
      height: 300px;
      overflow-y: auto;
      background-color: #f9f9f9;
      border-radius: 10px;
      border: 1px solid #ccc;
      padding: 1rem;
    }

    .message-bubble {
      margin-bottom: 12px;
      padding: 10px 15px 20px 15px;
      color: #f9f9f9;
      border-radius: 15px;
      background-color: rgb(44, 44, 44);
      max-width: 70%;
      position: relative;
      word-wrap: break-word;
      font-size: 0.95rem;
    }

    .message-bubble.you {
      background-color: rgb(0, 116, 31);
      margin-left: auto;
    }

    .message-bubble small {
      position: absolute;
      right: 10px;
      bottom: 5px;
      font-size: 0.7rem;
      color: rgba(255, 255, 255, 0.65);
    }

    .chat-link {
      position: absolute;
      left: 10px;
      bottom: 5px;
      font-size: 0.7rem;
      color: #ccc;
      text-decoration: underline;
    }

    .logout-btn {
      background-color: white;
      color: #000;
      border: 1px solid #ccc;
      transition: all 0.3s ease;
    }

    .logout-btn:hover {
      background-color: #dc3545;
      color: white;
      border-color: #dc3545;
    }

    .dropdown-toggle::after {
      display: none;
    }

    @media (max-width: 768px) {
      .topbar {
        flex-direction: column;
        gap: 10px;
        text-align: center;
      }
    }
  </style>
</head>
<body>

<div class="topbar">
  <div class="d-flex align-items-center gap-3">
    <img src="../assets/images/<?= htmlspecialchars($photo) ?>" alt="Profile" class="avatar">
    <h5 class="mb-0">Welcome, <?= htmlspecialchars($full_name) ?></h5>
  </div>

  <!-- Mail and Logout buttons -->
  <div class="d-flex align-items-center gap-3">
    <a href="http://localhost/ReliefLink/user/mail.php" class="btn btn-light">
      <i class="fa fa-envelope"></i>
    </a>
    <a href="../logout.php" class="btn logout-btn btn-sm">Logout</a>
  </div>
</div>

<div class="dashboard-container container mt-4">
  <!-- Global & Local Disaster Updates -->
  <div class="row">
    <div class="col-md-6">
      <div class="glass-card">
        <h5 class="text-primary">🌍 Global Disaster Updates</h5>
        <ul>
          <li><strong>Japan:</strong> Typhoon alert in Tokyo</li>
          <li><strong>Greece:</strong> Wildfires near Athens</li>
        </ul>
      </div>
    </div>
    <div class="col-md-6">
      <div class="glass-card">
        <h5 class="text-info">🏙️ City Alerts</h5>
        <ul>
          <li><strong>Trivandrum:</strong> Heavy rain warning</li>
          <li><strong>Kollam:</strong> Coastal flood risk</li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Public Chat -->
  <div class="glass-card">
    <h5 class="text-danger">🌐 Public Chat</h5>
    <div class="chat-box" id="public-chat-box">
      <?php if ($publicMessages->num_rows === 0): ?>
        <p>No messages yet.</p>
      <?php else: ?>
        <?php while ($msg = $publicMessages->fetch_assoc()):
          $isAdmin = $msg['sender_username'] === 'admin';
        ?>
          <div class="message-bubble <?= $msg['sender_username'] === $username ? 'you' : '' ?>">
            <strong class="<?= $isAdmin ? 'text-warning' : '' ?>">
              <?= $isAdmin ? '👑 Admin' : htmlspecialchars($msg['sender_username']) ?>:
            </strong> <?= htmlspecialchars($msg['message']) ?>
            <small><?= date("d M Y h:i A", strtotime($msg['sent_at'])) ?></small>
            <?php if ($isAdmin): ?>
              <a href="chat_with_user.php?user=admin" class="chat-link">Chat privately</a>
            <?php endif; ?>
          </div>
        <?php endwhile; ?>
      <?php endif; ?>
    </div>
    <form class="chat-input mt-3" method="post" action="send_messages.php">
      <input type="hidden" name="receiver" value="all">
      <div class="input-group">
        <input type="text" name="message" class="form-control" placeholder="Message the community..." required>
        <button type="submit" class="btn btn-danger">Send</button>
      </div>
    </form>
  </div>

  <!-- Private Chat -->
  <div class="glass-card">
    <h5 class="text-success">💬 Chat with Admin</h5>
    <div class="chat-box" id="chat-box">
      <?php if (empty($messages)): ?>
        <p>No private messages.</p>
      <?php else: ?>
        <?php foreach ($messages as $msg): ?>
          <div class="message-bubble <?= $msg['sender_username'] === $username ? 'you' : '' ?>">
            <strong><?= htmlspecialchars($msg['sender_username']) ?>:</strong> <?= htmlspecialchars($msg['message']) ?>
            <small><?= date("d M Y h:i A", strtotime($msg['sent_at'])) ?></small>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <form class="chat-input mt-3" method="post" action="send_messages.php">
      <input type="hidden" name="receiver" value="admin">
      <div class="input-group">
        <input type="text" name="message" class="form-control" placeholder="Type your message..." required>
        <button type="submit" class="btn btn-success">Send</button>
      </div>
    </form>
  </div>
</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script>
  const publicBox = document.getElementById("public-chat-box");
  if (publicBox) publicBox.scrollTop = publicBox.scrollHeight;

  const chatBox = document.getElementById("chat-box");
  if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;
</script>
</body>
</html>
