<?php
include __DIR__ . '/../config/db.php';

// Fetch volunteers
$result = $conn->query("SELECT * FROM users WHERE role = 'volunteer'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Our Volunteers | Relief-Link</title>
  <link rel="stylesheet" href="/ReliefLink/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background-color: #f0f2f5;
      font-family: 'Segoe UI', sans-serif;
      padding: 40px 20px;
    }
    .volunteer-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.07);
      padding: 20px;
      text-align: center;
      transition: 0.3s;
      height: 100%;
    }
    .volunteer-card:hover {
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
      transform: translateY(-5px);
    }
    .volunteer-img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid #198754;
      margin-bottom: 15px;
    }
    .volunteer-name {
      font-weight: 600;
      font-size: 1.1rem;
    }
    .volunteer-actions {
      margin-top: 15px;
    }
  </style>
</head>
<body>

<div class="container">
  <h3 class="mb-4 text-success text-center">👥 Meet Our Volunteers</h3>

  <?php if ($result && $result->num_rows > 0): ?>
    <div class="row g-4">
      <?php while ($row = $result->fetch_assoc()):
        $photoFile = $row['photo'] ?? 'default.jpg';
        $photoPath = "/ReliefLink/assets/images/" . basename($photoFile);
        $name = $row['full_name'] ?? $row['username'];
        $username = htmlspecialchars($row['username']);
      ?>
        <div class="col-lg-4 col-md-6 col-sm-12">
          <div class="volunteer-card">
            <img src="<?= htmlspecialchars($photoPath) ?>" alt="Volunteer Photo" class="volunteer-img"
                 onerror="this.onerror=null;this.src='/ReliefLink/assets/images/default.jpg';">
            <div class="volunteer-name text-dark"><?= htmlspecialchars($name) ?></div>
            <p class="text-muted mb-1"><i class="fa fa-user"></i> <?= $username ?></p>
            <p class="text-muted mb-1"><i class="fa fa-envelope"></i> <?= htmlspecialchars($row['email'] ?? '-') ?></p>
            <p class="text-muted mb-3"><i class="fa fa-phone"></i> <?= htmlspecialchars($row['phone'] ?? '-') ?></p>

            <div class="volunteer-actions">
              <a href="/ReliefLink/user/view_volunteers.php?username=<?= urlencode($username) ?>" class="btn btn-outline-primary btn-sm">
                <i class="fa fa-user-circle me-1"></i> View Profile
              </a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-warning text-center">No volunteers found at this time.</div>
  <?php endif; ?>

  <div class="text-center mt-4">
    <a href="/ReliefLink/index.php" class="btn btn-outline-secondary">← Back to Home</a>
  </div>
</div>

</body>
</html>
