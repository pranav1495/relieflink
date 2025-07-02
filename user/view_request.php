<?php
session_start();
$username = $_SESSION['username'] ?? '';

$conn = new mysqli("localhost", "root", "", "relieflink");
$id = (int)($_GET['id'] ?? 0);

// Handle approval/decline
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $username) {
    $action = $_POST['action'];
    $status = $action === 'approve' ? 1 : 2;

    if ($status === 1) {
        // Approve current, decline all others
        $stmt = $conn->prepare("UPDATE victims SET status = 1, approved_by = ? WHERE id = ?");
        $stmt->bind_param("si", $username, $id);
        $stmt->execute();

        $conn->query("UPDATE victims SET status = 2 WHERE id != $id AND status = 0");
    } else {
        $conn->query("UPDATE victims SET status = 2 WHERE id = $id");
    }

    header("Location: mail.php");
    exit();
}

// Fetch victim request
$res = $conn->query("SELECT * FROM victims WHERE id = $id");
$data = $res->fetch_assoc();

if (!$data) {
    echo "Request not found.";
    exit;
}

// Get approver's full name if different from current user
$approver_name = '';
if (!empty($data['approved_by']) && $data['approved_by'] !== $username) {
    $stmt = $conn->prepare("SELECT full_name FROM users WHERE username = ?");
    $stmt->bind_param("s", $data['approved_by']);
    $stmt->execute();
    $result = $stmt->get_result();
    $approver = $result->fetch_assoc();
    $approver_name = $approver['full_name'] ?? $data['approved_by'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Help Request | Relief-Link</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f4f6f9;
      font-family: 'Segoe UI', sans-serif;
      padding: 30px;
    }
    .message-box {
      max-width: 700px;
      margin: auto;
      background: white;
      padding: 25px 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .message-header h4 {
      margin-bottom: 5px;
      color: #198754;
    }
    .meta {
      font-size: 14px;
      color: #555;
      margin-bottom: 20px;
    }
    .meta a {
      color: #0d6efd;
      text-decoration: underline;
    }
    .message-body {
      white-space: pre-wrap;
      border-top: 1px solid #ddd;
      padding-top: 15px;
      font-size: 16px;
      line-height: 1.5;
    }
    .status-badge {
      display: inline-block;
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 600;
      color: white;
    }
    .pending { background-color: #ffc107; }
    .approved { background-color: #28a745; }
    .declined { background-color: #dc3545; }
    .action-buttons { margin-top: 25px; }
    .btn { margin-right: 10px; }
    .back-link {
      display: block;
      margin-top: 25px;
      text-align: right;
      font-size: 14px;
    }
    .info-msg {
      margin-top: 15px;
      font-size: 15px;
      color: #555;
    }
  </style>
</head>
<body>

<div class="message-box">
  <div class="message-header">
    <h4>🆘 Help Request from <?= htmlspecialchars($data['name']) ?></h4>
    <div class="meta">
      <strong>Email:</strong> <?= htmlspecialchars($data['email']) ?> |
      <strong>Phone:</strong> <a href="tel:<?= htmlspecialchars($data['phone']) ?>"><?= htmlspecialchars($data['phone']) ?></a> |
      <strong>Location:</strong> <?= htmlspecialchars($data['location']) ?>
    </div>
    <div class="meta">
      <strong>Status:</strong>
      <?php if ($data['status'] == 0): ?>
        <span class="status-badge pending">Pending</span>
      <?php elseif ($data['status'] == 1): ?>
        <span class="status-badge approved">Approved</span>
      <?php else: ?>
        <span class="status-badge declined">Declined</span>
      <?php endif; ?>
    </div>
  </div>

  <div class="message-body">
    <?= nl2br(htmlspecialchars($data['need'])) ?>
  </div>

  <?php if ($data['status'] == 0): ?>
    <form method="POST" class="action-buttons">
      <button class="btn btn-success" name="action" value="approve">✅ Approve</button>
      <button class="btn btn-danger" name="action" value="decline">❌ Decline</button>
    </form>
  <?php else: ?>
    <div class="info-msg">
      <?php if ($data['status'] == 1 && $data['approved_by'] === $username): ?>
        ✅ You have already approved this request.
      <?php elseif ($data['status'] == 1 && $data['approved_by'] !== $username): ?>
        ✅ This request has been approved by <strong><?= htmlspecialchars($approver_name) ?></strong>.
      <?php else: ?>
        ❌ This request has been declined.
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <a href="mail.php" class="back-link text-decoration-none">← Back to Inbox</a>
</div>

</body>
</html>
