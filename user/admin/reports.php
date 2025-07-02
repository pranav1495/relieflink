<?php
session_start();
include __DIR__ . '/../../config/db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Summary counts
$totalRequests = $conn->query("SELECT COUNT(*) as total FROM victims")->fetch_assoc()['total'];
$pendingRequests = $conn->query("SELECT COUNT(*) as total FROM victims WHERE status = 0")->fetch_assoc()['total'];
$approvedRequests = $conn->query("SELECT COUNT(*) as total FROM victims WHERE status = 1")->fetch_assoc()['total'];
$declinedRequests = $conn->query("SELECT COUNT(*) as total FROM victims WHERE status = 2")->fetch_assoc()['total'];
$totalVolunteers = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'volunteer'")->fetch_assoc()['total'];
$availableResources = $conn->query("SELECT COUNT(*) as total FROM resources WHERE status = 'Available'")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reports | Relief-Link</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      background: linear-gradient(to right, #f0f4f8, #e6f0ea);
      font-family: 'Segoe UI', sans-serif;
      padding: 40px 20px;
      color: #333;
    }

    .report-container {
      max-width: 1100px;
      margin: auto;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }

    .header h2 {
      font-weight: 700;
      color: #198754;
    }

    .card {
      border-radius: 14px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.08);
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card-body {
      padding: 30px 25px;
      text-align: center;
    }

    .card-body h4 {
      font-size: 28px;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .card-title {
      font-size: 15px;
      font-weight: 500;
    }

    .report-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 24px;
    }

    canvas {
      margin-top: 50px;
      background: white;
      padding: 30px;
      border-radius: 14px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    }

    .btn {
      margin-top: 40px;
      border-radius: 10px;
      padding: 10px 24px;
    }
  </style>
</head>
<body>

<div class="report-container">
  <div class="header">
    <h2>📊 Admin Reports Overview</h2>
    <a href="../admin.php" class="btn btn-outline-success">← Back to Dashboard</a>
  </div>

  <div class="report-grid">
    <div class="card text-white bg-primary">
      <div class="card-body">
        <h4><?= $totalRequests ?></h4>
        <p class="card-title">Total Help Requests</p>
      </div>
    </div>

    <div class="card text-white bg-warning">
      <div class="card-body">
        <h4><?= $pendingRequests ?></h4>
        <p class="card-title">Pending Requests</p>
      </div>
    </div>

    <div class="card text-white bg-success">
      <div class="card-body">
        <h4><?= $approvedRequests ?></h4>
        <p class="card-title">Approved Requests</p>
      </div>
    </div>

    <div class="card text-white bg-danger">
      <div class="card-body">
        <h4><?= $declinedRequests ?></h4>
        <p class="card-title">Declined Requests</p>
      </div>
    </div>

    <div class="card text-white bg-info">
      <div class="card-body">
        <h4><?= $totalVolunteers ?></h4>
        <p class="card-title">Total Volunteers</p>
      </div>
    </div>

    <div class="card text-white bg-secondary">
      <div class="card-body">
        <h4><?= $availableResources ?></h4>
        <p class="card-title">Available Resources</p>
      </div>
    </div>
  </div>

  <canvas id="requestChart" height="120"></canvas>
</div>

<script>
  const ctx = document.getElementById('requestChart').getContext('2d');
  const chart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Pending', 'Approved', 'Declined'],
      datasets: [{
        label: 'Help Requests',
        data: [<?= $pendingRequests ?>, <?= $approvedRequests ?>, <?= $declinedRequests ?>],
        backgroundColor: ['#ffc107', '#28a745', '#dc3545'],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom'
        },
        title: {
          display: true,
          text: 'Help Request Status Distribution'
        }
      }
    }
  });
</script>
</body>
</html>