<?php
$conn = new mysqli("localhost", "root", "", "relieflink");
$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST["full_name"]);
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $confirm = trim($_POST["confirm"]);

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM public_users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username already taken.";
        } else {
            $photo = "";
            if ($_FILES['photo']['name']) {
                $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                $photo = uniqid("public_") . "." . $ext;
                move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/" . $photo);
            }
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO public_users (username, password, full_name, photo) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $hashed, $full_name, $photo);
            $stmt->execute();
            $success = "Registration successful. <a href='public_login.php'>Login here</a>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register | Relief-Link</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #0d6efd, #00c8ff);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Segoe UI', sans-serif;
    }
    .register-box {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
      width: 100%;
      max-width: 450px;
      animation: fadeIn 0.8s ease-in-out;
    }
    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(30px);}
      to {opacity: 1; transform: translateY(0);}
    }
    .form-control:focus {
      box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25);
      border-color: #0d6efd;
    }
    .form-label {
      font-weight: 500;
    }
    .btn-primary {
      background: linear-gradient(135deg, #0d6efd, #0dcaf0);
      border: none;
    }
    .btn-primary:hover {
      background: linear-gradient(135deg, #0a58ca, #0dcaf0);
    }
  </style>
</head>
<body>
<div class="register-box">
  <h4 class="text-center mb-4">📋 Public User Registration</h4>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php elseif ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Full Name</label>
      <input name="full_name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Username</label>
      <input name="username" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Password</label>
      <input name="password" type="password" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Confirm Password</label>
      <input name="confirm" type="password" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Profile Photo</label>
      <input name="photo" type="file" class="form-control">
    </div>
    <button class="btn btn-primary w-100">Register</button>
    <div class="text-center mt-3">
      <a href="http://localhost/ReliefLink/user/login.php" class="text-decoration-none text-primary">Already have an account? Login</a>
    </div>
  </form>
</div>
</body>
</html>
