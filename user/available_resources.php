<?php
include __DIR__ . '/../config/db.php';

// Fetch only available resources
$result = $conn->query("SELECT * FROM resources WHERE status = 'Available' ORDER BY added_on DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Available Relief Resources | Relief-Link</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f4fdf7;
      font-family: 'Segoe UI', sans-serif;
      padding: 40px;
    }
    .container {
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(0,0,0,0.08);
    }
    .section-title {
      font-size: 24px;
      font-weight: bold;
      color: #198754;
      text-align: center;
      margin-bottom: 30px;
    }
    .resource-card {
      border: 1px solid #d1e7dd;
      border-left: 5px solid #198754;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 15px;
      background-color: #f8fdfb;
    }
    .resource-card h5 {
      color: #14532d;
      margin-bottom: 8px;
    }
    .no-data {
      text-align: center;
      color: #888;
    }
    .no-data img {
      max-width: 250px;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="section-title">✅ Currently Available Relief Resources</div>

  <?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
      <div class="resource-card">
        <h5><?= htmlspecialchars($row['name']) ?> (<?= $row['category'] ?>)</h5>
        <p><strong>Quantity:</strong> <?= $row['quantity'] ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($row['location']) ?></p>
        <p><strong>Added on:</strong> <?= date("d M Y, h:i A", strtotime($row['added_on'])) ?></p>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="no-data">
      <img src="../assets/images/empty_inbox.svg" alt="No Available Resources">
      <h5>No resources available right now.</h5>
      <p>Please check back later or contact local relief centers.</p>
    </div>
  <?php endif; ?>
</div>

</body>
</html>
