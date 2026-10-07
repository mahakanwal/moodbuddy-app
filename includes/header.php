<?php
session_start();
require_once __DIR__ . '/db.php';

// check agar session me mood na ho to homepage bhej do
// (pages like webcam.php set $allowNoMood = true because they are where the mood is detected)
if (!isset($_SESSION['mood']) && empty($allowNoMood)) {
    header("Location: index.php");
    exit();
}

$mood   = strtolower($_SESSION['mood'] ?? 'calm');
$gender = $_SESSION['gender'] ?? '';

function fetchData($pdo, $table, $mood) {
    $stmt = $pdo->prepare("SELECT * FROM $table WHERE mood = ?");
    $stmt->execute([$mood]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$products = fetchData($pdo, 'products', $mood);
$songs    = fetchData($pdo, 'songs', $mood);
$quotes   = fetchData($pdo, 'quotes', $mood);
$memes    = fetchData($pdo, 'memes', $mood);
$games    = fetchData($pdo, 'games', $mood);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoodBuddy 2027 - <?= ucfirst($mood) ?> Mood Recommendations</title>
    <meta name="description" content="Futuristic personalized recommendations based on your mood">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Library -->
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    
    <link rel="stylesheet" href="assets/css/site.css">
</head>

<body>
<div class="main-cursor"></div>
    <!-- Updated floating objects with pink/blue colors -->
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

    <!-- Navbar -->
     <!-- ===== HEADER START ===== -->
<header style="background:#fff; box-shadow:0 2px 8px rgba(0,0,0,0.1); padding:10px 30px; position:sticky; top:0; z-index:999;">
  <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap;">
    

    <!-- ☎️ Center: Contact Info -->
    <div style="text-align:center; min-width:250px; display:flex; justify-content:center; align-items:center; gap:25px; flex-wrap:wrap;">

  <!-- 📱 Phone -->
  <div style="display:flex; align-items:center; gap:8px;">
    <i class="fa-solid fa-phone" style="color:#ff4da6;"></i>
    <span style="font-size:15px; color:#333; font-weight:500;">+92 319 4743468</span>
  </div>

  <!-- ✉️ Email -->
  <div style="display:flex; align-items:center; gap:8px;">
    <i class="fa-solid fa-envelope" style="color:#ff4da6;"></i>
    <a href="mailto:info@moodbuddy.com" style="font-size:15px; color:#333; font-weight:500; text-decoration:none;">
      info@moodbuddy.com
    </a>
  </div>

</div>
<?php
$cartCount = 0;
$wishlistCount = 0;
if (isset($_SESSION['cart'])) {
    $cartCount = count($_SESSION['cart']);
}
if (isset($_SESSION['wishlist'])) {
    $wishlistCount = count($_SESSION['wishlist']);
}
?>
    <!-- 🛒 Right: Icons -->
    <div style="flex:1; display:flex; justify-content:flex-end; align-items:center; gap:20px; min-width:150px;">
    <div class="action-buttons">
            <a href="cart.php" class="action-btn cart-btn">
                <i class="fas fa-shopping-cart"></i>
                <span class="badge"><?= $cartCount ?></span>
            </a>
            <a href="wishlist.php" class="action-btn wishlist-btn">
                <i class="fas fa-heart"></i>
                <span class="badge"><?= $wishlistCount ?></span>
            </a>
        </div>
    </div>

  </div>
</header>
<!-- ===== HEADER END ===== -->

    <header class="futuristic-header">
    <div class="header-container">
        <a href="mood_result.php"><div class="logo">MoodBuddy</div></a>
        <nav>
            <ul class="nav-menu">
                <li><a href="about.php" class="nav-link"> About</a></li>
                <li><a href="shop.php" class="nav-link"></i> Shop</a></li>
                <li><a href="music.php" class="nav-link"> Music</a></li>
                <li><a href="memes.php" class="nav-link"> Memes</a></li>
                <li><a href="games.php" class="nav-link"> Games</a></li>
                <li><a href="contact.php" class="nav-link">Contact</a></li>
            </ul>
        </nav>

        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
            <!-- Dropdown -->
            <div class="tooltip-container">
                <button class="futuristic-btn">
                    <span class="text">
                        <?php 
  echo isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : 'User'; 
?>

                    </span>
                    <i class="fas fa-chevron-down"></i>
                </button>

                <div class="tooltip-content">
                    <!-- Profile Icon -->
<a href="profile_data.php" class="social-icon" data-tooltip="View Profile" id="profileBtn">
    <i class="fas fa-user"></i>
</a>


                    <!-- Update Profile -->
                    <a href="admin/profile.php" class="social-icon" data-tooltip="Update Profile">
                        <i class="fas fa-user-edit"></i>
                    </a>

                    <!-- Admin Dashboard -->
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="admin/dashboard.php" class="social-icon" data-tooltip="Dashboard">
                            <i class="fas fa-tachometer-alt"></i>
                        </a>
                    <?php endif; ?>

                    <!-- Logout -->
                    <a href="admin/logout.php" class="social-icon" data-tooltip="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>

            <!-- Profile Section -->
            <div class="profile-wrapper">
                <div class="profile-photo">
                    <?php if (!empty($_SESSION['profile_photo'])): ?>
                        <img src="admin/uploads/<?php echo htmlspecialchars($_SESSION['profile_photo']); ?>" alt="Profile">
                    <?php else: ?>
                        <img src="admin/uploads/default.jpg" alt="Default Profile">
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <!-- Sirf Login button (jab login na ho) -->
            <a href="admin/index.php" class="futuristic-btn">
                <span class="text">Login</span>
                <i class="fas fa-sign-in-alt"></i>
            </a>
        <?php endif; ?>

        <!-- Mobile Menu Button -->
        <button class="mobile-menu-btn" id="mobileMenuBtn">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    
    <!-- Mobile Navigation -->
    <div class="mobile-nav" id="mobileNav">
        <div class="mobile-nav-grid">
            <a href="about.php" class="mobile-nav-link"><i class="fas fa-info-circle"></i> About</a>
            <a href="shop.php" class="mobile-nav-link"><i class="fas fa-shopping-bag"></i> Shop</a>
            <a href="music.php" class="mobile-nav-link"><i class="fas fa-music"></i> Music</a>
            <a href="memes.php" class="mobile-nav-link"><i class="fas fa-smile"></i> Memes</a>
            <a href="games.php" class="mobile-nav-link"><i class="fas fa-gamepad"></i> Games</a>
            <a href="contact.php" class="mobile-nav-link"><i class="fas fa-phone"></i> Contact</a>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="admin/dashboard.php" class="mobile-nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<script src="assets/js/app.js" defer></script>
<div class="container-fluid">