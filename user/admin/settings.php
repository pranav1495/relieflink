<?php
session_start();
include __DIR__ . '/../../config/db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch current admin details
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND role = 'admin'");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// Handle success/error messages
$success = $_SESSION['settings_success'] ?? '';
$error = $_SESSION['settings_error'] ?? '';
unset($_SESSION['settings_success'], $_SESSION['settings_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Settings | Relief-Link</title>
  <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
      padding: 2rem;
    }
    .settings-container {
      max-width: 600px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .profile-pic {
      width: 120px;
      height: 120px;
      object-fit: cover;
      border-radius: 50%;
      border: 4px solid #198754;
    }
  </style>
</head>
<body>
<div class="settings-container">
  <h3 class="mb-4">⚙️ Admin Settings</h3>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php elseif ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <div class="text-center mb-4">
    <img src="../../assets/images/<?= htmlspecialchars($admin['photo'] ?? 'admin.jpg') ?>" class="profile-pic" alt="Admin Photo">
  </div>

  <!-- Update Info -->
  <form action="update_settings.php" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="full_name" class="form-label">Full Name</label>
      <input type="text" name="full_name" id="full_name" class="form-control" value="<?= htmlspecialchars($admin['full_name'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($admin['email'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
      <label for="photo" class="form-label">Change Photo</label>
      <input type="file" name="photo" id="photo" class="form-control">
    </div>
    <button type="submit" name="update_info" class="btn btn-success w-100">💾 Update Profile</button>
  </form>

  <hr class="my-4">

  <!-- Update Password -->
  <form action="update_settings.php" method="POST">
    <h5 class="mb-3">🔒 Change Password</h5>
    <div class="mb-3">
      <input type="password" name="current_password" class="form-control" placeholder="Current Password" required>
    </div>
    <div class="mb-3">
      <input type="password" name="new_password" class="form-control" placeholder="New Password" required>
    </div>
    <div class="mb-3">
      <input type="password" name="confirm_password" class="form-control" placeholder="Confirm New Password" required>
    </div>
    <button type="submit" name="update_password" class="btn btn-warning w-100">🔄 Update Password</button>
  </form>
</div>
<div class="mb-3">
  <a href="../admin.php" class="btn btn-outline-secondary">
    ← Back to Dashboard
  </a>
</div>

</body>
</html>
