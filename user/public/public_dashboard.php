<?php
session_start();
$conn = new mysqli("localhost", "root", "", "relieflink");

if (!isset($_SESSION['public_username'])) {
    header("Location: public_login.php");
    exit();
}

$username = $_SESSION['public_username'];
$user = $conn->query("SELECT * FROM public_users WHERE username = '$username'")->fetch_assoc();
// Fetch live disaster alerts using an external API or AI tool (simulated here)
$alerts = [
    [
        'title' => 'Cyclone Warning in Coastal Region',
        'description' => 'IMD has issued a red alert for heavy rainfall and strong winds in the coastal belt. Evacuations are advised.',
        'region' => 'South-East Coast',
        'created_at' => date('Y-m-d H:i:s')
    ],
    [
        'title' => 'Flash Flood Alert',
        'description' => 'Due to continuous rainfall, low-lying areas are expected to flood within 12 hours. Take precautions and move to higher ground.',
        'region' => 'Kerala Districts',
        'created_at' => date('Y-m-d H:i:s', strtotime('-30 minutes'))
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Public Dashboard | ReliefLink</title>
  <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f3f4f6;
    }

    .sidebar {
      position: fixed;
      left: 0;
      top: 0;
      height: 100vh;
      width: 230px;
      background-color: #111827;
      color: white;
      padding: 2rem 1rem;
    }

    .sidebar .profile {
      text-align: center;
      margin-bottom: 2rem;
    }

    .sidebar .profile img {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #0dcaf0;
    }

    .sidebar .profile h6 {
      margin-top: 10px;
      font-size: 1rem;
      color: #f3f4f6;
    }

    .sidebar a {
      display: block;
      color: #cbd5e1;
      padding: 10px 15px;
      text-decoration: none;
      border-radius: 8px;
      margin-bottom: 8px;
    }

    .sidebar a:hover, .sidebar a.active {
      background-color: #1e293b;
      color: #fff;
    }

    .main {
      margin-left: 240px;
      padding: 2rem;
    }

    .card-box {
      background: white;
      border-radius: 12px;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
      padding: 1.5rem;
      margin-bottom: 2rem;
    }

    .alert-item {
      border-left: 5px solid #ff9800;
      background: #fff7e6;
      padding: 1rem;
      margin-bottom: 1rem;
      border-radius: 8px;
    }

    .btn-primary {
      background: linear-gradient(to right, #0d6efd, #0dcaf0);
      border: none;
    }

    @media (max-width: 768px) {
      .sidebar {
        display: none;
      }
      .main {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>

<div class="sidebar">
  <div class="profile">
    <img src="uploads/<?= $user['photo'] ?>" alt="Profile">
    <h6><?= htmlspecialchars($user['full_name']) ?></h6>
  </div>
  <a href="#" class="active"><i class="fas fa-home me-2"></i>Dashboard</a>
  <a href="./chat_with_volunteers.php"><i class="fas fa-comments me-2"></i>Chat</a>
  <a href="public_settings.php"><i class="fas fa-cog me-2"></i>Settings</a>
  <a href="public_logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
</div>

<div class="main">
  <h3>👋 Welcome, <?= htmlspecialchars($user['full_name']) ?></h3>

  <div class="card-box">
    <h5 class="mb-3">🆘 Submit a Help Request</h5>
    <form action="send_public_request.php" method="POST">
      <div class="mb-3">
        <label class="form-label">What do you need?</label>
        <select name="need" class="form-select" required>
          <option value="">-- Select --</option>
          <option>Food</option>
          <option>Water</option>
          <option>Medical Help</option>
          <option>Shelter</option>
          <option>Evacuation</option>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Additional Information</label>
        <textarea name="message" class="form-control" rows="3" placeholder="Describe your situation..."></textarea>
      </div>
      <button class="btn btn-primary">Send Request</button>
    </form>
  </div>

  <div class="card-box">
    <h5 class="mb-3">🚨 Latest Disaster Alerts</h5>
    <?php foreach($alerts as $alert): ?>
      <div class="alert-item">
        <strong><?= htmlspecialchars($alert['title']) ?></strong><br>
        <?= htmlspecialchars($alert['description']) ?><br>
        <small class="text-muted">Region: <?= htmlspecialchars($alert['region']) ?> | <?= date("d M Y, h:i A", strtotime($alert['created_at'])) ?></small>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
