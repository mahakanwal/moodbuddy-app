<?php
session_start();

// If already logged in
if (isset($_SESSION['logged_in'])) {
  header('Location: ../mood_result.php');
  exit();
}

require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
      if ($user['is_verified'] == 0) {
        $error = "⚠️ Please verify your email first!";
      } else {
        // Promote the owner account to admin (set ADMIN_EMAIL in environment variables)
        $adminEmail = app_env('ADMIN_EMAIL');
        if ($adminEmail && strcasecmp($adminEmail, $user['email']) === 0 && $user['role'] !== 'admin') {
          $pdo->prepare("UPDATE users SET role = 'admin' WHERE id = ?")->execute([$user['id']]);
          $user['role'] = 'admin';
        }

        // Save session data
        session_regenerate_id(true);
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $user['username'];
        $_SESSION['first_name'] = $user['first_name']; 
        $_SESSION['id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['profile_photo'] = $user['profile_photo'];

        // Redirect to home.php with welcome message
        header("Location: ../mood_result.php?welcome=" . urlencode($user['first_name']));
        exit();
      }
    } else {
      $error = "Invalid email or password!";
    }
  } catch (PDOException $e) {
    error_log($e->getMessage());
    $error = "Something went wrong. Please try again.";
  }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MoodBuddy - Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    :root {
      --primary-color: #06b6d4;
      --secondary-color: #ec4899;
      --primary-gradient: linear-gradient(135deg, #06b6d4 0%, #ec4899 100%);
      --metallic-shine: linear-gradient(45deg, #06b6d4, #0891b2, #ec4899, #db2777);
      --light-bg: #ffffff;
      --card-bg: rgba(255, 255, 255, 0.95);
      --text-primary: #1f2937;
      --text-secondary: #4b5563;
      --border-light: rgba(6, 182, 212, 0.2);
      --glow-pink: rgba(236, 72, 153, 0.4);
      --glow-blue: rgba(6, 182, 212, 0.4);
    }

    body {
      font-family: 'Exo 2', sans-serif;
      background: var(--light-bg);
      color: var(--text-primary);
      min-height: 100vh;
      padding: 20px;
      position: relative;
      overflow-x: hidden;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    
    .bg-animation {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      overflow: hidden;
      background: linear-gradient(45deg, #f8fafc 0%, #e0f2fe 25%, #fce7f3 50%, #e0f2fe 75%, #f8fafc 100%);
      background-size: 400% 400%;
      animation: gradientShift 15s ease infinite;
    }

    @keyframes gradientShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    .floating-object {
      position: absolute;
      opacity: 0.3;
      animation: float 20s infinite linear;
      filter: drop-shadow(0 0 10px var(--glow-blue));
      z-index: -1;
    }

    @keyframes float {
      0% { 
        transform: translateY(0px) rotate(0deg); 
        filter: drop-shadow(0 0 10px var(--glow-blue));
      }
      25% { 
        transform: translateY(-20px) rotate(90deg); 
        filter: drop-shadow(0 0 15px var(--glow-pink));
      }
      50% { 
        transform: translateY(0px) rotate(180deg); 
        filter: drop-shadow(0 0 20px var(--glow-blue));
      }
      75% { 
        transform: translateY(20px) rotate(270deg); 
        filter: drop-shadow(0 0 15px var(--glow-pink));
      }
      100% { 
        transform: translateY(0px) rotate(360deg); 
        filter: drop-shadow(0 0 10px var(--glow-blue));
      }
    }

    .login-container {
      max-width: 500px;
      width: 100%;
      background: var(--card-bg);
      backdrop-filter: blur(20px);
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 15px 35px rgba(6, 182, 212, 0.1);
      position: relative;
      overflow: hidden;
    }

    .login-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      border-radius: 20px;
      padding: 2px;
      background: var(--primary-gradient);
      mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
      mask-composite: exclude;
      z-index: -1;
    }

    .logo {
      text-align: center;
      margin-bottom: 30px;
    }

    .logo-text {
      font-family: 'Orbitron', monospace;
      font-size: 2.5rem;
      font-weight: 900;
      background: var(--metallic-shine);
      background-size: 300% 300%;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      animation: shimmer 3s ease-in-out infinite;
    }

    @keyframes shimmer {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    h3 {
      font-family: 'Orbitron', monospace;
      font-size: 1.8rem;
      text-align: center;
      margin-bottom: 30px;
      background: var(--primary-gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: var(--text-primary);
    }

    .form-control {
      width: 100%;
      padding: 15px;
      border: 2px solid var(--border-light);
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.9);
      color: var(--text-primary);
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 15px var(--glow-blue);
      background: white;
    }

    .btn-login {
      background: var(--primary-gradient);
      color: white;
      border: none;
      padding: 15px;
      border-radius: 25px;
      font-weight: 700;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      transition: all 0.3s ease;
      font-size: 1rem;
      position: relative;
      overflow: hidden;
      cursor: pointer;
      width: 100%;
    }

    .btn-login::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
      transition: left 0.5s ease;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px var(--glow-blue);
      filter: brightness(1.1);
    }

    .btn-login:hover::before {
      left: 100%;
    }

    .extra-links {
      margin-top: 20px;
      text-align: center;
    }

    .extra-links a {
      color: var(--primary-color);
      text-decoration: none;
      margin: 0 10px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .extra-links a:hover {
      color: var(--secondary-color);
      text-shadow: 0 0 10px var(--glow-pink);
    }

    @media (max-width: 768px) {
      .login-container {
        padding: 30px 20px;
      }
      
      .logo-text {
        font-size: 2rem;
      }
      
      h3 {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body>
  <div class="bg-animation">
    <div class="floating-object" style="top: 10%; left: 10%; animation-delay: 0s;">
      <i class="fas fa-cube" style="font-size: 2.5rem; color: var(--primary-color);"></i>
    </div>
    <div class="floating-object" style="top: 60%; left: 80%; animation-delay: 5s;">
      <i class="fas fa-gem" style="font-size: 2rem; color: var(--secondary-color);"></i>
    </div>
    <div class="floating-object" style="top: 30%; left: 70%; animation-delay: 10s;">
      <i class="fas fa-star" style="font-size: 1.8rem; color: var(--primary-color);"></i>
    </div>
    <div class="floating-object" style="top: 80%; left: 20%; animation-delay: 15s;">
      <i class="fas fa-diamond" style="font-size: 3rem; color: var(--secondary-color);"></i>
    </div>
  </div>

  <div class="login-container">
    <div class="logo">
      <div class="logo-text">MoodBuddy</div>
    </div>
    
    <h3>Login to Your Account</h3>
    
    <form method="POST" action="">
      <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" name="email" required>
      </div>
      
      <div class="form-group">
        <label class="form-label">Password</label>
        <input type="password" class="form-control" name="password" required>
      </div>
      
      <button type="submit" class="btn-login">
        <i class="fas fa-sign-in-alt"></i> Login
      </button>
    </form>

    <div class="extra-links">
      <a href="forgot_password.php"><i class="fas fa-key"></i> Forgot Password?</a> 
      <a href="register.php"><i class="fas fa-user-plus"></i> Register</a>
    </div>
  </div>

  <?php if (isset($error)): ?>
  <script>
    Swal.fire({
      icon: 'warning',
      title: 'Login Error',
      text: '<?php echo $error; ?>',
      background: 'rgba(255, 255, 255, 0.95)',
      backdrop: 'rgba(6, 182, 212, 0.2)',
      confirmButtonColor: '#06b6d4',
      confirmButtonText: 'Try Again'
    });
  </script>
  <?php endif; ?>
</body>
</html>