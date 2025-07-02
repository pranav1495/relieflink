<?php
session_start();
include __DIR__ . '/../config/db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['username'])) {
    echo "❌ Volunteer username not provided.";
    exit();
}

$username = $_GET['username'];
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND role = 'volunteer'");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if (!$row = $result->fetch_assoc()) {
    echo "❌ Volunteer not found.";
    exit();
}

$fullName = $row['full_name'] ?? ucwords($row['username']);
$email = $row['email'] ?? 'Not Provided';
$phone = $row['phone'] ?? 'Not Provided';
$address = $row['address'] ?? 'Not Provided';
$joined = date("F j, Y", strtotime($row['created_at']));
$photo = !empty($row['photo']) ? "../assets/images/" . $row['photo'] : "../assets/images/default.jpg";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Volunteer ID | Relief-Link</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <style>
    body {
      background: #f0f4f5;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px;
    }

    .id-card {
      width: 420px;
      background: white;
      border-radius: 15px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.1);
      overflow: hidden;
      text-align: center;
      padding: 25px;
    }

    .id-card img.photo {
      width: 120px;
      height: 120px;
      object-fit: cover;
      border-radius: 12px;
      margin-bottom: 20px;
      border: 4px solid #198754;
    }

    .id-card h5 {
      margin: 0 0 10px;
      font-weight: bold;
      color: #198754;
    }

    .id-card p {
      margin: 5px 0;
      font-size: 15px;
    }

    .buttons {
      display: flex;
      justify-content: space-between;
      margin-top: 25px;
    }

    .buttons a {
      width: 48%;
    }
  </style>
</head>
<body>

<div class="id-card">
  <img src="<?= htmlspecialchars($photo) ?>" alt="Volunteer Photo" class="photo">
  <h5><?= htmlspecialchars($fullName) ?></h5>
  <p><strong>Username:</strong> <?= htmlspecialchars($row['username']) ?></p>
  <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
  <p><strong>Phone:</strong> <?= htmlspecialchars($phone) ?></p>
  <p><strong>Address:</strong> <?= htmlspecialchars($address) ?></p>
  <p><strong>Joined On:</strong> <?= htmlspecialchars($joined) ?></p>

  <div class="buttons">
    <a href="http://localhost/ReliefLink/user/admin/volunteers.php" class="btn btn-secondary">← Back</a>
    <a href="chat_with_volunteer.php?username=<?= urlencode($row['username']) ?>" class="btn btn-success">💬 Chat</a>
  </div>
</div>

</body>
</html>
