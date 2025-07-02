<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Request Help | Relief-Link</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">

  <style>
    body {
      background-color: #f0f2f5;
      padding: 2rem 1rem;
      font-family: 'Segoe UI', sans-serif;
    }

    .form-container {
      max-width: 600px;
      background: #fff;
      margin: auto;
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 0 20px rgba(0,0,0,0.08);
    }

    .form-container h4 {
      margin-bottom: 1.5rem;
      font-weight: bold;
      color: #0d6efd;
    }

    .form-control {
      border-radius: 0.4rem;
    }

    .form-control:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.25);
    }

    .btn-primary {
      width: 100%;
      padding: 0.6rem;
    }

    .alert {
      display: none;
      margin-top: 1rem;
    }
  </style>
</head>
<body>

  <div class="form-container mt-4">
    <h4>🆘 Request Emergency Help</h4>

    <!-- Alert box for dynamic messages -->
    <div id="alertBox" class="alert" role="alert"></div>

    <form id="helpForm">
      <div class="mb-3">
        <input type="text" name="name" placeholder="Full Name" class="form-control" required>
      </div>
      <div class="mb-3">
        <input type="email" name="email" placeholder="Email" class="form-control" required>
      </div>
      <div class="mb-3">
        <input type="text" name="phone" id="phone" placeholder="Phone Number" class="form-control" 
               pattern="[0-9]{10}" maxlength="10" title="Enter 10 digit phone number" required>
      </div>
      <div class="mb-3">
        <input type="text" name="location" placeholder="Your Current Location" class="form-control" required>
      </div>
      <div class="mb-3">
        <textarea name="need" placeholder="What help do you need?" class="form-control" rows="3" required></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Submit Request</button>
      <a href="../index.php" class="btn btn-outline-secondary mt-3 w-100">← Back to Home</a>
    </form>
  </div>

  <!-- jQuery (from CDN) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- AJAX logic -->
  <script>
    $('#helpForm').on('submit', function(e) {
      e.preventDefault();

      const phone = $('[name="phone"]').val().trim();

      if (!/^[0-9]{10}$/.test(phone)) {
        $('#alertBox')
          .removeClass('alert-success')
          .addClass('alert-danger')
          .text("❌ Enter a valid 10-digit phone number.")
          .fadeIn().delay(4000).fadeOut();
        return;
      }

      $.ajax({
        url: '../api/submit_request.php',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
          const res = typeof response === 'string' ? JSON.parse(response) : response;

          if (res.status === "success") {
            $('#alertBox')
              .removeClass('alert-danger')
              .addClass('alert-success')
              .text("✅ " + res.message)
              .fadeIn().delay(3000).fadeOut();
            $('#helpForm')[0].reset();
          } else {
            $('#alertBox')
              .removeClass('alert-success')
              .addClass('alert-danger')
              .text("❌ " + res.message)
              .fadeIn().delay(4000).fadeOut();
          }
        },
        error: function(xhr) {
          $('#alertBox')
            .removeClass('alert-success')
            .addClass('alert-danger')
            .text("❌ Submission failed. Please try again.")
            .fadeIn().delay(4000).fadeOut();
        }
      });
    });
  </script>
</body>
</html>
