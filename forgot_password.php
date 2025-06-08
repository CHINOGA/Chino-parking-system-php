<?php
session_start();
require_once __DIR__ . '/config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = trim($_POST['username_or_email'] ?? '');

    if ($usernameOrEmail === '') {
        $error = 'Please enter your username or email.';
    } else {
        // Find user by username (assuming no email field in users table)
        $stmt = $pdo->prepare('SELECT id, username FROM users WHERE username = ?');
        $stmt->execute([$usernameOrEmail]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $error = 'User not found.';
        } else {
            // Generate token and expiry (1 hour)
            $token = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', time() + 3600);

            // Insert token into password_resets table
            $stmt = $pdo->prepare('INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)');
            $stmt->execute([$user['id'], $token, $expiresAt]);

            // Send reset email
            $resetLink = sprintf(
                '%s/reset_password.php?token=%s',
                (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']),
                $token
            );

            $to = $user['username'] . '@example.com'; // Placeholder email, adjust as needed
            $subject = 'Password Reset Request';
            $message = "Hello " . htmlspecialchars($user['username']) . ",\n\n";
            $message .= "To reset your password, please click the link below or copy and paste it into your browser:\n\n";
            $message .= $resetLink . "\n\n";
            $message .= "This link will expire in 1 hour.\n\n";
            $message .= "If you did not request a password reset, please ignore this email.\n\n";
            $message .= "Regards,\nChino Parking System";

            $headers = 'From: no-reply@chinoparkingsystem.com' . "\r\n" .
                       'Reply-To: no-reply@chinoparkingsystem.com' . "\r\n" .
                       'X-Mailer: PHP/' . phpversion();

            if (mail($to, $subject, $message, $headers)) {
                $success = 'A password reset link has been sent to your email address.';
            } else {
                $error = 'Failed to send password reset email. Please try again later.';
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
    <?php if ($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <form method="post" action="forgot_password.php" novalidate>
        <label for="username_or_email">Username</label>
        <input type="text" id="username_or_email" name="username_or_email" required autofocus />
        <button type="submit">Send Reset Link</button>
    </form>
    <p><a href="login.php" style="color:#a5b4fc;">Back to Login</a></p>
</div>
</body>
</html>
