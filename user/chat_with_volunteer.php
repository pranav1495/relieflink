<?php
session_start();
include __DIR__ . '/../config/db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['username'])) {
    echo "❌ Volunteer username not specified.";
    exit();
}

$volunteer = $_GET['username'];
$admin = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Chat with Volunteer - <?= htmlspecialchars($volunteer) ?></title>
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    .chat-box {
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 1rem;
      height: 320px;
      overflow-y: auto;
      background-color: #f8f9fa;
      margin-bottom: 1rem;
    }

    .chat-msg {
      margin-bottom: 10px;
    }

    .chat-msg strong {
      color: #198754;
    }

    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
    }

    .clear-form {
      display: inline;
    }
  </style>
</head>
<body class="p-4">
  <div class="container">
    <div class="top-bar">
      <h4>💬 Chat with Volunteer: <strong><?= htmlspecialchars($volunteer) ?></strong></h4>
      <form action="clear_chat.php" method="POST" onsubmit="return confirm('Are you sure you want to clear the chat with <?= $volunteer ?>?');" class="clear-form">
        <input type="hidden" name="admin" value="<?= $admin ?>">
        <input type="hidden" name="volunteer" value="<?= $volunteer ?>">
        <button type="submit" class="btn btn-danger btn-sm">🗑️ Clear Chat</button>
      </form>
    </div>

    <!-- Chat Box -->
    <div class="chat-box" id="chat-box">
      <?php
        $stmt = $conn->prepare("SELECT * FROM messages WHERE (sender_username=? AND receiver_username=?) OR (sender_username=? AND receiver_username=?) ORDER BY sent_at ASC");
        $stmt->bind_param("ssss", $admin, $volunteer, $volunteer, $admin);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
          echo "<p class='text-muted'>No messages yet.</p>";
        }

        while ($msg = $result->fetch_assoc()) {
          echo "<div class='chat-msg'><strong>" . htmlspecialchars($msg['sender_username']) . ":</strong> " . htmlspecialchars($msg['message']) . "</div>";
        }
      ?>
    </div>

    <!-- Send Message Form -->
    <form method="post" action="send_messages.php" id="sendForm">
      <input type="hidden" name="receiver" value="<?= htmlspecialchars($volunteer) ?>">
      <div class="input-group">
        <input type="text" name="message" class="form-control" placeholder="Type your message..." required>
        <button type="submit" class="btn btn-success">Send</button>
      </div>
    </form>

    <div class="mt-3">
      <a href="http://localhost/ReliefLink/user/admin/volunteers.php" class="btn btn-secondary">← Back</a>
    </div>
  </div>

  <!-- AJAX Script -->
  <script>
    $('#sendForm').on('submit', function(e) {
      e.preventDefault();

      const form = $(this);
      const message = form.find('input[name="message"]').val();
      const receiver = form.find('input[name="receiver"]').val();

      $.post(form.attr('action'), form.serialize(), function() {
        const msgHtml = `
          <div class="chat-msg"><strong>admin:</strong> ${$('<div>').text(message).html()}</div>
        `;
        $('#chat-box').append(msgHtml);
        $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
        form[0].reset();
      }).fail(function() {
        alert("❌ Failed to send message.");
      });
    });
  </script>
</body>
</html>
