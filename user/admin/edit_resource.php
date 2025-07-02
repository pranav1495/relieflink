<?php
session_start();
include __DIR__ . '/../../config/db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    die("Invalid resource ID.");
}

$success = $error = '';

// Fetch resource
$stmt = $conn->prepare("SELECT * FROM resources WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$resource = $result->fetch_assoc();
$stmt->close();

if (!$resource) {
    die("Resource not found.");
}

// Update on form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $quantity = intval($_POST['quantity']);
    $location = trim($_POST['location']);
    $status = $_POST['status'];

    if (!$name || !$category || !$quantity || !$location || !$status) {
        $error = "All fields are required.";
    } else {
        $stmt = $conn->prepare("UPDATE resources SET name=?, category=?, quantity=?, location=?, status=? WHERE id=?");
        $stmt->bind_param("ssissi", $name, $category, $quantity, $location, $status, $id);
        if ($stmt->execute()) {
            $success = "Resource updated successfully!";
            // Refresh data after update
            $resource = [
                'name' => $name,
                'category' => $category,
                'quantity' => $quantity,
                'location' => $location,
                'status' => $status
            ];
        } else {
            $error = "Update failed: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Resource | Admin - Relief-Link</title>
  <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
  <style>
    body {
      background: #f9fdfb;
      padding: 40px;
      font-family: 'Segoe UI', sans-serif;
    }
    .form-box {
      background: white;
      max-width: 600px;
      margin: auto;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(0,0,0,0.08);
    }
    .form-title {
      font-size: 22px;
      font-weight: 600;
      color: #198754;
      text-align: center;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

<div class="form-box">
  <div class="form-title">✏️ Edit Resource</div>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php elseif ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <label>Resource Name</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($resource['name']) ?>" required>
    </div>

    <div class="mb-3">
      <label>Category</label>
      <select name="category" class="form-control" required>
        <?php
        $categories = ['Food', 'Medicine', 'Clothes', 'Shelter', 'Hygiene', 'Water'];
        foreach ($categories as $cat) {
            $selected = $resource['category'] === $cat ? 'selected' : '';
            echo "<option value='$cat' $selected>$cat</option>";
        }
        ?>
      </select>
    </div>

    <div class="mb-3">
      <label>Quantity</label>
      <input type="number" name="quantity" class="form-control" value="<?= $resource['quantity'] ?>" required>
    </div>

    <div class="mb-3">
      <label>Location</label>
      <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($resource['location']) ?>" required>
    </div>

    <div class="mb-3">
      <label>Status</label>
      <select name="status" class="form-control" required>
        <?php
        $statuses = ['Available', 'Used', 'In Transit'];
        foreach ($statuses as $stat) {
            $selected = $resource['status'] === $stat ? 'selected' : '';
            echo "<option value='$stat' $selected>$stat</option>";
        }
        ?>
      </select>
    </div>

    <button type="submit" class="btn btn-success w-100">Update Resource</button>
    <a href="resources.php" class="btn btn-secondary w-100 mt-2">← Back to Resources</a>
  </form>
</div>

</body>
</html>
