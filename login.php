<?php
session_start();
require_once __DIR__ . '/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenant_code = preg_replace('/[^A-Za-z0-9]/', '', trim($_POST['tenant_code'] ?? ''));
    $username = preg_replace('/[^A-Za-z0-9_]/', '', trim($_POST['username'] ?? ''));
    $password = $_POST['password'] ?? '';

    if ($tenant_code === '' || $username === '' || $password === '') {
        $error = 'Please fill in all fields.';
    } else {
        // Get tenant_id from tenant_code
        $tenantStmt = $pdo->prepare('SELECT id FROM tenants WHERE name = ?');
        $tenantStmt->execute([$tenant_code]);
        $tenant = $tenantStmt->fetch(PDO::FETCH_ASSOC);

        if (!$tenant) {
            $error = 'Invalid tenant code.';
        } else {
            $tenant_id = $tenant['id'];

            // Get user with matching username, tenant_id and verify password
            $stmt = $pdo->prepare('SELECT id, tenant_id, password_hash FROM users WHERE username = ? AND tenant_id = ?');
            $stmt->execute([$username, $tenant_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $error = 'Invalid username or tenant code combination.';
            } elseif (!password_verify($password, $user['password_hash'])) {
                $error = 'Incorrect password.';
            } else {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['tenant_id'] = $user['tenant_id'];
                header('Location: vehicle_entry.php');
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Login - Chino Parking System</title>
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
label {
    font-weight: 700;
}
input[type="text"],
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
input[type="text"]:focus,
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
<div class="container">
    <h2>Login</h2>
    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" action="login.php" novalidate>
        <label for="tenant_code">Tenant Code</label>
        <input type="text" id="tenant_code" name="tenant_code" required autofocus />
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required />
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required />
        <button type="submit">Login</button>
    </form>
    <p style="margin-top: 1rem; text-align: center;">
        Don't have an account? <a href="signup.php" style="color:#a5b4fc;">Sign Up</a><br />
        <a href="forgot_password.php" style="color:#a5b4fc;">Forgot Password?</a>
    </p>
</div>
</body>
</html>
