<?php
session_start();
include __DIR__ . '/../../config/db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$public_users = [];
$result = $conn->query("SELECT * FROM public_users");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $public_users[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Public Users</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
  body {
    background-color: #f0f2f5;
    font-family: 'Segoe UI', sans-serif;
  }

  .container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 20px;
  }

  h2 {
    text-align: center;
    font-weight: 700;
    color: #1b8f64;
    margin-bottom: 30px;
  }

  .table {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    background-color: #fff;
  }

  .table thead {
    background: linear-gradient(to right, #198754, #157347);
    color: #fff;
  }

  .table th, .table td {
    vertical-align: middle;
    text-align: center;
    font-size: 14px;
    padding: 12px;
  }

  .table tbody tr:hover {
    background-color: #f8fdfb;
    transition: background-color 0.3s ease;
  }

  .table img {
    border-radius: 50%;
    object-fit: cover;
    width: 50px;
    height: 50px;
    border: 2px solid #dee2e6;
  }

  .btn-info {
    background-color: #17a2b8;
    border: none;
  }

  .btn-warning, .btn-danger, .btn-info {
    font-size: 13px;
    padding: 5px 10px;
    border-radius: 6px;
  }

  .modal-header {
    border-bottom: none;
    background-color: #198754;
  }

  .modal-title {
    font-weight: 600;
  }

  .aadhaar-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 25px;
    text-align: center;
    box-shadow: 0 0 10px rgba(0,0,0,0.08);
  }

  .aadhaar-card img {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #198754;
    margin-bottom: 15px;
  }

  .aadhaar-card h5 {
    font-weight: bold;
    margin-bottom: 10px;
  }

  .aadhaar-card p {
    font-size: 14px;
    margin-bottom: 6px;
  }

  @media (max-width: 576px) {
    .table td {
      font-size: 12px;
      padding: 10px 6px;
    }

    .btn {
      margin: 2px 0;
    }

    .aadhaar-card img {
      width: 70px;
      height: 70px;
    }
  }
</style>
</head>
<body>

<div class="container">
  <h2>👥 Registered Public Users</h2>
  <div class="table-responsive">
    <table class="table table-bordered align-middle text-center">
      <thead>
        <tr>
          <th>#</th>
          <th>Photo</th>
          <th>Name</th>
          <th>Username</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($public_users)): ?>
          <?php foreach ($public_users as $index => $user): ?>
          <tr>
            <td><?= $index + 1 ?></td>
            <td><img src="../public/uploads/<?= htmlspecialchars($user['photo'] ?? 'default.jpg') ?>" alt="User"></td>
            <td><?= htmlspecialchars($user['full_name']) ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['email'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($user['phone'] ?? 'N/A') ?></td>
            <td>
              <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#viewModal<?= $user['username'] ?>">View</button>
              <a href="delete_public.php?username=<?= $user['username'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">Delete</a>
            </td>
          </tr>

          <!-- Aadhaar Modal -->
          <div class="modal fade" id="viewModal<?= $user['username'] ?>" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header bg-success text-white">
                  <h5 class="modal-title">ID card</h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <div class="aadhaar-card">
                    <img src="../public/uploads/<?= htmlspecialchars($user['photo'] ?? 'default.jpg') ?>" alt="Photo">
                    <h5><?= htmlspecialchars($user['full_name']) ?></h5>
                    <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? 'N/A') ?></p>
                    <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone'] ?? 'N/A') ?></p>
                    <p><strong>Location:</strong> <?= htmlspecialchars($user['location'] ?? 'N/A') ?></p>
                    <p><strong>Gender:</strong> <?= htmlspecialchars($user['gender'] ?? 'N/A') ?></p>
                    <p><strong>Registered:</strong> <?= htmlspecialchars($user['created_at'] ?? 'N/A') ?></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="7" class="text-muted">No users found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <a href="../admin.php" class="btn btn-outline-success mb-3">
  ← Back to Dashboard
</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
