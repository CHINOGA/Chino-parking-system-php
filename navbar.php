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
        <li class="nav-item">
          <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">Profile</a>
        </li>
        <!-- Removed Change Password and Logout links as per request -->
      </ul>

      <!-- Profile Modal -->
      <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content bg-dark text-light">
            <div class="modal-header">
              <h5 class="modal-title" id="profileModalLabel">User Profile</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <?php if (isset($_SESSION['username'])): ?>
                <p><strong>Username:</strong> <?= htmlspecialchars($_SESSION['username']) ?></p>
              <?php else: ?>
                <p>Username not available.</p>
              <?php endif; ?>
              <a href="change_password.php" class="btn btn-primary w-100 mb-2">Change Password</a>
              <form method="post" action="logout.php" onsubmit="return confirm('Are you sure you want to logout?');">
                <button type="submit" class="btn btn-danger w-100">Logout</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</nav>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
