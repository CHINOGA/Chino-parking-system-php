<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Chino Parking System</title>
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<!-- Custom CSS -->
<link href="custom.css" rel="stylesheet" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="vehicle_entry.php">Chino Parking System</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <?php if (isset($_SESSION['user_id'])): ?>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="vehicle_entry.php">Vehicle Entry</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="reporting.php">Reporting</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="revenue_report.php">Revenue Report</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="sms_send.php">Send SMS</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Profile
          </a>
          <ul class="dropdown-menu dropdown-menu-end bg-dark text-light" aria-labelledby="profileDropdown" style="min-width: 200px;">
            <li class="dropdown-item-text" style="color: #e0e7ff;">
              <?php if (isset($_SESSION['username'])): ?>
                <strong>Username:</strong> <?= htmlspecialchars($_SESSION['username']) ?>
              <?php else: ?>
                Username not available.
              <?php endif; ?>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item text-white" href="change_password.php" style="color: #e0e7ff;" onmouseover="this.style.color='black';" onmouseout="this.style.color='#e0e7ff';">Change Password</a>
            </li>
            <li>
              <form method="post" action="logout.php" onsubmit="return confirm('Are you sure you want to logout?');" class="m-0 p-0">
                <button type="submit" class="dropdown-item text-danger p-2" style="background:none; border:none; width:100%; text-align:left;">Logout</button>
              </form>
            </li>
          </ul>
        </li>
      <?php endif; ?>
    </div>
  </div>
</nav>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
