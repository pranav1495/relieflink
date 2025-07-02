<?php
session_start();
include __DIR__ . '/../../config/db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Registered Volunteers | Admin - Relief-Link</title>
  <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
    .container { padding: 40px; }
    .table thead { background-color: #198754; color: white; }
    .btn-sm { margin-right: 6px; }
    .section-title { margin-top: 60px; }
  </style>
</head>
<body>

<div class="container">
  <h3 class="mb-4">👥 Registered Volunteers</h3>
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th>#</th>
        <th>Photo</th>
        <th>Name</th>
        <th>Username</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Joined</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php
      $result = $conn->query("SELECT * FROM users WHERE role = 'volunteer'");
      $i = 1;
      while ($row = $result->fetch_assoc()):
        $photo = "../../assets/images/" . ($row['photo'] ?? 'default.jpg');
        $name = $row['full_name'] ?? $row['username'];
        $joined = date('d M Y', strtotime($row['created_at']));
    ?>
      <tr>
        <td><?= $i++ ?></td>
        <td><img src="<?= $photo ?>" alt="photo" width="50" height="50" style="border-radius: 50%; object-fit: cover;"></td>
        <td><?= htmlspecialchars($name) ?></td>
        <td><?= htmlspecialchars($row['username']) ?></td>
        <td><?= htmlspecialchars($row['email'] ?? '-') ?></td>
        <td><?= htmlspecialchars($row['phone'] ?? '-') ?></td>
        <td><?= $joined ?></td>
        <td>
          <a href="../view_volunteer.php?username=<?= urlencode($row['username']) ?>" class="btn btn-info btn-sm">View</a>
          <a href="../chat_with_volunteer.php?username=<?= urlencode($row['username']) ?>" class="btn btn-success btn-sm">Chat</a>
        </td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>

  <!-- Pending Volunteer Requests -->
  <h3 class="mb-4 section-title">🕒 Volunteer Enquiries (Pending Approval)</h3>
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th>#</th>
        <th>Photo</th>
        <th>Name</th>
        <th>Username</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php
      $pending = $conn->query("SELECT * FROM venquiry WHERE status = 'pending'");
      $j = 1;
      while ($row = $pending->fetch_assoc()):
        $photo = "../../assets/images/" . ($row['photo'] ?? 'default.jpg');
        $status = ucfirst($row['status']);
    ?>
      <tr>
        <td><?= $j++ ?></td>
        <td><img src="<?= $photo ?>" alt="photo" width="50" height="50" style="border-radius: 50%; object-fit: cover;"></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['username']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['phone']) ?></td>
        <td><span class="badge bg-warning text-dark"><?= $status ?></span></td>
        <td>
          <a href="../view_enquiry.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">View</a>
          <a href="../approve_volunteer.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm">Approve</a>
          <a href="../decline_volunteer.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Decline</a>
        </td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>

  <a href="../admin.php" class="btn btn-secondary mt-4">← Back to Dashboard</a>
</div>

</body>
</html>
