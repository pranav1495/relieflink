<?php
session_start();
$conn = new mysqli("localhost", "root", "", "relieflink");

if (!isset($_SESSION['public_username'])) {
    header("Location: public_login.php");
    exit();
}

$username = $_SESSION['public_username'];
$user = $conn->query("SELECT * FROM public_users WHERE username = '$username'")->fetch_assoc();

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);
    $photo = $user['photo'];

    // Upload photo if changed
    if (!empty($_FILES['photo']['name'])) {
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photo = uniqid("public_") . "." . $ext;
        move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/" . $photo);
    }

    // Update name and photo
    $stmt = $conn->prepare("UPDATE public_users SET full_name=?, photo=? WHERE username=?");
    $stmt->bind_param("sss", $full_name, $photo, $username);
    $stmt->execute();

    // Handle password update
    if (!empty($current_password) || !empty($new_password) || !empty($confirm_password)) {
        if (password_verify($current_password, $user['password'])) {
            if ($new_password === $confirm_password) {
                $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                $conn->query("UPDATE public_users SET password='$hashed' WHERE username='$username'");
                $success = "Profile and password updated successfully!";
            } else {
                $error = "New password and confirmation do not match!";
            }
        } else {
            $error = "Current password is incorrect!";
        }
    } else {
        $success = "Profile updated successfully!";
    }

    $user = $conn->query("SELECT * FROM public_users WHERE username = '$username'")->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Public Settings | ReliefLink</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <style>
    body {
      background-color: #f1f4f8;
      font-family: 'Segoe UI', sans-serif;
    }
    .card {
      background: #fff;
      border-radius: 12px;
      padding: 2rem;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .form-label {
      font-weight: 600;
    }
    .profile-section {
      display: flex;
      align-items: center;
      gap: 1.5rem;
      flex-wrap: wrap;
    }
    .profile-img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid #dee2e6;
    }
    .btn-primary {
      background-color: #007bff;
      border: none;
    }
    .btn-primary:hover {
      background-color: #0069d9;
    }
    .form-control {
      border-radius: 6px;
    }
    .back-link {
      text-decoration: none;
      color: #6c63ff;
      font-weight: 500;
    }
    .back-link:hover {
      color: #4b47d4;
    }
    .alert {
      font-size: 0.95rem;
    }
  </style>
</head>
<body class="py-5">

<div class="container">
  <div class="card mx-auto" style="max-width: 700px;">
    <h4 class="mb-4 text-primary"><i class="fas fa-user-cog me-2"></i>Public Profile Settings</h4>

    <?php if ($success): ?>
      <div class="alert alert-success"><?= $success ?></div>
    <?php elseif ($error): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3 profile-section">
        <div class="flex-grow-1">
          <label class="form-label">Full Name</label>
          <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name']) ?>" required>
        </div>
        <div>
          <?php if ($user['photo']): ?>
            <img src="uploads/<?= $user['photo'] ?>" alt="Profile Photo" class="profile-img">
          <?php endif; ?>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Change Profile Photo</label>
        <input type="file" name="photo" class="form-control">
      </div>

      <hr class="my-4">

      <div class="mb-3">
        <label class="form-label">Current Password</label>
        <input type="password" name="current_password" class="form-control" placeholder="Enter current password">
      </div>
      <div class="mb-3">
        <label class="form-label">New Password</label>
        <input type="password" name="new_password" class="form-control" placeholder="Enter new password">
      </div>
      <div class="mb-3">
        <label class="form-label">Confirm New Password</label>
        <input type="password" name="confirm_password" class="form-control" placeholder="Confirm new password">
      </div>

      <div class="d-flex justify-content-between align-items-center">
        <button class="btn btn-primary px-4">
          <i class="fas fa-save me-1"></i> Update Profile
        </button>
        <a href="public_dashboard.php" class="back-link">← Back to Dashboard</a>
      </div>
    </form>
  </div>
</div>

</body>
</html>
