<?php
require_once __DIR__ . '/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name     = trim($_POST['first_name']);
    $last_name      = trim($_POST['last_name']);
    $username       = trim($_POST['username']);
    $email          = trim($_POST['email']);
    $password       = $_POST['password'];
    $confirm_pass   = $_POST['confirm_password'];

    // Profile photo handling
    $default_photo = "default.jpg";
    $profile_photo = $default_photo;

    if (!empty($_FILES['profile_photo']['name'])) {
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = time() . "_" . preg_replace('/[^A-Za-z0-9._-]/', '_', basename($_FILES['profile_photo']['name']));
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $targetFilePath)) {
            $profile_photo = $fileName;
        }
    }

    // Validations
    if (empty($first_name) || empty($last_name) || empty($username) || empty($email) || empty($password) || empty($confirm_pass)) {
        $error = '⚠️ All fields are required!';
    } elseif (!preg_match("/^[a-zA-Z ]+$/", $first_name) || !preg_match("/^[a-zA-Z ]+$/", $last_name)) {
        $error = '⚠️ Name can only contain letters!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = '⚠️ Invalid email format!';
    } elseif ($password !== $confirm_pass) {
        $error = '⚠️ Passwords do not match!';
    } elseif (strlen($password) < 6) {
        $error = '⚠️ Password must be at least 6 characters!';
    } else {
        try {
            // Check duplicate
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);

            if ($stmt->rowCount() > 0) {
                $error = '⚠️ Username or Email already exists!';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $role = "user"; 
                $token = bin2hex(random_bytes(32)); // email verification token

                $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, username, email, profile_photo, password, role, verify_token, is_verified) 
                                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)");
                if ($stmt->execute([$first_name, $last_name, $username, $email, $profile_photo, $hashed_password, $role, $token])) {
                    
                    // Send verification email (SMTP settings come from environment variables)
                    $verifyLink = app_base_url() . "/admin/verify.php?verify_token=" . urlencode($token);

                    if (app_mail_enabled()) {
                        $html = "<h2>Welcome, " . htmlspecialchars($first_name) . "!</h2>
                                 <p>Please click the link below to verify your email:</p>
                                 <a href='$verifyLink'>$verifyLink</a>";
                        $sent = app_send_mail($email, $first_name, 'Verify Your Email - MoodBuddy', $html);
                        if ($sent === true) {
                            $success = "✅ Registration successful! Please check your email to verify.";
                        } else {
                            error_log('Mail error: ' . $sent);
                            $error = "❌ Account created, but the verification email could not be sent. Please contact support.";
                        }
                    } else {
                        // No SMTP configured (e.g. fresh deployment): activate the account directly
                        $pdo->prepare("UPDATE users SET is_verified = 1, verify_token = NULL WHERE email = ?")->execute([$email]);
                        $success = "✅ Registration successful! You can now log in.";
                    }

                } else {
                    $error = '❌ Registration failed! Please try again.';
                }
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>User Register | MoodBuddy</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
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

        .register-container {
            max-width: 600px;
            width: 100%;
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(6, 182, 212, 0.1);
            position: relative;
            overflow: hidden;
        }

        .register-container::before {
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

        .alert {
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(239, 68, 68, 0.2) 100%);
            border: 1px solid #ef4444;
            color: #ef4444;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(46, 213, 115, 0.1) 0%, rgba(46, 213, 115, 0.2) 100%);
            border: 1px solid #2ed573;
            color: #2ed573;
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

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }

        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
            padding: 0 10px;
        }

        .btn-register {
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

        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px var(--glow-blue);
            filter: brightness(1.1);
        }

        .btn-register:hover::before {
            left: 100%;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: var(--text-secondary);
        }

        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .login-link a:hover {
            color: var(--secondary-color);
            text-shadow: 0 0 10px var(--glow-pink);
        }

        @media (max-width: 768px) {
            .register-container {
                padding: 30px 20px;
            }
            
            .logo-text {
                font-size: 2rem;
            }
            
            h3 {
                font-size: 1.5rem;
            }
            
            .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
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

    <div class="register-container">
        <div class="logo">
            <div class="logo-text">MoodBuddy</div>
        </div>
        
        <h3>Create Your Account</h3>
        
        <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control" name="first_name" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="last_name" required>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Profile Photo (optional)</label>
                <input type="file" class="form-control" name="profile_photo" accept="image/*">
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="confirm_password" required>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn-register">
                <i class="fas fa-user-plus"></i> Register
            </button>
            
            <div class="login-link">
                Already have an account? <a href="index.php">Login here</a>
            </div>
        </form>
    </div>
</body>
</html>