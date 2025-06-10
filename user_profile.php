<?php
session_start();
require_once __DIR__ . '/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'] ?? 'Unknown';

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>User Profile - Chino Parking System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="custom.css" rel="stylesheet" />
<style>
.container {
    max-width: 400px;
    margin: 4rem auto 2rem;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(12px);
    border-radius: 0.5rem;
    padding: 2rem;
    box-shadow: 0 0 20px rgba(0,0,0,0.3);
    color: #f0f0f0;
    display: flex;
    flex-direction: column;
    align-items: center;
}
h2 {
    text-align: center;
    font-weight: 700;
    font-size: 2rem;
    margin-bottom: 1.5rem;
}
.info {
    font-size: 1.125rem;
    margin-bottom: 1rem;
}
button, a.btn-link {
    width: 100%;
    background-color: #4f46e5;
    color: #e0e7ff;
    font-weight: 700;
    padding: 0.75rem;
    border: none;
    border-radius: 0.375rem;
    cursor: pointer;
    font-size: 1.125rem;
    text-align: center;
    text-decoration: none;
    display: block;
    margin-bottom: 1rem;
    transition: background-color 0.3s ease;
}
button:hover, a.btn-link:hover {
    background-color: #3730a3;
}
</style>
</head>
<body>
     <div class="loader-container" id="loader">
        <div class="loader"></div>
    </div>
<div class="container">
    <h2>User Profile</h2>
    <div class="info"><strong>Username:</strong> <?= htmlspecialchars($username) ?></div>
    <a href="change_password.php" class="btn-link" role="button">Change Password</a>
    <form method="post" action="user_profile.php" onsubmit="return confirm('Are you sure you want to logout?');">
        <button type="submit" name="logout">Logout</button>
    </form>
</div>
<script>
 window.addEventListener('load', () => {
        const loader = document.getElementById('loader');
        loader.style.display = 'none';
    });
</script>
</body>
</html>
