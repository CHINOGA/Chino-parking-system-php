<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/sms_send.php';

$error = '';
$success = '';

function generateTenantCode() {
    return strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($username === '' || $email === '' || $phone === '' || $password === '' || $confirmPassword === '') {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (!preg_match('/^\+?[0-9]{7,15}$/', $phone)) {
        $error = 'Please enter a valid phone number.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        // Check if username or email or phone exists
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ? OR phone = ?');
        $stmt->execute([$username, $email, $phone]);
        if ($stmt->fetch()) {
            $error = 'Username, email, or phone number already exists.';
        } else {
            // Generate tenant code and insert tenant
            $tenantCode = generateTenantCode();

            $tenantStmt = $pdo->prepare('INSERT INTO tenants (name) VALUES (?)');
            if (!$tenantStmt->execute([$tenantCode])) {
                $error = 'Failed to create tenant. Please try again.';
            } else {
                $tenantId = $pdo->lastInsertId();

                // Insert new user with tenant_id
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('INSERT INTO users (tenant_id, username, email, phone, password_hash) VALUES (?, ?, ?, ?, ?)');
                if ($stmt->execute([$tenantId, $username, $email, $phone, $passwordHash])) {
                    // Send SMS with username and tenant code using NextSMS API
                    $message = "Welcome $username! Your tenant code is $tenantCode. Use it to login.";

                    $nextSmsUsername = NEXTSMS_USERNAME;
                    $nextSmsPassword = NEXTSMS_PASSWORD;
                    $nextSmsSenderId = NEXTSMS_SENDER_ID;

                    $smsService = new SmsService();

                    if ($smsService->sendSms($phone, $message)) {
                        $success = 'Account created successfully. You will receive an SMS with your tenant code. You can now <a href="login.php">login</a>.';
                    } else {
                        $error = 'Failed to send SMS with tenant code. Please try again.';
                    }
                } else {
                    $error = 'Failed to create account. Please try again.';
                }
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
<title>Sign Up - Chino Parking System</title>
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
    <h2>Sign Up</h2>
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>
    <form method="post" action="signup.php" novalidate>
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required autofocus />
        <label for="email">Email</label>
        <input type="text" id="email" name="email" required />
        <label for="phone">Phone Number</label>
        <input type="text" id="phone" name="phone" required />
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required />
        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required />
        <button type="submit">Create Account</button>
    </form>
    <p><a href="login.php" style="color:#a5b4fc;">Back to Login</a></p>
</div>
</body>
</html>
