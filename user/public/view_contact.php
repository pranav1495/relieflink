<?php
session_start();
include __DIR__ . '/../../config/db.php';

if (!isset($_SESSION['public_username']) || !isset($_GET['volunteer'])) {
    die("Unauthorized access.");
}

$volunteer = $_GET['volunteer'];

$stmt = $conn->prepare("SELECT full_name, email, phone, photo FROM users WHERE username = ? AND role = 'volunteer'");
$stmt->bind_param("s", $volunteer);
$stmt->execute();
$result = $stmt->get_result();
$details = $result->fetch_assoc();

if (!$details) {
    echo "Volunteer not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Volunteer Contact</title>
  <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
</head>
<body>
  <div class="container mt-5">
    <div class="card p-4 shadow">
      <div class="text-center">
        <img src="../../assets/images/<?= htmlspecialchars($details['photo']) ?>" class="rounded-circle mb-3" width="120" height="120" alt="Profile">
        <h4><?= htmlspecialchars($details['full_name']) ?></h4>
        <p><strong>Email:</strong> <?= htmlspecialchars($details['email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($details['phone']) ?></p>
        <a href="chat_with_volunteers.php" class="btn btn-secondary mt-3">Back</a>
      </div>
    </div>
  </div>
</body>
</html>
