<?php
session_start();
require_once __DIR__ . '/db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);

  if (!empty($email)) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
      $token = bin2hex(random_bytes(16));
      $stmt = $pdo->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
      $stmt->execute([$token, $email]);

      $resetLink = app_base_url() . "/admin/reset_password.php?token=" . urlencode($token);

      if (app_mail_enabled()) {
        $sent = app_send_mail($email, $user['first_name'] ?? '', 'Reset Your Password - MoodBuddy',
          "<p>Click the link below to reset your MoodBuddy password:</p><a href='$resetLink'>$resetLink</a>");
        if ($sent !== true) error_log('Mail error: ' . $sent);
      } else {
        // No SMTP configured: link is only written to the server log (never shown publicly)
        error_log('Password reset link for ' . $email . ': ' . $resetLink);
      }

      $message = "success";
    } else {
      $message = "not_found";
    }
  } else {
    $message = "empty";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>MoodBuddy - Forgot Password</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    :root { --theme-color: #ff681a; --theme-color2: #37d4d9; }
    body {
      font-family: 'Jost', sans-serif;
      background: linear-gradient(135deg, var(--theme-color), var(--theme-color2));
      height: 100vh; display: flex; justify-content: center; align-items: center; margin: 0;
    }
    .login-container {
      max-width: 500px; width: 100%; padding: 40px;
      background: white; border-radius: 12px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    .logo { text-align: center; margin-bottom: 30px; }
    .logo img { height: 60px; }
    .form-control { padding: 12px 15px; border-radius: 8px; }
    .vs-btn.style4 {
      display: block; width: 100%;
      background: linear-gradient(135deg, var(--theme-color), var(--theme-color2));
      border: none; padding: 12px; border-radius: 8px;
      font-weight: 600; color: white; transition: 0.3s; text-align: center;
    }
    .vs-btn.style4:hover { background: var(--theme-color); }
    .extra-links { margin-top: 15px; text-align: center; }
    .extra-links a { color: var(--theme-color); text-decoration: none; margin: 0 10px; font-weight: 500; }
    .extra-links a:hover { text-decoration: underline; }
  </style>
</head>
<body>
  <div class="login-container mx-auto">
    <div class="logo">
      <img src="../assets/img/moodbuddy-logo-white.png" alt="MoodBuddy">
    </div>
    <h3 class="text-center mb-4">Forgot Password</h3>
    
    <form method="POST" action="">
      <div class="mb-3">
        <label for="email" class="form-label">Enter your email</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>
      <button type="submit" class="vs-btn style4">Send Reset Link</button>
    </form>

    <div class="extra-links mt-3">
      <a href="index.php">Back to Login</a>
    </div>
  </div>

  <?php if ($message === "success"): ?>
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Link Sent!',
        text: 'If email is configured on this server, a reset link has been sent to your inbox.',
        confirmButtonColor: '#3085d6'
      });
    </script>
  <?php elseif ($message === "not_found"): ?>
    <script>
      Swal.fire({ icon: 'error', title: 'Email not found!' });
    </script>
  <?php elseif ($message === "empty"): ?>
    <script>
      Swal.fire({ icon: 'warning', title: 'Please enter your email' });
    </script>
  <?php endif; ?>
</body>
</html>
