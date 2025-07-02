<?php
session_start();
include __DIR__ . '/../../config/db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $quantity = intval($_POST['quantity'] ?? 0);
    $location = trim($_POST['location'] ?? '');
    $status = $_POST['status'] ?? 'Available';

    if (!$name || !$category || !$quantity || !$location || !$status) {
        $error = "All fields are required.";
    } elseif ($quantity < 1) {
        $error = "Quantity must be at least 1.";
    } else {
        $stmt = $conn->prepare("INSERT INTO resources (name, category, quantity, location, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiss", $name, $category, $quantity, $location, $status);

        if ($stmt->execute()) {
            $success = "Resource added successfully!";
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Resource | Admin - Relief-Link</title>
  <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
  <style>
    body {
      background: #f9fdfb;
      font-family: 'Segoe UI', sans-serif;
      padding: 40px;
    }
    .form-container {
      max-width: 600px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .form-title {
      font-size: 22px;
      font-weight: 600;
      margin-bottom: 25px;
      color: #198754;
      text-align: center;
    }
    .form-control, .btn {
      border-radius: 8px;
    }
  </style>
</head>
<body>

<div class="form-container">
  <div class="form-title">➕ Add New Resource</div>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php elseif ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <label>Resource Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Category</label>
      <select name="category" class="form-control" required>
        <option value="">-- Select --</option>
        <option value="Food">Food</option>
        <option value="Medicine">Medicine</option>
        <option value="Clothes">Clothes</option>
        <option value="Shelter">Shelter</option>
        <option value="Hygiene">Hygiene</option>
        <option value="Water">Water</option>
      </select>
    </div>

    <div class="mb-3">
      <label>Quantity</label>
      <input type="number" name="quantity" class="form-control" min="1" required>
    </div>

    <div class="mb-3">
      <label>Location</label>
      <input type="text" name="location" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Status</label>
      <select name="status" class="form-control" required>
        <option value="Available">Available</option>
        <option value="Used">Used</option>
        <option value="In Transit">In Transit</option>
      </select>
    </div>

    <button type="submit" class="btn btn-success w-100">Add Resource</button>
    <a href="resources.php" class="btn btn-secondary w-100 mt-2">← Back to Resources</a>
  </form>
</div>

</body>
</html>
