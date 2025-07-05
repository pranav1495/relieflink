<?php
include __DIR__ . '/../config/db.php';

// Redirect if username not provided
if (!isset($_GET['username'])) {
  header("Location: /ReliefLink/user/registered_volunteers.php");
  exit();
}

$username = $_GET['username'];

// Fetch volunteer details
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND role = 'volunteer'");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$volunteer = $result->fetch_assoc();

// If not found
if (!$volunteer) {
  echo "<h4 style='text-align:center;color:red;'>Volunteer not found!</h4>";
  exit();
}

// Setup variables
$fullName = $volunteer['full_name'] ?? $volunteer['username'];
$photoFile = $volunteer['photo'] ?? 'default.jpg';
$photoPath = "/ReliefLink/assets/images//" . basename($photoFile);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($fullName) ?> | Relief-Link Volunteer</title>
  <link rel="stylesheet" href="/ReliefLink/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background-color: #f9f9f9;
      font-family: 'Segoe UI', sans-serif;
      padding: 40px;
    }
    .profile-card {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
      padding: 30px;
      max-width: 600px;
      margin: auto;
      text-align: center;
    }
    .profile-img {
      width: 120px;
      height: 120px;
      object-fit: cover;
      border-radius: 50%;
      border: 4px solid #198754;
      margin-bottom: 20px;
    }
    .info {
      font-size: 16px;
      margin-bottom: 10px;
    }
    .info i {
      margin-right: 8px;
      color: #198754;
    }
  </style>
</head>
<body>

<div class="profile-card">
  <img src="<?= htmlspecialchars($photoPath) ?>"
       alt="Volunteer Photo"
       class="profile-img"
       onerror="this.onerror=null;this.src='/ReliefLink/assets/images/default.jpg';">

  <h4><?= htmlspecialchars($fullName) ?></h4>
  <p class="text-muted">@<?= htmlspecialchars($volunteer['username']) ?></p>

  <div class="info"><i class="fa fa-envelope"></i> <?= htmlspecialchars($volunteer['email'] ?? '-') ?></div>
  <div class="info"><i class="fa fa-phone"></i> <?= htmlspecialchars($volunteer['phone'] ?? '-') ?></div>
  <div class="info"><i class="fa fa-map-marker-alt"></i> <?= htmlspecialchars($volunteer['address'] ?? 'N/A') ?></div>

  <br><br>
  <a href="/ReliefLink/user/registered_volunteers.php" class="btn btn-outline-secondary">← Back to Volunteers</a>
</div>

</body>
</html>
