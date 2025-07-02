<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm py-2">
  <div class="container">
    <!-- Logo and Brand -->
    <a class="navbar-brand d-flex align-items-center" href="/ReliefLink/index.php">
      <img src="/ReliefLink/assets/images/logo.png" alt="ReliefLink Logo" class="me-2" style="max-height: 55px;">
      <span class="fw-bold text-success" style="font-size: 1.4rem;">Relief-Link</span>
    </a>

    <!-- Toggler for mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navigation Links -->
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item me-2">
          <a class="btn btn-success btn-sm px-3" href="/ReliefLink/user/available_resources.php">
            <i class="fa-solid fa-triangle-exclamation me-1"></i> Resources Available
          </a>
        </li>
        <!-- Emergency Link -->
        <li class="nav-item me-2">
          <a class="btn btn-danger btn-sm px-3" href="/ReliefLink/victim/request_form.php">
            <i class="fa-solid fa-triangle-exclamation me-1"></i> Emergency
          </a>
        </li>

        <!-- Volunteers Dropdown -->
        <li class="nav-item dropdown me-2">
          <a class="nav-link dropdown-toggle fw-semibold" href="#" role="button" data-bs-toggle="dropdown">
            Volunteers
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
           <li>
              <a class="dropdown-item" href="/ReliefLink/user/registered_volunteers.php">
                <i class="fa fa-users me-1"></i> Our Volunteers
              </a>
        </li>
            <li>
              <a class="dropdown-item" href="/ReliefLink/user/add_volunteer.php">
                <i class="fa fa-user-plus me-1"></i> Volunteer Enquiry
              </a>
            </li>
          </ul>
        </li>
        
        <!-- Login Button -->
        <li class="nav-item">
          <a href="/ReliefLink/user/login.php" class="btn btn-outline-primary btn-sm px-3">
            <i class="fa fa-sign-in-alt me-1"></i> Login
          </a>
        </li>

      </ul>
    </div>
  </div>
</nav>
