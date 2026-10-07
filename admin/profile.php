<?php
session_start();
require_once __DIR__ . '/db.php';

// Protect page
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

// Get user info from DB
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $email      = trim($_POST['email']);
    $username   = trim($_POST['username']);
    $profile_photo = $user['profile_photo']; // current

    // File upload
    if (!empty($_FILES['profile_photo']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = time() . "_" . basename($_FILES['profile_photo']['name']);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $targetFilePath)) {
            $profile_photo = $fileName;
        }
    }

    // Update DB
    $stmt = $pdo->prepare("UPDATE users 
                           SET first_name = ?, last_name = ?, email = ?, username = ?, profile_photo = ? 
                           WHERE id = ?");
    $stmt->execute([$first_name, $last_name, $email, $username, $profile_photo, $_SESSION['id']]);

    // Update session values
    $_SESSION['username'] = $username;
    $_SESSION['profile_photo'] = $profile_photo;

    header("Location: profile.php?success=1");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - MoodBuddy</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        .logo {
            font-family: 'Orbitron', monospace;
            font-size: 2.5rem;
            font-weight: 900;
            background: var(--metallic-shine);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-align: center;
            margin-bottom: 2rem;
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .profile-container {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
        }

        .profile-header {
            background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(236, 72, 153, 0.1) 100%);
            padding: 40px 20px;
            text-align: center;
            border-radius: 20px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(6, 182, 212, 0.1);
            backdrop-filter: blur(10px);
        }

        .profile-header::before {
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

        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid transparent;
            background: linear-gradient(white, white) padding-box, var(--primary-gradient) border-box;
            margin: 0 auto 20px;
            box-shadow: 0 8px 25px var(--glow-pink);
        }

        .profile-name {
            font-family: 'Orbitron', monospace;
            font-size: 2rem;
            margin-bottom: 5px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .profile-username {
            color: var(--text-secondary);
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .profile-email {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        .profile-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(6, 182, 212, 0.1);
            position: relative;
            overflow: hidden;
            margin-bottom: 30px;
        }

        .profile-card::before {
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

        .profile-card h3 {
            font-family: 'Orbitron', monospace;
            font-size: 1.8rem;
            margin-bottom: 20px;
            text-align: center;
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

        .form-input {
            width: 100%;
            padding: 15px;
            border: 2px solid var(--border-light);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.9);
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 15px var(--glow-blue);
            background: white;
        }

        .btn-update {
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

        .btn-update::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }

        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px var(--glow-blue);
            filter: brightness(1.1);
        }

        .btn-update:hover::before {
            left: 100%;
        }

        .btn-back {
            background: transparent;
            color: var(--text-secondary);
            border: 2px solid var(--border-light);
            padding: 15px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
        }

        .btn-back:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            box-shadow: 0 0 15px var(--glow-blue);
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(46, 213, 115, 0.1) 0%, rgba(46, 213, 115, 0.2) 100%);
            border: 1px solid #2ed573;
            color: #2ed573;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .logo {
                font-size: 2rem;
            }
            
            .profile-name {
                font-size: 1.5rem;
            }
            
            .profile-card {
                padding: 20px;
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

    <div class="profile-container">
        <div class="logo">MoodBuddy</div>

        <!-- Profile Header -->
        <div class="profile-header">
            <img src="uploads/<?php echo htmlspecialchars($user['profile_photo'] ?: 'default.jpg'); ?>" alt="Profile Photo" class="profile-img">
            <h2 class="profile-name"><?= htmlspecialchars($user['first_name'] . " " . $user['last_name']) ?></h2>
            <p class="profile-username">@<?= htmlspecialchars($user['username']) ?></p>
            <p class="profile-email"><?= htmlspecialchars($user['email']) ?></p>
        </div>

        <!-- Profile Update Form -->
        <div class="profile-card">
            <h3>Update Your Profile</h3>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert-success">
                    <i class="fas fa-check-circle"></i> Profile updated successfully!
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="form-label">First Name</label>
                    <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Profile Image</label>
                    <input type="file" name="profile_photo" class="form-input">
                </div>

                <button type="submit" class="btn-update">
                    <i class="fas fa-sync-alt"></i> Update Profile
                </button>
            </form>
        </div>

        <a href="../mood_result.php" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</body>
</html>