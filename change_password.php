<?php
session_start();
require_once __DIR__ . '/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
        $error = 'Please fill in all fields.';
    } elseif ($newPassword !== $confirmPassword) {
        $error = 'New passwords do not match.';
    } elseif (strlen($newPassword) < 6) {
        $error = 'New password must be at least 6 characters.';
    } else {
        // Fetch current password hash
        $stmt = $pdo->prepare('SELECT password_hash FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
            $error = 'Current password is incorrect.';
        } else {
            // Update password
            $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
            $stmt->execute([$newHash, $_SESSION['user_id']]);
            $success = 'Password changed successfully.';
            // Redirect to login page after 3 seconds
            header("refresh:3;url=login.php");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Change Password - Chino Parking System</title>
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
}
h2 {
    text-align: center;
    font-weight: 700;
    font-size: 2rem;
    margin-bottom: 1.5rem;
}
.error {
    color: #f87171;
    margin-bottom: 1rem;
    font-weight: 700;
    text-shadow: 0 0 3px rgba(0,0,0,0.7);
}
.success {
    color: #4ade80;
    margin-bottom: 1rem;
    font-weight: 700;
    text-shadow: 0 0 3px rgba(0,0,0,0.7);
}
label {
    font-weight: 700;
}
input[type="password"] {
    width: 100%;
    padding: 0.75rem;
    border-radius: 0.375rem;
    border: 1px solid #a5b4fc;
    background-color: rgba(255, 255, 255, 0.95);
    color: #111827;
    margin-bottom: 1rem;
    font-size: 1rem;
    box-sizing: border-box;
    outline: none;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}
input[type="password"]:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.5);
}
button {
    width: 100%;
    background-color: #4f46e5;
    color: #e0e7ff;
    font-weight: 700;
    padding: 0.75rem;
    border: none;
    border-radius: 0.375rem;
    cursor: pointer;
    font-size: 1.125rem;
    transition: background-color 0.3s ease;
}
button:hover {
    background-color: #3730a3;
}
</style>
</head>
<body>
     <div class="loader-container" id="loader">
        <div class="loader"></div>
    </div>
<div class="container">
    <h2>Change Password</h2>
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <form method="post" action="change_password.php" novalidate>
        <label for="current_password">Current Password</label>
        <input type="password" id="current_password" name="current_password" required autofocus />
        <label for="new_password">New Password</label>
        <input type="password" id="new_password" name="new_password" required />
        <label for="confirm_password">Confirm New Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required />
        <button type="submit">Change Password</button>
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
