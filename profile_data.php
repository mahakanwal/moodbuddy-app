<?php
session_start();
require_once __DIR__ . '/includes/db.php';

// Protect page
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Get user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found");
}

$name     = $user['first_name'] . ' ' . $user['last_name'];
$username = $user['username'];
$email    = $user['email'];
$joinDate = date('F j, Y', strtotime($user['created_at']));
$status   = 'Premium Member';
$bio      = $user['bio'] ?? 'No bio provided yet.';
$image    = 'uploads/' . ($user['profile_photo'] ?? 'default.png');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MoodBuddy - Profile</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;900&family=Exo+2:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    *{margin:0;padding:0;box-sizing:border-box;}
    :root {
      --primary:#06b6d4; --secondary:#ec4899;
      --gradient:linear-gradient(135deg,#06b6d4,#ec4899);
      --card-bg:rgba(255,255,255,0.95);
      --text-dark:#1f2937; --text-muted:#6b7280;
    }
    body {
      font-family:'Exo 2',sans-serif;
      background:#f1f5f9; color:var(--text-dark);
      min-height:100vh; display:flex; flex-direction:column;
      align-items:center; justify-content:center; padding:20px;
    }
    /* Background animation */
    .bg-animation{position:fixed;top:0;left:0;width:100%;height:100%;z-index:-1;
      background:linear-gradient(45deg,#f8fafc 0%,#e0f2fe 25%,#fce7f3 50%,#e0f2fe 75%,#f8fafc 100%);
      background-size:400% 400%;animation:gradientShift 15s ease infinite;}
    @keyframes gradientShift{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
    .floating-object{position:absolute;opacity:.3;animation:float 20s infinite linear;}
    @keyframes float{0%{transform:translateY(0) rotate(0)}25%{transform:translateY(-20px) rotate(90deg)}50%{transform:translateY(0) rotate(180deg)}75%{transform:translateY(20px) rotate(270deg)}100%{transform:translateY(0) rotate(360deg)}}
    /* Logo */
    .logo{font-family:'Orbitron',monospace;font-size:3rem;font-weight:900;
      background:var(--gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent;
      background-clip:text;animation:shimmer 3s ease-in-out infinite;margin-bottom:1.5rem;text-align:center;}
    @keyframes shimmer{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
    /* Container */
    .profile-container{background:var(--card-bg);backdrop-filter:blur(20px);
      border-radius:20px;padding:3rem;box-shadow:0 15px 35px rgba(6,182,212,0.1);
      max-width:800px;width:100%;text-align:center;position:relative;}
    .profile-container::before{content:'';position:absolute;top:0;left:0;right:0;bottom:0;border-radius:20px;
      padding:2px;background:var(--gradient);mask:linear-gradient(#fff 0 0) content-box,linear-gradient(#fff 0 0);
      -webkit-mask-composite:destination-out;mask-composite:exclude;z-index:-1;}
    /* Profile image */
    .profile-image{width:120px;height:120px;border-radius:50%;object-fit:cover;margin:0 auto 1.5rem;
      border:3px solid transparent;background:linear-gradient(white,white) padding-box,var(--gradient) border-box;}
    /* Grid info */
    .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:20px;text-align:left;}
    .info-card{background:#f9fafb;padding:20px;border-radius:12px;
      box-shadow:0 4px 8px rgba(0,0,0,.04);transition:.3s;}
    .info-card:hover{transform:translateY(-3px);box-shadow:0 6px 14px rgba(0,0,0,.08);}
    .info-card i{font-size:1.2rem;color:var(--primary);margin-right:8px;}
    .info-title{font-weight:600;color:var(--text-muted);margin-bottom:6px;display:flex;align-items:center;}
    .info-value{font-size:1.05rem;color:var(--text-dark);}
    .bio-box{grid-column:1/-1;}
    /* Back Button */
    .back-btn{display:inline-block;margin:25px auto 10px;padding:12px 24px;border:none;border-radius:8px;
      background:var(--gradient);color:#fff;font-weight:600;cursor:pointer;text-decoration:none;
      transition:.3s;box-shadow:0 4px 10px rgba(0,0,0,.15);}
    .back-btn:hover{opacity:.9;transform:translateY(-2px);}
  </style>
</head>
<body>
  <div class="bg-animation">
    <div class="floating-object" style="top:10%;left:10%"><i class="fas fa-cube" style="font-size:2.5rem;color:var(--primary)"></i></div>
    <div class="floating-object" style="top:60%;left:80%"><i class="fas fa-gem" style="font-size:2rem;color:var(--secondary)"></i></div>
    <div class="floating-object" style="top:30%;left:70%"><i class="fas fa-star" style="font-size:1.8rem;color:var(--primary)"></i></div>
    <div class="floating-object" style="top:80%;left:20%"><i class="fas fa-diamond" style="font-size:3rem;color:var(--secondary)"></i></div>
  </div>

  <!-- Logo -->
  <div class="logo">MoodBuddy</div>

  <!-- Profile Card -->
  <div class="profile-container">
    <img src="admin/<?= htmlspecialchars($image) ?>" class="profile-image" alt="Profile">
    <h1><?= htmlspecialchars($name) ?></h1>
    <p style="color:var(--text-muted);margin-bottom:2rem;">@<?= htmlspecialchars($username) ?></p>

    <div class="grid">
      <div class="info-card">
        <div class="info-title"><i class="fas fa-envelope"></i>Email</div>
        <div class="info-value"><?= htmlspecialchars($email) ?></div>
      </div>
      <div class="info-card">
        <div class="info-title"><i class="fas fa-calendar-alt"></i>Member Since</div>
        <div class="info-value"><?= htmlspecialchars($joinDate) ?></div>
      </div>
      <div class="info-card">
        <div class="info-title"><i class="fas fa-star"></i>Status</div>
        <div class="info-value"><?= htmlspecialchars($status) ?></div>
      </div>
      <div class="info-card bio-box">
        <div class="info-title"><i class="fas fa-info-circle"></i>Bio</div>
        <div class="info-value"><?= htmlspecialchars($bio) ?></div>
      </div>
    </div>

    <!-- Back button -->
    <a href="mood_result.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
  </div>
</body>
</html>
