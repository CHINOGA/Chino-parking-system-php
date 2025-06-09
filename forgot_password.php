<?php
session_start();
require_once __DIR__ . '/config.php';

$error = '';
$success = '';
$showForm = false;

require_once __DIR__ . '/SmsService.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emailOrPhone = trim($_POST['email_or_phone'] ?? '');

    if ($emailOrPhone === '') {
        $error = 'Please enter your email or phone number.';
    } else {
        // Find user by email or phone
        $stmt = $pdo->prepare('SELECT id, username, email, phone FROM users WHERE email = ? OR phone = ?');
        $stmt->execute([$emailOrPhone, $emailOrPhone]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $error = 'User not found.';
        } else {
            // Generate OTP and expiry (10 minutes)
            $otp = random_int(100000, 999999);
            $expiresAt = date('Y-m-d H:i:s', time() + 600);

            // Insert OTP into password_resets table (reuse token column for OTP)
            $stmt = $pdo->prepare('INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)');
            $stmt->execute([$user['id'], $otp, $expiresAt]);

            $smsService = new SmsService();

            if (filter_var($emailOrPhone, FILTER_VALIDATE_EMAIL)) {
                // Send reset email with OTP, username, tenant code
                $subject = 'Password Reset OTP';
                $message = "Hello " . htmlspecialchars($user['username']) . ",\n\n";
                $message .= "Your password reset OTP is: $otp\n";
                $tenantId = $user['tenant_id'] ?? null;
                $tenantCode = $tenantId ? htmlspecialchars(getTenantCode($tenantId, $pdo)) : '';
                $message .= "Your tenant code is: " . $tenantCode . "\n\n";
                $message .= "This OTP will expire in 10 minutes.\n\n";
                $message .= "If you did not request a password reset, please ignore this email.\n\n";
                $message .= "Regards,\nChino Parking System";

                $headers = 'From: no-reply@chinoparkingsystem.com' . "\r\n" .
                           'Reply-To: no-reply@chinoparkingsystem.com' . "\r\n" .
                           'X-Mailer: PHP/' . phpversion();

                if (mail($user['email'], $subject, $message, $headers)) {
                    $success = 'A password reset OTP has been sent to your email address.';
                } else {
                    $error = 'Failed to send password reset email. Please try again later.';
                }
            } else {
                // Send OTP via SMS with username and tenant code
                $tenantId = $user['tenant_id'] ?? null;
                $tenantCode = $tenantId ? getTenantCode($tenantId, $pdo) : '';
                $smsMessage = "Hello " . $user['username'] . "! Your password reset OTP is: $otp. Tenant code: $tenantCode. It expires in 10 minutes.";
                if ($smsService->sendSms($user['phone'], $smsMessage)) {
                    $success = 'A password reset OTP has been sent to your phone number.';
                } else {
                    $error = 'Failed to send password reset SMS. Please try again later.';
                }
            }
        }
    }
}

function getTenantCode($tenantId, $pdo) {
    $stmt = $pdo->prepare('SELECT name FROM tenants WHERE id = ?');
    $stmt->execute([$tenantId]);
    $tenant = $stmt->fetch(PDO::FETCH_ASSOC);
    return $tenant ? $tenant['name'] : '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Forgot Password - Chino Parking System</title>
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
    <h2>Forgot Password</h2>
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success && !$showForm): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (!$showForm): ?>
    <form method="post" action="forgot_password.php" novalidate>
        <label for="email_or_phone">Email or Phone Number</label>
        <input type="text" id="email_or_phone" name="email_or_phone" required autofocus />
        <button type="submit">Send Reset OTP</button>
    </form>
    <?php endif; ?>
    <?php if ($showForm): ?>
    <form method="post" action="forgot_password.php" novalidate>
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required autofocus />
        <label for="tenant_code">Tenant Code</label>
        <input type="text" id="tenant_code" name="tenant_code" required />
        <label for="otp">OTP</label>
        <input type="text" id="otp" name="otp" required />
        <label for="new_password">New Password</label>
        <input type="password" id="new_password" name="new_password" required />
        <label for="confirm_password">Confirm New Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required />
        <button type="submit" name="reset_password">Reset Password</button>
    </form>
    <?php endif; ?>
    <p><a href="login.php" style="color:#a5b4fc;">Back to Login</a></p>
</div>
</body>
</html>
