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

// Fetch current volunteer data
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND role = 'volunteer'");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if (!$volunteer = $result->fetch_assoc()) {
    echo "❌ Volunteer not found.";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $photo = $volunteer['photo'];

    // Handle new photo upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../assets/images/volunteers/';
        $fileName = basename($_FILES['photo']['name']);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
            $photo = 'volunteers/' . $fileName;
        }
    }

    // Update query
    $stmt = $conn->prepare("UPDATE users SET photo = ?, full_name = ?, email = ?, phone = ?, address = ? WHERE username = ?");
    $stmt->bind_param("ssssss", $photo, $newName, $email, $phone, $address, $username);

    if ($stmt->execute()) {
        $success = "✅ Volunteer updated successfully.";
        header("Location: view_volunteer.php?username=" . urlencode($username));
        exit();
    } else {
        $error = "❌ Failed to update volunteer.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Volunteer | Relief-Link</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .edit-form {
      max-width: 600px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

<div class="edit-form">
  <h4 class="mb-4">✏️ Edit Volunteer Profile</h4>

  <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Full Name</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($volunteer['full_name'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($volunteer['email'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Phone Number</label>
      <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($volunteer['phone'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Address</label>
      <textarea name="address" class="form-control"><?= htmlspecialchars($volunteer['address'] ?? '') ?></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Photo (optional)</label><br>
      <?php if (!empty($volunteer['photo'])): ?>
        <img src="../assets/images/<?= htmlspecialchars($volunteer['photo']) ?>" alt="Photo" width="80" class="mb-2 rounded-circle"><br>
      <?php endif; ?>
      <input type="file" name="photo" accept="image/*" class="form-control">
    </div>

    <button type="submit" class="btn btn-success">Update Volunteer</button>
    <a href="admin.php#volunteers" class="btn btn-secondary">Cancel</a>
  </form>
</div>

</body>
</html>
