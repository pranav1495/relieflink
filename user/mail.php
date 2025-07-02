<?php
$conn = new mysqli("localhost", "root", "", "relieflink");

// Handle clear inbox action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear'])) {
    $conn->query("DELETE FROM victims");
    header("Location: mail.php"); // reload the inbox after clearing
    exit;
}

$result = $conn->query("SELECT id, name, need, location, status, created_at FROM victims ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Inbox | ReliefLink</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f0f2f5;
    }
    .inbox-container {
      max-width: 800px;
      margin: 30px auto;
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    .message-preview {
      padding: 15px;
      border-bottom: 1px solid #ddd;
      cursor: pointer;
    }
    .message-preview:hover {
      background: #f9f9f9;
    }
    .status {
      font-weight: bold;
      float: right;
    }
    .status.pending { color: orange; }
    .status.approved { color: green; }
    .status.declined { color: red; }

    .btn {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 18px;
      background-color: #dc3545;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
    }

    .btn:hover {
      background-color: #c82333;
    }

    .back-btn {
      display: inline-block;
      margin-top: 20px;
      text-decoration: none;
      color: #007bff;
      font-size: 14px;
    }
  </style>
</head>
<body>

<div class="inbox-container">
  <h2>📬 Victim Requests Inbox</h2>

  <?php if ($result->num_rows === 0): ?>
    <p>No help requests found.</p>
  <?php else: ?>
    <?php while($row = $result->fetch_assoc()): ?>
      <div class="message-preview" onclick="location.href='view_request.php?id=<?= $row['id'] ?>'">
        <strong><?= htmlspecialchars($row['name']) ?></strong> - <?= htmlspecialchars($row['need']) ?>
        <div class="status <?= $row['status'] == 0 ? 'pending' : ($row['status'] == 1 ? 'approved' : 'declined') ?>">
          <?= $row['status'] == 0 ? 'Pending' : ($row['status'] == 1 ? 'Approved' : 'Declined') ?>
        </div>
        <div style="font-size: 13px; color: gray;"><?= $row['location'] ?> | <?= $row['created_at'] ?></div>
      </div>
    <?php endwhile; ?>
  <?php endif; ?>

  <!-- Clear Inbox Form -->
  <form method="POST" onsubmit="return confirm('Are you sure you want to clear all messages?');">
    <button type="submit" name="clear" class="btn">🗑️ Clear Inbox</button>
  </form>

  <a href="volunteer_dashboard.php" class="back-btn">← Back to Dashboard</a>
</div>

</body>
</html>
