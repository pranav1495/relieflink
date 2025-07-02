<?php
session_start();
include __DIR__ . '/../../config/db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch all resources
$result = $conn->query("SELECT * FROM resources ORDER BY added_on DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Resources | Admin - Relief-Link</title>
  <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
  <style>
    body {
      background: #f9fdfb;
      font-family: 'Segoe UI', sans-serif;
      padding: 40px;
    }
    .container {
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(0,0,0,0.08);
    }
    .btn-sm {
      margin-right: 5px;
    }
    .status-Available {
      color: green;
      font-weight: bold;
    }
    .status-Used {
      color: #dc3545;
      font-weight: bold;
    }
    .status-InTransit {
      color: #ffc107;
      font-weight: bold;
    }
  </style>
</head>
<body>

<div class="container">
  <h2 class="mb-4 text-success">📦 Resource Inventory</h2>

  <a href="add_resource.php" class="btn btn-success mb-3">+ Add New Resource</a>

  <?php if ($result->num_rows > 0): ?>
    <table class="table table-bordered table-hover">
      <thead class="table-success">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Category</th>
          <th>Quantity</th>
          <th>Location</th>
          <th>Status</th>
          <th>Added On</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['category']) ?></td>
            <td><?= $row['quantity'] ?></td>
            <td><?= htmlspecialchars($row['location']) ?></td>
            <td class="status-<?= str_replace(' ', '', $row['status']) ?>">
              <?= $row['status'] ?>
            </td>
            <td><?= date("d M Y, h:i A", strtotime($row['added_on'])) ?></td>
            <td>
              <a href="edit_resource.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
              <a href="delete_resource.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this resource?')">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="text-center my-5">
      <img src="../../assets/images/empty_inbox.svg" alt="No Resources" style="max-width: 250px;">
      <h4 class="text-muted mt-3">No resources added yet</h4>
      <p>Add items like food, medicine, clothing, or shelter kits here.</p>
    </div>
  <?php endif; ?>
</div>
<a href="../admin.php" class="btn btn-secondary w-10 mt-2">← Back</a>
</body>
</html>
