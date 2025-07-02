<?php
session_start();
include __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password_hash'])) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            if ($row['role'] === 'admin') {
                header("Location: ../user/admin.php");
            } else {
                header("Location: ../user/volunteer_dashboard.php");
            }
            exit();
        } else {
            $error = "❌ Incorrect password.";
        }
    } else {
        $error = "❌ User not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | Relief-Link</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <style>
    body {
  background: url('../assets/images/bgg.jpg') no-repeat center center fixed;
  background-size: cover;
  font-family: 'Segoe UI', sans-serif;
  margin: 0;
  padding: 0;
}


    .login-box {
      backdrop-filter: blur(12px);
      background: rgba(255, 255, 255, 0.12);
      border: 1px solid rgba(255, 255, 255, 0.3);
      border-radius: 20px;
      padding: 2.5rem 2rem;
      max-width: 420px;
      margin: 7% auto;
      box-shadow: 0 10px 35px rgba(0, 0, 0, 0.4);
      animation: slideFadeIn 1s ease;
      position: relative;
      color: white;
    }

    .login-box::before {
      content: "";
      position: absolute;
      top: -2px;
      left: -2px;
      right: -2px;
      bottom: -2px;
      z-index: -1;
      background: linear-gradient(135deg, #0d6efd, #00d4ff, #0dcaf0);
      border-radius: 22px;
      filter: blur(15px);
      opacity: 0.7;
      animation: pulseBorder 4s infinite linear;
    }

    @keyframes pulseBorder {
      0% { filter: blur(15px); opacity: 0.6; }
      50% { filter: blur(20px); opacity: 0.9; }
      100% { filter: blur(15px); opacity: 0.6; }
    }

    @keyframes slideFadeIn {
      from {
        transform: translateY(30px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .logo {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-bottom: 2rem;
    }

    .logo img {
      height: 48px;
      margin-right: 10px;
    }

    .logo span {
      font-size: 1.8rem;
      font-weight: bold;
      color: #ffffff;
      letter-spacing: 1px;
    }

    .form-control {
      background: rgba(255, 255, 255, 0.15);
      border: none;
      border-radius: 12px;
      color: white;
      padding: 0.75rem;
      font-size: 1rem;
      transition: 0.3s;
    }

    .form-control::placeholder {
      color: rgba(255, 255, 255, 0.6);
    }

    .form-control:focus {
      background: rgba(255, 255, 255, 0.2);
      box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
      border: none;
      color: white;
    }

    .btn-primary {
      background: linear-gradient(135deg, #0d6efd, #0dcaf0);
      border: none;
      border-radius: 12px;
      padding: 0.6rem;
      font-weight: 600;
      font-size: 1rem;
      margin-top: 1rem;
      transition: 0.3s ease-in-out;
    }

    .btn-primary:hover {
      background: linear-gradient(135deg, #0056b3, #0dcaf0);
    }

    .error-msg {
      color: #ffdddd;
      background-color: rgba(220, 53, 69, 0.2);
      border: 1px solid #dc3545;
      border-radius: 10px;
      padding: 8px 12px;
      font-size: 0.9rem;
      margin-bottom: 1rem;
    }

    .text-secondary {
      color: rgba(255, 255, 255, 0.8) !important;
    }

    .text-secondary:hover {
      color: #fff !important;
      text-decoration: underline;
    }

    @media (max-width: 480px) {
      .login-box {
        margin-top: 15%;
        padding: 2rem 1.5rem;
      }

      .logo span {
        font-size: 1.4rem;
      }
    }
  </style>
</head>
<body>

  <div class="login-box">
    <div class="logo">
      <img src="../assets/images/logo.png" alt="Relief Link Logo">
      <span>Relief - Link</span>
    </div>

    <?php if (isset($error)): ?>
      <div class="error-msg"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" autocomplete="off">
      <div class="mb-3">
        <input type="text" name="username" class="form-control" placeholder="Username" required>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Login</button>
      <div class="mt-3 text-center">
        <a href="../index.php" class="text-decoration-none text-secondary">← Back to Home</a>
      </div>
    </form>
  </div>

</body>
</html>
