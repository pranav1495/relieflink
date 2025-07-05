<?php
session_start();
include __DIR__ . '/../config/db.php';

// Handle form submission
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $photo = $_FILES['photo']['name'] ?? '';

    if (!$name || !$username || !$email || !$phone || !$password || !$confirm_password || !$photo) {
        $error = "All fields are required.";
    } elseif (!preg_match('/^[6-9]\d{9}$/', $phone)) {
        $error = "Invalid phone number. Must be a 10-digit number starting with 6-9.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // Upload photo
        $targetDir = "../assets/images/";
        $photoPath = $targetDir . basename($photo);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $photoPath);

        $stmt = $conn->prepare("INSERT INTO venquiry (name, username, password, email, phone, photo, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->bind_param("ssssss", $name, $username, $hashed, $email, $phone, $photo);

        if ($stmt->execute()) {
            $success = "Volunteer enquiry submitted successfully!";
        } else {
            $error = "Failed to submit: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Volunteer | Relief-Link</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <style>
    body {
      background: linear-gradient(to right, #e0f2f1, #e8f5e9);
      font-family: 'Segoe UI', sans-serif;
      padding: 40px;
    }
    .form-card {
      background: white;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      max-width: 600px;
      margin: auto;
    }
    .form-title {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 25px;
      text-align: center;
      color: #198754;
    }
    .form-control, .btn {
      border-radius: 8px;
    }
  </style>
</head>
<body>

<div class="form-card">
  <div class="form-title">📝 Register New Volunteer</div>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php elseif ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label>Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Username</label>
      <input type="text" name="username" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Phone</label>
      <input type="tel" name="phone" class="form-control" required 
             pattern="[6-9]\d{9}" maxlength="10"
             title="Enter a valid 10-digit Indian phone number starting with 6-9">
    </div>

    <div class="mb-3">
      <label>Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Confirm Password</label>
      <input type="password" name="confirm_password" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Upload Photo</label>
      <input type="file" name="photo" class="form-control" accept="assets/images/" required>
    </div>

    <button type="submit" class="btn btn-success w-100">Submit for Approval</button>
  </form>
</div>

<a href="../index.php" class="btn btn-secondary mt-4 d-block mx-auto" style="max-width: 600px;">
  ← Back to Dashboard
</a>

<!-- JavaScript to limit phone to 10 digits and numbers only -->
<script>
document.querySelector('input[name="phone"]').addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '').slice(0, 10);
});
</script>

</body>
</html>
