<?php
session_start();
include __DIR__ . '/../../config/db.php'; // Adjusted for correct config path

if (!isset($_SESSION['public_username'])) {
    echo "<p class='text-danger text-center mt-4'>You must be logged in to view this page.</p>";
    exit();
}

$public_user = $_SESSION['public_username'];
$volunteer = $_GET['volunteer'] ?? '';
$volunteerDetails = null;

if ($volunteer) {
    $stmt = $conn->prepare("SELECT full_name, photo, phone FROM users WHERE username = ? AND role = 'volunteer'");
    $stmt->bind_param("s", $volunteer);
    $stmt->execute();
    $result = $stmt->get_result();
    $volunteerDetails = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Chat with Volunteers | ReliefLink</title>
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
      padding: 10px;
      margin-bottom: 1rem;
      background-color: rgba(255, 255, 255, 0.5);
      backdrop-filter: blur(10px);
    }
    .chat-msg {
      margin-bottom: 12px;
      line-height: 1.4;
    }
    .timestamp {
      font-size: 0.75rem;
      color: #888;
    }
    .chat-msg .sender {
      font-weight: bold;
      color: #0d6efd;
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
    .chat-header strong {
      font-size: 1.1rem;
    }
  </style>
</head>
<body>
<div class="container">
  <h4 class="text-center my-4">💬 Public Chat with Volunteer</h4>
  <div class="chat-container">
    <!-- Sidebar: Volunteers List -->
    <div class="user-list">
      <?php
      $stmt = $conn->prepare("
        SELECT u.username, u.full_name, u.photo,
               MAX(m.sent_at) AS last_msg_time
        FROM users u
        LEFT JOIN messages m ON (m.sender_username = ? AND m.receiver_username = u.username)
                             OR (m.sender_username = u.username AND m.receiver_username = ?)
        WHERE u.role = 'volunteer'
        GROUP BY u.username, u.full_name, u.photo
        ORDER BY last_msg_time DESC
      ");
      $stmt->bind_param("ss", $public_user, $public_user);
      $stmt->execute();
      $users = $stmt->get_result();

      while ($user = $users->fetch_assoc()) {
          $isSelected = ($volunteer === $user['username']) ? "background-color: #e9f5ff;" : "";
          echo "<a href='chat_with_volunteers.php?volunteer=" . htmlspecialchars($user['username']) . "' class='user-item' style='$isSelected'>";
          echo "<img src='../../assets/images/" . htmlspecialchars($user['photo']) . "' alt='User'> ";
          echo "<span>" . htmlspecialchars($user['full_name']) . "</span></a>";
      }
      ?>
    </div>

    <!-- Chat Area -->
    <div class="chat-area">
      <?php if ($volunteer && $volunteerDetails): ?>
        <div class="chat-header">
          <img src="../../assets/images/<?= htmlspecialchars($volunteerDetails['photo']) ?>" alt="Profile">
          <strong><?= htmlspecialchars($volunteerDetails['full_name']) ?></strong>
        </div>
        <div class="chat-box" id="chat-box"></div>

       <form method="post" action="../send_messages.php" id="sendForm">
          <input type="hidden" name="receiver" value="<?= htmlspecialchars($volunteer) ?>">
          <div class="input-group">
            <input type="text" name="message" class="form-control" placeholder="Type your message..." required>
            <button type="submit" class="btn btn-success">Send</button>
          </div>
        </form>
      <?php else: ?>
        <p class="text-muted">Select a volunteer to start chatting.</p>
      <?php endif; ?>
    </div>
  </div>
  <div class="text-start mt-3 mb-3">
  <a href="public_dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
</div>
</div>

<!-- JavaScript -->
<script>
<?php if ($volunteer): ?>
function loadChat() {
  $.get('load_chat.php?volunteer=<?= urlencode($volunteer) ?>', function(data) {
    $('#chat-box').html(data);
    $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
  }).fail(function(xhr) {
    console.error("❌ Chat load failed:", xhr.status, xhr.responseText);
  });
}
loadChat();
setInterval(loadChat, 3000);

$('#sendForm').on('submit', function(e) {
  e.preventDefault();  // ✅ Prevent page reload
  $.post($(this).attr('action'), $(this).serialize(), function(response) {
    console.log("✅ Message sent:", response);
    loadChat(); // Reload chat messages
    $('#sendForm')[0].reset(); // Clear input
  }).fail(function(xhr) {
    console.error("❌ Error:", xhr.status, xhr.responseText);
    alert("Error: " + xhr.responseText);
  });
});

<?php endif; ?>
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
