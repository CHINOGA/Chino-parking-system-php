<?php
session_start();
require_once __DIR__ . '/config.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = 'Invalid CSRF token.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $error = 'Please enter both username and password.';
        } else {
            $stmt = $pdo->prepare('SELECT id, password_hash FROM users WHERE username = ?');
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $username;
                header('Location: vehicle_entry.php');
                exit;
            } else {
                $error = 'Invalid username or password.';
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
<title>Chino Parking System - Login</title>
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<!-- Custom CSS -->
<link href="custom.css" rel="stylesheet" />
<script>
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
      navigator.serviceWorker.register('service-worker.js')
      .then(function(registration) {
        console.log('ServiceWorker registration successful with scope: ', registration.scope);
      })
      .catch(function(error) {
        console.error('ServiceWorker registration failed:', error);
      });
    });
  }

  // PWA install prompt handling
  let deferredPrompt;
  window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    const installBtn = document.getElementById('installBtn');
    if (installBtn) {
      installBtn.style.display = 'block';
    }
  });

  function installPWA() {
    if (deferredPrompt) {
      deferredPrompt.prompt();
      deferredPrompt.userChoice.then((choiceResult) => {
        if (choiceResult.outcome === 'accepted') {
          console.log('User accepted the install prompt');
          alert('App installed successfully!');
        } else {
          console.log('User dismissed the install prompt');
          alert('App installation dismissed.');
        }
        deferredPrompt = null;
        const installBtn = document.getElementById('installBtn');
        if (installBtn) {
          installBtn.style.display = 'none';
        }
      });
    }
  }

  // Listen for appinstalled event
  window.addEventListener('appinstalled', (evt) => {
    console.log('PWA was installed');
    alert('Thank you for installing the app!');
    const installBtn = document.getElementById('installBtn');
    if (installBtn) {
      installBtn.style.display = 'none';
    }
  });

  // Show password toggle
  function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleBtn = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');
    if (!passwordInput || !toggleBtn || !eyeIcon) {
      console.error('Password input, toggle button, or eye icon not found');
      return;
    }
    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      toggleBtn.setAttribute('aria-label', 'Hide password');
      toggleBtn.setAttribute('aria-pressed', 'true');
      eyeIcon.innerHTML = '<path d="M13.359 11.238a6.5 6.5 0 0 0 1.292-3.238 6.5 6.5 0 0 0-11.292-3.238l1.415 1.415a3 3 0 0 1 4.243 4.243l1.415 1.415z"/><path d="M3.646 3.646a.5.5 0 0 1 .708 0l8 8a.5.5 0 0 1-.708.708l-8-8a.5.5 0 0 1 0-.708z"/>';
      toggleBtn.classList.add('active');
    } else {
      passwordInput.type = 'password';
      toggleBtn.setAttribute('aria-label', 'Show password');
      toggleBtn.setAttribute('aria-pressed', 'false');
      eyeIcon.innerHTML = '<path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/><path d="M8 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6z"/>';
      toggleBtn.classList.remove('active');
    }
  }

  // Client-side validation and form submission handling
  document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('loginForm');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const errorDiv = document.getElementById('clientError');
    const loginButton = document.getElementById('loginButton');
    const forgotPasswordLink = document.querySelector('.forgot-password');

    forgotPasswordLink.addEventListener('click', function (e) {
      e.preventDefault();
      window.location.href = 'forgot_password.php';
    });

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      errorDiv.textContent = '';

      if (usernameInput.value.trim() === '') {
        errorDiv.textContent = 'Please enter your username.';
        usernameInput.focus();
        return;
      }
      if (passwordInput.value === '') {
        errorDiv.textContent = 'Please enter your password.';
        passwordInput.focus();
        return;
      }

      // Disable button and show loading text
      loginButton.disabled = true;
      loginButton.textContent = 'Logging in...';

      form.submit();
    });
  });
</script>
<style>
/* Custom styles for login page */
body {
  background: linear-gradient(to right, #2563eb, #4f46e5);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #f0f0f0;
  margin: 0;
  padding: 0;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}
.topbar {
  background-color: #1e40af;
  color: #e0e7ff;
  font-weight: 700;
  font-size: 1.25rem;
  text-align: center;
  padding: 1rem 0;
  box-shadow: 0 2px 4px rgba(0,0,0,0.2);
  position: sticky;
  top: 0;
  z-index: 1050;
}
.container {
  max-width: 400px;
  margin: 4rem auto 2rem;
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(12px);
  border-radius: 0.5rem;
  padding: 2rem;
  box-shadow: 0 0 20px rgba(0,0,0,0.3);
  display: flex;
  flex-direction: column;
  align-items: center;
}
h2 {
  text-align: center;
  font-weight: 700;
  font-size: 2rem;
  margin-bottom: 1.5rem;
  color: #e0e7ff;
}
.error {
  color: #f87171;
  margin-bottom: 1rem;
  font-weight: 700;
  text-shadow: 0 0 3px rgba(0,0,0,0.7);
}
form {
  width: 100%;
}
form label {
  display: block;
  margin-bottom: 0.25rem;
  font-weight: 700;
  color: #e0e7ff;
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
.show-password-btn {
  position: absolute;
  right: 1rem;
  top: 2.5rem;
  background: none;
  border: none;
  color: #4f46e5;
  font-weight: 700;
  cursor: pointer;
  user-select: none;
}
.form-group {
  position: relative;
}
.forgot-password {
  display: block;
  margin-top: 0.5rem;
  text-align: right;
  font-size: 0.875rem;
  color: #a5b4fc;
  text-decoration: none;
}
.forgot-password:hover {
  text-decoration: underline;
}
</style>
</head>
<body>
<div class="topbar">Chino Parking System</div>
<div class="container">
    <h2>Login</h2>
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <div id="clientError" class="error" role="alert" aria-live="assertive"></div>
    <form id="loginForm" method="post" action="login.php" novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>" />
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required autofocus aria-required="true" aria-describedby="usernameHelp" />
        <label for="password">Password</label>
        <div class="form-group position-relative">
          <input type="password" id="password" name="password" required aria-required="true" aria-describedby="passwordHelp" />
          <button type="button" id="togglePassword" class="btn btn-outline-light btn-sm position-absolute top-50 end-0 translate-middle-y me-2" aria-label="Show password" onclick="togglePassword()" style="border:none; background:none; padding:0;">
            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
              <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/>
              <path d="M8 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6z"/>
            </svg>
          </button>
        </div>
        <a href="#" class="forgot-password" tabindex="0">Forgot password?</a>
        <button type="submit" id="loginButton">Login</button>
    </form>
    <p style="margin-top: 1rem; text-align: center; color: #a5b4fc;">
      Don't have an account? <a href="signup.php" style="color: #e0e7ff; text-decoration: underline;">Sign up</a>
    </p>
</div>
</body>
</html>
