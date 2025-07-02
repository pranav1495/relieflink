<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Disaster Relief Resource Tracker</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">

  <!-- Animations -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

  <!-- Custom CSS -->
  <style>
    /* Navbar Logo Styling */
    .navbar-brand img {
      max-height: 60px;
    }

    @media (max-width: 768px) {
      .navbar-brand {
        text-align: center;
        width: 100%;
      }
    }

    .hero {
      background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://i.imgur.com/4ZRkzmb.jpg') no-repeat center center;
      background-size: cover;
      color: white;
      padding: 80px 20px;
      text-align: center;
    }

    .hero h1 {
      font-size: 2.2rem;
      color:white;
    }

    .hero p {
      font-size: 1.1rem;
    }

    .stats-box {
      background: #f8f9fa;
      padding: 2rem;
      border-radius: 1rem;
      text-align: center;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      transition: transform 0.3s;
    }

    .stats-box:hover {
      transform: scale(1.03);
    }

    .back-to-top {
      display: none;
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 999;
    }

    @media (max-width: 576px) {
      .hero h1 {
        font-size: 1.6rem;
      }

      .hero p {
        font-size: 1rem;
      }

      .stats-box {
        padding: 1.5rem;
      }

      .btn-lg {
        font-size: 1rem;
        padding: 0.6rem 1.2rem;
      }
    }
  </style>
</head>
<body>

 <!-- Include Navbar -->
  <?php include('navbar.php'); ?>
  <!-- Hero Section -->
  <div class="hero">
    <h1 class="animate__animated animate__fadeInDown">Disaster Relief Resource Tracker</h1>
    <p class="lead animate__animated animate__fadeInUp">Crowd sourced help during floods, earthquakes, and other emergencies</p>
    <div class="d-flex justify-content-center flex-wrap mt-4 animate__animated animate__fadeInUp">
      <a href="victim/request_form.php" class="btn btn-danger btn-lg m-2">Request Help</a>
    </div>
  </div>

  <!-- Stats Section -->
  <div class="container my-5">
    <div class="row g-4">
      <div class="col-12 col-md-4">
        <div class="stats-box">
          <h2 data-toggle="counter-up">25</h2>
          <p>Relief Centers</p>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="stats-box">
          <h2 data-toggle="counter-up">1320</h2>
          <p>Victims Helped</p>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="stats-box">
          <h2 data-toggle="counter-up">245</h2>
          <p>Volunteers</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Back to Top Button -->
  <a href="#" class="btn btn-primary btn-lg back-to-top">↑</a>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/waypoints/lib/jquery.waypoints.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/counterup2@1.0.7/dist/index.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/build/js/tempusdominus-bootstrap-4.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>
</body>
</html>
