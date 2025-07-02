<?php
$conn = new mysqli("localhost", "root", "", "relieflink");
$result = $conn->query("SELECT id, name, need, location, status, approved_by, created_at FROM victims ORDER BY created_at DESC");
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
    .note {
      font-size: 13px;
      color: gray;
      margin-top: 5px;
    }
  </style>
</head>
<body>

<div class="inbox-container">
  <h2>📬 Victim Requests Inbox</h2>

  <?php while($row = $result->fetch_assoc()): ?>
    <div class="message-preview" onclick="location.href='view_request.php?id=<?= $row['id'] ?>'">
      <strong><?= htmlspecialchars($row['name']) ?></strong> - <?= htmlspecialchars($row['need']) ?>
      <div class="status <?= $row['status'] == 0 ? 'pending' : ($row['status'] == 1 ? 'approved' : 'declined') ?>">
        <?php if ($row['status'] == 0): ?>
          Pending
        <?php elseif ($row['status'] == 1): ?>
          Approved
        <?php else: ?>
          Declined
        <?php endif; ?>
      </div>
      <div class="note">
        <?= htmlspecialchars($row['location']) ?> | <?= $row['created_at'] ?>
        <?php if ($row['status'] == 2 && !empty($row['approved_by'])): ?>
          <br><small>This request has been approved by volunteer: <strong><?= htmlspecialchars($row['approved_by']) ?></strong></small>
        <?php endif; ?>
      </div>
    </div>
  <?php endwhile; ?>
</div>

</body>
</html>
