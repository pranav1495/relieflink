<?php
session_start();
include __DIR__ . '/../config/db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$id = $_GET['id'] ?? '';
if (!$id) {
    echo "Invalid enquiry ID.";
    exit();
}

$stmt = $conn->prepare("SELECT * FROM venquiry WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$enquiry = $result->fetch_assoc();

if (!$enquiry) {
    echo "Enquiry not found.";
    exit();
}

$photoPath = "../assets/images/" . ($enquiry['photo'] ?? 'default.jpg');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Enquiry - Relief-Link</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <style>
    body { background-color: #f0f8ff; font-family: 'Segoe UI', sans-serif; padding: 40px; }
    .card { max-width: 600px; margin: auto; box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
    .avatar { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; }
    .back-btn { margin-top: 20px; }
  </style>
</head>
<body>

<div class="card p-4">
  <div class="text-center mb-4">
    <img src="<?= htmlspecialchars($photoPath) ?>" class="avatar mb-2" alt="Photo">
    <h4><?= htmlspecialchars($enquiry['name']) ?></h4>
    <small class="text-muted">Username: <?= htmlspecialchars($enquiry['username']) ?></small>
  </div>

  <ul class="list-group list-group-flush">
    <li class="list-group-item"><strong>Email:</strong> <?= htmlspecialchars($enquiry['email']) ?></li>
    <li class="list-group-item"><strong>Phone:</strong> <?= htmlspecialchars($enquiry['phone']) ?></li>
    <li class="list-group-item"><strong>Status:</strong> 
      <span class="badge bg-<?= $enquiry['status'] === 'pending' ? 'warning text-dark' : ($enquiry['status'] === 'approved' ? 'success' : 'danger') ?>">
        <?= ucfirst($enquiry['status']) ?>
      </span>
    </li>
  </ul>

  <div class="d-flex justify-content-between mt-4">
    <a href="approve_volunteer.php?id=<?= $enquiry['id'] ?>" class="btn btn-success">Approve</a>
    <a href="decline_volunteer.php?id=<?= $enquiry['id'] ?>" class="btn btn-danger">Decline</a>
    <a href="http://localhost/ReliefLink/user/admin/volunteers.php" class="btn btn-secondary">← Back</a>
  </div>
</div>

</body>
</html>
