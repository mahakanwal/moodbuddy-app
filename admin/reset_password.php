<?php
session_start();
require_once __DIR__ . '/db.php';

$message = "";
$token = $_GET['token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $token = $_POST['token'];
  $password = $_POST['password'];
  $confirm = $_POST['confirm'];

  if ($password === $confirm) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
      $hashed = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE id = ?");
      $stmt->execute([$hashed, $user['id']]);
      $message = "success";
    } else {
      $message = "invalid";
    }
  } else {
    $message = "mismatch";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>MoodBuddy - Reset Password</title>
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
    <h3 class="text-center mb-4">Reset Password</h3>
    
    <form method="POST" action="">
      <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
      <div class="mb-3">
        <label for="password" class="form-label">New Password</label>
        <input type="password" class="form-control" name="password" required>
      </div>
      <div class="mb-3">
        <label for="confirm" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" name="confirm" required>
      </div>
      <button type="submit" class="vs-btn style4">Reset Password</button>
    </form>

    <div class="extra-links mt-3">
      <a href="index.php">Back to Login</a>
    </div>
  </div>

  <?php if ($message === "success"): ?>
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Password Updated!',
        text: 'Now you can login with your new password',
        confirmButtonColor: '#3085d6'
      }).then(() => { window.location = 'index.php'; });
    </script>
  <?php elseif ($message === "invalid"): ?>
    <script>Swal.fire({ icon: 'error', title: 'Invalid reset token!' });</script>
  <?php elseif ($message === "mismatch"): ?>
    <script>Swal.fire({ icon: 'warning', title: 'Passwords do not match!' });</script>
  <?php endif; ?>
</body>
</html>
