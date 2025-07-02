<?php
session_start();
include __DIR__ . '/../config/db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch volunteers with photos
$volunteers = [];
$result = $conn->query("SELECT username, full_name, photo FROM users WHERE role='volunteer'");
while ($row = $result->fetch_assoc()) {
    $volunteers[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | Relief-Link</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fa;
    }

    .dashboard-container {
      display: flex;
      height: 100vh;
      overflow: hidden;
    }

    .sidebar {
      width: 240px;
      background-color: #198754;
      color: white;
      flex-shrink: 0;
      display: flex;
      flex-direction: column;
      padding: 1rem;
    }

    .sidebar h4 {
      text-align: center;
      margin-bottom: 2rem;
      color: white;
    }

    .logo-img {
      height: 50px;
      vertical-align: middle;
      margin-right: 8px;
      transition: transform 0.6s ease;
    }

    .logo-img:hover {
      transform: rotate(360deg);
    }

    .sidebar a {
      color: white;
      text-decoration: none;
      padding: 0.75rem 1rem;
      display: block;
      border-radius: 8px;
      transition: background 0.2s;
    }

    .sidebar a:hover {
      background-color: #157347;
    }

    .main-content {
      flex: 1;
      overflow-y: auto;
      padding: 2rem;
      position: relative;
    }

    .topbar {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      margin-bottom: 2rem;
    }

    .profile-menu {
      position: relative;
    }

    .profile-icon {
      cursor: pointer;
      border-radius: 50%;
      background-color: #dee2e6;
      padding: 0.1rem;
    }

    .dropdown-menu {
      display: none;
      position: absolute;
      right: 0;
      top: 100%;
      background-color: white;
      border: 1px solid #ccc;
      border-radius: 0.5rem;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
      min-width: 160px;
      z-index: 1000;
    }

    .dropdown-menu a {
      display: block;
      padding: 10px 16px;
      text-decoration: none;
      color: #333;
    }

    .dropdown-menu a:hover {
      background-color: #f0f0f0;
    }

    .map-container {
      width: 100%;
      height: 400px;
      border-radius: 12px;
      overflow: hidden;
      margin-bottom: 30px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .volunteer-gallery {
      display: flex;
      gap: 1rem;
      overflow-x: auto;
      padding-bottom: 10px;
    }

    .volunteer-card {
      flex: 0 0 auto;
      width: 120px;
      text-align: center;
    }

    .volunteer-card img {
      width: 100%;
      height: 160px;
      object-fit: cover;
      border-radius: 12px;
      border: 3px solid #198754;
      transition: transform 0.3s ease;
    }

    .volunteer-card img:hover {
      animation: jumpIn 0.4s ease;
      cursor: pointer;
    }

    @keyframes jumpIn {
      0% { transform: scale(1) translateY(0); }
      50% { transform: scale(1.05) translateY(-5px); }
      100% { transform: scale(1) translateY(0); }
    }

    .volunteer-card p {
      font-size: 14px;
      margin-top: 5px;
    }

    .section-title {
      font-weight: bold;
      margin-bottom: 15px;
      font-size: 18px;
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
      <h4>
        <img src="../assets/images/logo.png" alt="Logo" class="logo-img">
        Relief-Link
      </h4>
      <a href="admin/volunteers.php"><i class="fas fa-users me-2"></i>Volunteers</a>
      <a href="admin/resources.php"><i class="fas fa-boxes me-2"></i>Resources</a>
      <a href="admin/reports.php"><i class="fas fa-chart-line me-2"></i>Reports</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <!-- Topbar -->
      <div class="topbar">
        <div class="profile-menu">
          <img src="../assets/images/profile-admin.svg" alt="Admin" width="40" height="40" class="profile-icon" onclick="toggleDropdown()">
          <div class="dropdown-menu" id="dropdownMenu">
            <a href="admin/settings.php">Settings</a>
            <a href="../logout.php">Logout</a>
          </div>
        </div>
      </div>

      <h1 class="mb-4">Welcome Admin</h1>
      <div class="section-title">🌍 Live Global Disaster Map</div>
      <div class="map-container">
        <iframe 
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31520472.21896211!2d60.90551205000001!3d11.130135999999994!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTHCsDA4JzQ4LjAiTiA3NMKwMTUnMDguMCJF!5e0!3m2!1sen!2sin!4v1700000000000" 
          width="100%" 
          height="100%" 
          style="border:0;" 
          allowfullscreen="" 
          loading="lazy" 
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>

      <!-- Volunteer Portraits -->
      <div class="section-title">👥 Active Volunteers</div>
      <div class="volunteer-gallery">
        <?php foreach ($volunteers as $vol): ?>
          <div class="volunteer-card">
            <img src="../assets/images/<?= htmlspecialchars($vol['photo'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($vol['username']) ?>">
            <p><?= htmlspecialchars($vol['full_name'] ?? ucwords($vol['username'])) ?></p>
          </div>
        <?php endforeach; ?>
      </div>

    </div>
  </div>

  <script>
    function toggleDropdown() {
      const menu = document.getElementById('dropdownMenu');
      menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    }

    document.addEventListener('click', function (e) {
      const profile = document.querySelector('.profile-icon');
      const dropdown = document.getElementById('dropdownMenu');
      if (!profile.contains(e.target)) {
        dropdown.style.display = 'none';
      }
    });
  </script>
</body>
</html>
