<?php
$conn = new mysqli("localhost", "root", "", "relieflink");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear'])) {
    $conn->query("DELETE FROM victims");
    header("Location: mail.php");
    exit;
}

$result = $conn->query("SELECT id, name, need, location, status, created_at FROM victims ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Inbox | ReliefLink</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      background: #f0f2f5;
      margin: 0;
      padding: 0;
    }

    .inbox-container {
      max-width: 800px;
      margin: 20px auto;
      background: #fff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }

    .message-preview {
      padding: 15px;
      border-bottom: 1px solid #ddd;
      cursor: pointer;
      transition: background 0.2s ease-in-out;
    }

    .message-preview:hover {
      background: #f9f9f9;
    }

    .message-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }

    .message-header strong {
      font-size: 16px;
      color: #222;
    }

    .status {
      font-weight: bold;
      font-size: 14px;
      margin-top: 5px;
    }

    .status.pending { color: orange; }
    .status.approved { color: green; }
    .status.declined { color: red; }

    .meta {
      font-size: 13px;
      color: gray;
      margin-top: 5px;
    }

    .btn, .back-btn {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 16px;
      font-size: 14px;
      border-radius: 6px;
      text-align: center;
      text-decoration: none;
    }

    .btn {
      background-color: #dc3545;
      color: white;
      border: none;
      cursor: pointer;
    }

    .btn:hover {
      background-color: #c82333;
    }

    .back-btn {
      color: #007bff;
    }

    .back-btn:hover {
      text-decoration: underline;
    }

    @media (max-width: 600px) {
      .message-header {
        flex-direction: column;
        align-items: flex-start;
      }

      .status {
        align-self: flex-start;
        margin-top: 8px;
      }

      .btn, .back-btn {
        width: 100%;
        margin-top: 10px;
      }
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
        <div class="message-header">
          <strong><?= htmlspecialchars($row['name']) ?> - <?= htmlspecialchars($row['need']) ?></strong>
          <div class="status <?= $row['status'] == 0 ? 'pending' : ($row['status'] == 1 ? 'approved' : 'declined') ?>">
            <?= $row['status'] == 0 ? 'Pending' : ($row['status'] == 1 ? 'Approved' : 'Declined') ?>
          </div>
        </div>
        <div class="meta"><?= htmlspecialchars($row['location']) ?> | <?= htmlspecialchars($row['created_at']) ?></div>
      </div>
    <?php endwhile; ?>
  <?php endif; ?>

  <form method="POST" onsubmit="return confirm('Are you sure you want to clear all messages?');">
    <button type="submit" name="clear" class="btn">🗑️ Clear Inbox</button>
  </form>

  <a href="volunteer_dashboard.php" class="back-btn">← Back to Dashboard</a>
</div>

</body>
</html>
