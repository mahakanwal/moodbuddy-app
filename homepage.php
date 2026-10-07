<?php
session_start();
require_once __DIR__ . '/includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MoodBuddy - Content That Matches Your Mood</title>
    <meta name="description" content="MoodBuddy detects your mood with your webcam, an AI chat or a quick pick, then recommends songs, products, quotes, memes and games that fit.">
    <link rel="shortcut icon" href="assets/img/favicon.svg">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="assets/css/magnific-popup.css">
    <link rel="stylesheet" href="assets/css/meanmenu.css">
    <link rel="stylesheet" href="assets/css/swiper-bundle.min.css">
    <link rel="stylesheet" href="assets/css/nice-select.css">
    <link rel="stylesheet" href="assets/css/main.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- MoodBuddy CSS -->
    <link rel="stylesheet" href="assets/css/homepage.css">
    <link rel="stylesheet" href="assets/css/hero.css">
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

        <!-- GT Back To Top Start -->
        <button id="gt-back-top" class="gt-back-to-top show">
            <i class="fa-regular fa-arrow-up"></i>
        </button>

        <!-- Navbar -->
    <header class="futuristic-header">
    <div class="header-container">
        <a href="mood_result.php"><div class="logo">MoodBuddy</div></a>
        <nav>
            <ul class="nav-menu" style="text-transform: capitalize; opacity: 0;">
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
                <button class="hero-btn">
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
            <a href="admin/index.php" class="hero-btn">
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
     

<div id="smooth-wrapper">
            <div id="smooth-content">
<?php
// ---- Hero data: real numbers from the database (falls back gracefully) ----
$heroStats = ['products' => 0, 'songs' => 0];
try {
    $heroStats['products'] = (int) $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $heroStats['songs']    = (int) $pdo->query("SELECT COUNT(*) FROM songs")->fetchColumn();
} catch (Throwable $e) { /* keep zeros */ }
$heroGames = count(glob(__DIR__ . '/games/*.php'));

// One real song + product for the preview card
$heroSong = $heroProduct = null;
try {
    $heroSong    = $pdo->query("SELECT title, singer FROM songs ORDER BY RAND() LIMIT 1")->fetch(PDO::FETCH_ASSOC) ?: null;
    $heroProduct = $pdo->query("SELECT title, price FROM products ORDER BY RAND() LIMIT 1")->fetch(PDO::FETCH_ASSOC) ?: null;
} catch (Throwable $e) { /* static fallback below */ }
?>
        <!-- Hero Section Start -->
        <section class="mb-hero" aria-labelledby="heroTitle">
            <div class="mb-hero__inner">
                <div class="row align-items-center g-5">

                    <!-- Hero text -->
                    <div class="col-lg-7">
                        <div class="mb-hero__content">
                            <span class="mb-hero__eyebrow">
                                <span class="mb-hero__eyebrow-dot" aria-hidden="true"></span>
                                AI-powered mood recommendations
                            </span>

                            <h1 id="heroTitle" class="mb-hero__title">
                                Discover content that matches <span class="mb-gradient-text">your mood</span>
                            </h1>

                            <p class="mb-hero__lead">
                                Tell MoodBuddy how you feel, or let it read your expression. You get songs,
                                products, quotes, memes and games picked for that exact moment.
                            </p>

                            <p class="mb-hero__label">Choose how to detect your mood</p>
                            <div class="mb-hero__actions">
                                <a href="webcam.php" class="mb-btn mb-btn--primary">
                                    <i class="fa-solid fa-camera" aria-hidden="true"></i> Detect via Webcam
                                </a>
                                <a href="chat.php" class="mb-btn mb-btn--outline">
                                    <i class="fa-solid fa-comment-dots" aria-hidden="true"></i> Chat with AI
                                </a>
                                <a href="home.php" class="mb-btn mb-btn--ghost">
                                    <i class="fa-solid fa-sliders" aria-hidden="true"></i> Select Your Mood
                                </a>
                            </div>

                            <ul class="mb-hero__stats">
                                <li>
                                    <strong><?= number_format($heroStats['products']) ?></strong>
                                    <span>Products</span>
                                </li>
                                <li>
                                    <strong><?= number_format($heroStats['songs']) ?></strong>
                                    <span>Songs</span>
                                </li>
                                <li>
                                    <strong><?= $heroGames ?></strong>
                                    <span>Games</span>
                                </li>
                                <li>
                                    <strong>6</strong>
                                    <span>Moods</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Hero visual: mood detection preview -->
                    <div class="col-lg-5">
                        <div class="mb-preview" aria-label="Example of a MoodBuddy mood result">
                            <div class="mb-preview__head">
                                <div>
                                    <p class="mb-preview__kicker">Mood check</p>
                                    <h2 class="mb-preview__title">Your result</h2>
                                </div>
                                <span class="mb-preview__badge"><span aria-hidden="true"></span> Live preview</span>
                            </div>

                            <div class="mb-preview__mood">
                                <div class="mb-preview__ring" aria-hidden="true">
                                    <i class="fa-solid fa-face-smile"></i>
                                </div>
                                <div class="mb-preview__mood-text">
                                    <span class="mb-preview__mood-label">Detected mood</span>
                                    <strong class="mb-preview__mood-name" id="heroMoodName">Calm</strong>
                                    <div class="mb-preview__meter" role="presentation">
                                        <span id="heroMoodBar" style="width: 86%"></span>
                                    </div>
                                    <span class="mb-preview__confidence"><span id="heroMoodScore">86</span>% confidence</span>
                                </div>
                            </div>

                            <div class="mb-preview__chips" id="heroMoodChips">
                                <span data-mood="Happy" data-score="92">Happy</span>
                                <span data-mood="Calm" data-score="86" class="is-active">Calm</span>
                                <span data-mood="Excited" data-score="78">Excited</span>
                                <span data-mood="Sad" data-score="81">Sad</span>
                                <span data-mood="Anxious" data-score="74">Anxious</span>
                                <span data-mood="Angry" data-score="88">Angry</span>
                            </div>

                            <p class="mb-preview__section">Recommended for you</p>
                            <ul class="mb-preview__list">
                                <li>
                                    <span class="mb-preview__icon mb-preview__icon--blue"><i class="fa-solid fa-music" aria-hidden="true"></i></span>
                                    <div>
                                        <strong><?= htmlspecialchars($heroSong['title'] ?? 'Jo Tum Mere Ho') ?></strong>
                                        <span><?= htmlspecialchars($heroSong['singer'] ?? 'Anuv Jain') ?></span>
                                    </div>
                                    <i class="fa-solid fa-headphones mb-preview__action" aria-hidden="true"></i>
                                </li>
                                <li>
                                    <span class="mb-preview__icon mb-preview__icon--pink"><i class="fa-solid fa-bag-shopping" aria-hidden="true"></i></span>
                                    <div>
                                        <strong><?= htmlspecialchars($heroProduct['title'] ?? 'Indoor Plant') ?></strong>
                                        <span>$<?= htmlspecialchars(number_format((float)($heroProduct['price'] ?? 22.99), 2)) ?></span>
                                    </div>
                                    <i class="fa-solid fa-arrow-right mb-preview__action" aria-hidden="true"></i>
                                </li>
                                <li>
                                    <span class="mb-preview__icon mb-preview__icon--violet"><i class="fa-solid fa-gamepad" aria-hidden="true"></i></span>
                                    <div>
                                        <strong>Snake Game</strong>
                                        <span>Relaxing arcade</span>
                                    </div>
                                    <i class="fa-solid fa-arrow-right mb-preview__action" aria-hidden="true"></i>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <!-- Hero Section End -->

        <!-- About Section Start -->
        <section class="about-section section-padding fix">
            <div class="left-shape">
                <img src="assets/img/left-shape.png" alt="img">
            </div>
            <div class="container">
    <div class="about-wrapper">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="superhero-image">
                    <img src="assets/img/superhero.png" alt="img" class="wow fadeInRight" data-wow-delay=".3s">
                    <span class="eplay-text">
                        <img src="assets/img/eplay.png" alt="img">
                    </span>
                    <div class="radius-shape1 float-bob-y">
                        <img src="assets/img/radius-shape1.png" alt="img">
                    </div>
                    <div class="radius-shape2 float-bob-y">
                        <img src="assets/img/radius-shape2.png" alt="img">
                    </div>
                    <div class="game-controller-image float-bob-x">
                        <img src="assets/img/game-controller.png" alt="img">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-content">
                    <div class="section-title mb-0">
                        <h6 class="subtitle tz-sub-tilte tz-sub-anim text-uppercase tx-subTitle">
                            about moodBuddy
                        </h6>
                        <h2 class="tx-title sec_title tz-itm-title tz-itm-anim">
                            MoodBuddy Brings You <span>Mood-Based Fun</span> Anytime, Anywhere!
                        </h2>
                    </div>
                    <p class="about-text wow fadeInUp" data-wow-delay=".5s">
                        MoodBuddy is your personal vibe partner — giving you games, music, memes, and quotes
                        that match exactly how you feel. Whether you’re happy, sad, excited, or calm, 
                        MoodBuddy turns your emotions into an amazing digital experience.
                    </p>
                    <div class="about-btn">
                        <a href="about.html" class="theme-btn wow fadeInUp" data-wow-delay=".3s">Know More About Us</a>
                        <a href="game.html" class="view-btn wow fadeInUp" data-wow-delay=".5s">
                            <span class="active">V</span>
                            <span class="active">i</span>
                            <span class="active">e</span>
                            <span class="active">w</span>
                            <span class="space"></span>
                            <span>A</span>
                            <span>l</span>
                            <span>l</span>
                            <span class="space"></span>
                            <span>G</span>
                            <span>a</span>
                            <span>m</span>
                            <span>e</span>
                            <span>s</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="middle-shape float-bob-y">  
            <img src="assets/img/middle-shape.png" alt="img">
        </div>
    </div>
</div>

            <div class="game-wrapper section-padding pb-0">
    <div class="container">
        <div class="row g-4">
            <div class="col-xl-8">
                <div class="section-title">
                    <h6 class="subtitle tz-sub-tilte tz-sub-anim text-uppercase tx-subTitle">
                        our mood-based games
                    </h6>
                    <h2 class="tx-title sec_title tz-itm-title tz-itm-anim">
                        Fun Games That Match <span>Your Mood</span> – Ready to Play?
                    </h2>
                </div>
                <div class="game-slider-wrapper">
                    <div class="swiper game-slider">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="game-image-items">
                                    <img src="assets/img/game-01.jpg" alt="img">
                                    <ul>
                                        <li>
                                            <img src="assets/img/icon2.svg" alt="">
                                                happy mode
                                        </li>
                                        <li>
                                            <img src="assets/img/icon1.svg" alt="">
                                            quick fun
                                        </li>
                                    </ul>
                                    <div class="game-content">
                                        <h3>
                                            <a href="games/flappy.php">
                                                Flappy Bird – Test Your Reflexes
                                            </a>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="game-image-items">
                                    <img src="assets/img/game-02.jpg" alt="img">
                                    <ul>
                                        <li>
                                            <img src="assets/img/icon2.svg" alt="">
                                                chill mode
                                        </li>
                                        <li>
                                            <img src="assets/img/icon1.svg" alt="">
                                            relaxing
                                        </li>
                                    </ul>
                                    <div class="game-content">
                                        <h3>
                                            <a href="games/snake.php">
                                                Puzzle Quest – Calm Your Mind
                                            </a>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="game-image-items">
                                    <img src="assets/img/game-3.jpg" alt="img">
                                    <ul>
                                        <li>
                                            <img src="assets/img/icon2.svg" alt="">
                                                adventure
                                        </li>
                                        <li>
                                            <img src="assets/img/icon1.svg" alt="">
                                            challenge
                                        </li>
                                    </ul>
                                    <div class="game-content">
                                        <h3>
                                            <a href="games/snake.php">
                                                Snake Game – Old School Fun
                                            </a>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="game-arrow-button">
                    <div class="array-button d-flex align-items-center wow fadeInUp" data-wow-delay=".3s">
                        <button class="array-prev">
                            <!-- same svg left -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                <g clip-path="url(#clip0_0_434)">
                                    <path d="M1.16006 18L14.762 18C15.4019 18 15.9222 17.4797 15.9222 16.8398C15.9222 16.2 15.4019 15.6797 14.762 15.6797L3.96553 15.6797L17.6589 1.98281C18.1124 1.5293 18.1124 0.794531 17.6589 0.341017C17.2054 -0.112499 16.4706 -0.112499 16.0171 0.341017L2.32373 14.0379L2.32373 3.24141C2.32373 2.60156 1.80342 2.08125 1.16357 2.08125C0.52373 2.08125 0.00341662 2.60156 0.00341668 3.24141L0.00341787 16.8398C-9.73203e-05 17.4797 0.520214 18 1.16006 18Z" fill="#0B0E13"></path>
                                </g>
                                <defs>
                                    <clipPath id="clip0_0_434">
                                        <rect width="18" height="18" fill="white" transform="translate(18 18) rotate(180)"></rect>
                                    </clipPath>
                                </defs>
                            </svg>
                        </button>
                        <button class="array-next">
                            <!-- same svg right -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                <g clip-path="url(#clip0_0_427)">
                                    <path d="M16.8399 0H3.23799C2.59814 0 2.07783 0.520312 2.07783 1.16016C2.07783 1.8 2.59814 2.32031 3.23799 2.32031H14.0345L0.341113 16.0172C-0.112402 16.4707 -0.112402 17.2055 0.341113 17.659C0.794629 18.1125 1.52939 18.1125 1.98291 17.659L15.6763 3.96211V14.7586C15.6763 15.3984 16.1966 15.9187 16.8364 15.9187C17.4763 15.9187 17.9966 15.3984 17.9966 14.7586V1.16016C18.0001 0.520312 17.4798 0 16.8399 0Z" fill="#0B0E13"></path>
                                </g>
                                <defs>
                                    <clipPath id="clip0_0_427">
                                        <rect width="18" height="18" fill="white"></rect>
                                    </clipPath>
                                </defs>
                            </svg>
                        </button>
                    </div>
                    <a href="game.html" class="view-btn wow fadeInUp" data-wow-delay=".5s">
                        <span class="active">V</span>
                        <span class="active">i</span>
                        <span class="active">e</span>
                        <span class="active">w</span>

                        <span class="space"></span>

                        <span>A</span>
                        <span>l</span>
                        <span>l</span>

                        <span class="space"></span>

                        <span>M</span>
                        <span>o</span>
                        <span>o</span>
                        <span>d</span>
                        <span>-</span>
                        <span>G</span>
                        <span>a</span>
                        <span>m</span>
                        <span>e</span>
                        <span>s</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="top-image wow bounceInRight" data-wow-delay="600ms" data-wow-duration="1000ms">
        <img src="assets/img/top-image.jpg" alt="img">
        <div class="radius-shape float-bob-y">
            <img src="assets/img/radius-shape.png" alt="img">
        </div>
    </div>
</div>

        </section>

        <!-- Game Section Start -->
        <section class="game-feature-section fix section-padding">
            <div class="container">
                <div class="section-title-2 text-center">
                    <h6 class="subtitle tz-sub-tilte tz-sub-anim  text-uppercase tx-subTitle"  style="color: white;">what we do</h6>
                    <h2 class="tx-title sec_title  tz-itm-title tz-itm-anim">
                    More Than Just <br> <span>Mood Recommendations</span>
                    </h2>
                </div>
                <div class="row">
                    <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".2s">
                        <div class="game-feature-box-items">
                            <div class="shape-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="27" viewBox="0 0 30 27" fill="none">
                                    <path d="M1 27V6C1 3.23858 3.23858 1 6 1H30" stroke="white" stroke-opacity="0.2"></path>
                                </svg>
                            </div>
                            <div class="shape-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="27" viewBox="0 0 30 27" fill="none">
                                    <path d="M29 -9.53674e-07L29 21C29 23.7614 26.7614 26 24 26L-5.96046e-08 26" stroke="white" stroke-opacity="0.2"></path>
                                </svg>
                            </div>
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="31" height="31" viewBox="0 0 31 31" fill="none">
                                    <g clip-path="url(#clip0_106_311)">
                                        <path d="M21.7439 0.626953H9.25561C8.0519 0.626953 7.07178 1.60708 7.07178 2.81079V28.1884C7.07178 29.3926 8.0519 30.3727 9.25561 30.3727H21.7434C22.9476 30.3727 23.9277 29.3926 23.9277 28.1889V2.81079C23.9277 1.60708 22.9476 0.626953 21.7439 0.626953ZM8.0633 4.59305H22.9362V23.432H8.0633V4.59305ZM9.25561 1.61848H21.7434C22.4013 1.61848 22.9362 2.15341 22.9362 2.81079V3.60153H8.0633V2.81079C8.0633 2.15341 8.59823 1.61848 9.25561 1.61848ZM21.7439 29.3812H9.25561C8.59823 29.3812 8.0633 28.8463 8.0633 28.1889V24.4236H22.9362V28.1889C22.9362 28.8463 22.4013 29.3812 21.7439 29.3812Z" fill="#CBFE1C"></path>
                                        <path d="M15.4999 24.9189C14.4062 24.9189 13.5168 25.8083 13.5168 26.902C13.5168 27.9956 14.4062 28.885 15.4999 28.885C16.5935 28.885 17.4829 27.9956 17.4829 26.902C17.4829 25.8083 16.5935 24.9189 15.4999 24.9189ZM15.4999 27.8935C14.9531 27.8935 14.5084 27.4488 14.5084 26.902C14.5084 26.3552 14.9531 25.9105 15.4999 25.9105C16.0467 25.9105 16.4914 26.3552 16.4914 26.902C16.4914 27.4488 16.0467 27.8935 15.4999 27.8935Z" fill="#CBFE1C"></path>
                                        <path d="M13.5168 3.10578H15.4998C15.774 3.10578 15.9956 2.88418 15.9956 2.61002C15.9956 2.33586 15.774 2.11426 15.4998 2.11426H13.5168C13.2426 2.11426 13.021 2.33586 13.021 2.61002C13.021 2.88418 13.2426 3.10578 13.5168 3.10578Z" fill="#CBFE1C"></path>
                                        <path d="M16.9872 3.10578H17.483C17.7571 3.10578 17.9787 2.88418 17.9787 2.61002C17.9787 2.33586 17.7571 2.11426 17.483 2.11426H16.9872C16.7131 2.11426 16.4915 2.33586 16.4915 2.61002C16.4915 2.88418 16.7131 3.10578 16.9872 3.10578Z" fill="#CBFE1C"></path>
                                        <path d="M28.6931 2.89245C28.4992 2.69861 28.1859 2.69861 27.9921 2.89245C27.7982 3.0863 27.7982 3.39962 27.9921 3.59346C29.8442 5.44563 29.8442 8.45937 27.9921 10.312C27.7982 10.5059 27.7982 10.8192 27.9921 11.013C28.0887 11.1097 28.2156 11.1583 28.3426 11.1583C28.4695 11.1583 28.5964 11.1097 28.6931 11.013C30.9319 8.77468 30.9319 5.13132 28.6931 2.89245Z" fill="#CBFE1C"></path>
                                        <path d="M26.6054 4.02038C26.4116 3.82654 26.0983 3.82654 25.9044 4.02038C25.7106 4.21423 25.7106 4.52755 25.9044 4.72139C26.5177 5.33465 26.8553 6.15167 26.8553 7.02123C26.8553 7.8908 26.5177 8.70732 25.9044 9.32058C25.7106 9.51442 25.7106 9.82775 25.9044 10.0216C26.0011 10.1183 26.128 10.1668 26.2549 10.1668C26.3818 10.1668 26.5088 10.1183 26.6054 10.0216C27.4061 9.22143 27.8468 8.15554 27.8468 7.02123C27.8468 5.88693 27.4066 4.82054 26.6054 4.02038Z" fill="#CBFE1C"></path>
                                        <path d="M3.0076 3.59346C3.20144 3.39962 3.20144 3.0863 3.0076 2.89245C2.81375 2.69861 2.50043 2.69861 2.30659 2.89245C0.0677253 5.13132 0.0677253 8.77418 2.30659 11.013C2.40326 11.1097 2.53018 11.1583 2.65709 11.1583C2.78401 11.1583 2.91092 11.1097 3.0076 11.013C3.20144 10.8192 3.20144 10.5059 3.0076 10.312C1.15493 8.45987 1.15493 5.44613 3.0076 3.59346Z" fill="#CBFE1C"></path>
                                        <path d="M5.09523 3.88366C4.90139 3.68982 4.58807 3.68982 4.39422 3.88366C3.59357 4.68382 3.15283 5.74972 3.15283 6.88402C3.15283 8.01833 3.59357 9.08422 4.39422 9.88487C4.4909 9.98155 4.61781 10.0301 4.74473 10.0301C4.87164 10.0301 4.99856 9.98155 5.09523 9.88487C5.28907 9.69103 5.28907 9.37771 5.09523 9.18386C4.48197 8.57061 4.14436 7.75359 4.14436 6.88402C4.14436 6.01445 4.48148 5.19793 5.09523 4.58467C5.28858 4.39132 5.28858 4.07751 5.09523 3.88366Z" fill="#CBFE1C"></path>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_106_311">
                                        <rect width="29.7458" height="29.7458" fill="white" transform="translate(0.626953 0.626953)"></rect>
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>
                            <h4>
                               <a href="webcam.php">Mood Detects With Webcam</a>
                            </h4>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".4s">
                        <div class="game-feature-box-items">
                            <div class="shape-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="27" viewBox="0 0 30 27" fill="none">
                                    <path d="M1 27V6C1 3.23858 3.23858 1 6 1H30" stroke="white" stroke-opacity="0.2"></path>
                                </svg>
                            </div>
                            <div class="shape-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="27" viewBox="0 0 30 27" fill="none">
                                    <path d="M29 -9.53674e-07L29 21C29 23.7614 26.7614 26 24 26L-5.96046e-08 26" stroke="white" stroke-opacity="0.2"></path>
                                </svg>
                            </div>
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                    <g clip-path="url(#clip0_106_330)">
                                        <path d="M2.66253 12.7801C2.80406 12.7809 2.9401 12.7254 3.04059 12.6257L5.17054 10.4957C5.37934 10.2869 5.37934 9.94841 5.17054 9.73961C4.96174 9.53081 4.62321 9.53081 4.41441 9.73961L2.28446 11.8696C2.18366 11.9695 2.12695 12.1056 2.12695 12.2476C2.12695 12.3896 2.18366 12.5257 2.28446 12.6257C2.38495 12.7254 2.52099 12.7809 2.66253 12.7801Z" fill="#CBFE1C"></path>
                                        <path d="M2.66253 15.975C2.80406 15.9758 2.9401 15.9203 3.04059 15.8206L8.36545 10.4957C8.57425 10.2869 8.57425 9.94841 8.36545 9.73961C8.15665 9.53081 7.81812 9.53081 7.60932 9.73961L2.28446 15.0645C2.18366 15.1644 2.12695 15.3005 2.12695 15.4425C2.12695 15.5845 2.18366 15.7206 2.28446 15.8206C2.38495 15.9203 2.52099 15.9758 2.66253 15.975Z" fill="#CBFE1C"></path>
                                        <path d="M30.3517 0H16.5071C15.6248 0 14.9096 0.715206 14.9096 1.59746V7.4548H2.66243C1.19201 7.4548 0 8.64681 0 10.1172V23.9619C0 25.4323 1.19201 26.6243 2.66243 26.6243H8.51977V28.7542H6.92232C6.04006 28.7542 5.32486 29.4694 5.32486 30.3517C5.32486 31.2339 6.04006 31.9492 6.92232 31.9492H30.3517C31.2339 31.9492 31.9492 31.2339 31.9492 30.3517V1.59746C31.9492 0.715206 31.2339 0 30.3517 0ZM2.66243 8.51977H21.8319C22.7142 8.51977 23.4294 9.23498 23.4294 10.1172V22.8969H1.06497V10.1172C1.06497 9.23498 1.78018 8.51977 2.66243 8.51977ZM1.06497 23.9619H23.4294C23.4294 24.8441 22.7142 25.5593 21.8319 25.5593H2.66243C1.78018 25.5593 1.06497 24.8441 1.06497 23.9619ZM9.58475 26.6243H14.9096V28.7542H9.58475V26.6243ZM16.5071 30.8842H6.92232C6.62823 30.8842 6.38983 30.6458 6.38983 30.3517C6.38983 30.0576 6.62823 29.8192 6.92232 29.8192H17.572C17.8661 29.8192 18.1045 30.0576 18.1045 30.3517C18.1045 30.6458 17.8661 30.8842 17.572 30.8842H16.5071ZM30.8842 30.3517C30.8842 30.6458 30.6458 30.8842 30.3517 30.8842H19.0736C19.2462 30.396 19.1715 29.8544 18.8732 29.4312C18.5748 29.008 18.0898 28.7557 17.572 28.7542H15.9746V26.6243H21.8319C23.3023 26.6243 24.4943 25.4323 24.4943 23.9619V10.1172C24.4943 8.64681 23.3023 7.4548 21.8319 7.4548H15.9746V1.59746C15.9746 1.30337 16.213 1.06497 16.5071 1.06497H30.3517C30.6458 1.06497 30.8842 1.30337 30.8842 1.59746V30.3517Z" fill="#CBFE1C"></path>
                                        <path d="M28.7542 2.66211H18.1045C17.5164 2.66211 17.0396 3.13891 17.0396 3.72708V4.79205C17.0396 5.38022 17.5164 5.85702 18.1045 5.85702H28.7542C29.3424 5.85702 29.8192 5.38022 29.8192 4.79205V3.72708C29.8192 3.13891 29.3424 2.66211 28.7542 2.66211ZM18.1045 4.79205V3.72708H28.7542V4.79205H18.1045Z" fill="#CBFE1C"></path>
                                        <path d="M27.6893 10.65C28.5715 10.65 29.2867 9.93479 29.2867 9.05254C29.2867 8.17028 28.5715 7.45508 27.6893 7.45508C26.807 7.45508 26.0918 8.17028 26.0918 9.05254C26.0918 9.93479 26.807 10.65 27.6893 10.65ZM27.6893 8.52005C27.9833 8.52005 28.2217 8.75845 28.2217 9.05254C28.2217 9.34662 27.9833 9.58502 27.6893 9.58502C27.3952 9.58502 27.1568 9.34662 27.1568 9.05254C27.1568 8.75845 27.3952 8.52005 27.6893 8.52005Z" fill="#CBFE1C"></path>
                                        <path d="M29.2867 13.3123C29.2867 12.43 28.5715 11.7148 27.6893 11.7148C26.807 11.7148 26.0918 12.43 26.0918 13.3123C26.0918 14.1946 26.807 14.9098 27.6893 14.9098C28.5715 14.9098 29.2867 14.1946 29.2867 13.3123ZM27.6893 13.8448C27.3952 13.8448 27.1568 13.6064 27.1568 13.3123C27.1568 13.0182 27.3952 12.7798 27.6893 12.7798C27.9833 12.7798 28.2217 13.0182 28.2217 13.3123C28.2217 13.6064 27.9833 13.8448 27.6893 13.8448Z" fill="#CBFE1C"></path>
                                        <path d="M20.7671 27.6895C20.473 27.6895 20.2346 27.9279 20.2346 28.2219V29.2869C20.2346 29.581 20.473 29.8194 20.7671 29.8194C21.0612 29.8194 21.2996 29.581 21.2996 29.2869V28.2219C21.2996 27.9279 21.0612 27.6895 20.7671 27.6895Z" fill="#CBFE1C"></path>
                                        <path d="M22.897 27.6895C22.6029 27.6895 22.3645 27.9279 22.3645 28.2219V29.2869C22.3645 29.581 22.6029 29.8194 22.897 29.8194C23.1911 29.8194 23.4295 29.581 23.4295 29.2869V28.2219C23.4295 27.9279 23.1911 27.6895 22.897 27.6895Z" fill="#CBFE1C"></path>
                                        <path d="M25.0269 27.6895C24.7328 27.6895 24.4944 27.9279 24.4944 28.2219V29.2869C24.4944 29.581 24.7328 29.8194 25.0269 29.8194C25.321 29.8194 25.5594 29.581 25.5594 29.2869V28.2219C25.5594 27.9279 25.321 27.6895 25.0269 27.6895Z" fill="#CBFE1C"></path>
                                        <path d="M27.1568 27.6895C26.8627 27.6895 26.6243 27.9279 26.6243 28.2219V29.2869C26.6243 29.581 26.8627 29.8194 27.1568 29.8194C27.4508 29.8194 27.6892 29.581 27.6892 29.2869V28.2219C27.6892 27.9279 27.4508 27.6895 27.1568 27.6895Z" fill="#CBFE1C"></path>
                                        <path d="M29.2866 27.6895C28.9926 27.6895 28.7542 27.9279 28.7542 28.2219V29.2869C28.7542 29.581 28.9926 29.8194 29.2866 29.8194C29.5807 29.8194 29.8191 29.581 29.8191 29.2869V28.2219C29.8191 27.9279 29.5807 27.6895 29.2866 27.6895Z" fill="#CBFE1C"></path>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_106_330">
                                        <rect width="31.9492" height="31.9492" fill="white"></rect>
                                        </clipPath>
                                    </defs>
                                    </svg>
                            </div>
                            <h4>
                                <a href="chat.php">AI Chat Mood Detect</a>
                            </h4>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".6s">
                        <div class="game-feature-box-2">
                            <img src="assets/img/game-controll.png" alt="">
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".8s">
                        <div class="game-feature-box-items">
                            <div class="shape-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="27" viewBox="0 0 30 27" fill="none">
                                    <path d="M1 27V6C1 3.23858 3.23858 1 6 1H30" stroke="white" stroke-opacity="0.2"></path>
                                </svg>
                            </div>
                            <div class="shape-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="27" viewBox="0 0 30 27" fill="none">
                                    <path d="M29 -9.53674e-07L29 21C29 23.7614 26.7614 26 24 26L-5.96046e-08 26" stroke="white" stroke-opacity="0.2"></path>
                                </svg>
                            </div>
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                    <g clip-path="url(#clip0_106_372)">
                                        <path d="M18.5671 23.9052C16.8811 24.4678 15.0213 24.4677 13.3305 23.9049C12.8567 23.7472 12.3452 24.0034 12.1876 24.477C12.03 24.9506 12.2861 25.4623 12.7597 25.6199C13.7894 25.9626 14.8703 26.1339 15.9509 26.1339C17.0316 26.1339 18.1119 25.9624 19.1392 25.6197C19.6126 25.4617 19.8684 24.9498 19.7103 24.4763C19.5524 24.003 19.0406 23.7473 18.5671 23.9052Z" fill="#CBFE1C"></path>
                                        <path d="M26.4768 6.53129C26.0921 3.77249 23.7232 1.59497 20.7892 1.59497H19.7568V1.53069C19.7568 1.03158 19.3522 0.626953 18.8531 0.626953H13.0451C12.546 0.626953 12.1414 1.03158 12.1414 1.53069V1.59491H11.1091C8.19966 1.59491 5.82747 3.74206 5.42405 6.53063C3.72997 6.91616 2.46143 8.43353 2.46143 10.2426V17.9867C2.46143 20.4633 4.47874 21.4726 5.39507 21.6904C5.80886 27.2658 10.4904 31.4744 15.9491 31.4744C21.5159 31.4744 26.0925 27.1543 26.5027 21.6906C28.878 21.1267 29.4368 18.9717 29.4368 17.9867V10.2427C29.4369 8.43449 28.1695 6.91773 26.4768 6.53129ZM19.7568 3.40238H20.7891C23.3462 3.40238 24.4025 5.49308 24.6221 6.43495H19.7568V3.40238ZM13.9489 2.43442H17.9494V6.43495H13.9489V2.43442ZM11.1091 3.40238H12.1414V6.43495H7.27984C7.866 3.97945 10.0905 3.40238 11.1091 3.40238ZM15.9491 29.6669C11.4886 29.6669 7.67573 26.2878 7.2188 21.7944H11.6754C12.7844 21.7944 13.8695 21.345 14.6524 20.5615C14.6571 20.5568 14.6617 20.5521 14.6662 20.5474C15.0062 20.2148 15.4727 20.0245 15.9491 20.0245C16.4257 20.0245 16.8921 20.2148 17.2321 20.5474C17.2366 20.5521 17.2412 20.5568 17.2459 20.5615C18.0288 21.345 19.1139 21.7944 20.2229 21.7944H24.6791C24.2253 26.2107 20.4833 29.6669 15.9491 29.6669ZM27.6294 17.9867C27.6294 19.1052 26.7178 19.9869 25.6291 19.9869H20.2229C19.5968 19.9869 18.9843 19.7362 18.5391 19.2984C18.5342 19.2933 18.5293 19.2882 18.5243 19.2832C17.8467 18.6056 16.9082 18.217 15.9492 18.217C14.9903 18.217 14.0517 18.6056 13.3741 19.2832C13.3691 19.2882 13.3641 19.2932 13.3593 19.2983C12.914 19.7362 12.3015 19.9869 11.6754 19.9869H6.2691C5.18389 19.9869 4.26883 19.109 4.26883 17.9867V10.2427C4.26883 9.13977 5.16618 8.24248 6.2691 8.24248H25.6291C26.732 8.24248 27.6293 9.13983 27.6293 10.2427V17.9867H27.6294Z" fill="#CBFE1C"></path>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_106_372">
                                        <rect width="30.8475" height="30.8475" fill="white" transform="translate(0.525391 0.626953)"></rect>
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>
                            <h4>
                                <a href="shop.php">Mood Based Products</a>
                            </h4>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".2s">
                        <div class="game-feature-box-items">
                            <div class="shape-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="27" viewBox="0 0 30 27" fill="none">
                                    <path d="M1 27V6C1 3.23858 3.23858 1 6 1H30" stroke="white" stroke-opacity="0.2"></path>
                                </svg>
                            </div>
                            <div class="shape-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="27" viewBox="0 0 30 27" fill="none">
                                    <path d="M29 -9.53674e-07L29 21C29 23.7614 26.7614 26 24 26L-5.96046e-08 26" stroke="white" stroke-opacity="0.2"></path>
                                </svg>
                            </div>
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 34 34" fill="none">
                                    <g clip-path="url(#clip0_106_386)">
                                        <path d="M29.7203 2.17773H4.38132C2.25276 2.18016 0.527819 3.9051 0.525391 6.03367V28.0676C0.527819 30.1961 2.25276 31.9211 4.38132 31.9235H29.7203C31.8489 31.9211 33.5738 30.1961 33.5762 28.0676V6.03367C33.5738 3.9051 31.8489 2.18016 29.7203 2.17773ZM32.4745 28.0676C32.4727 29.5879 31.2407 30.82 29.7203 30.8218H4.38132C2.86095 30.82 1.62891 29.5879 1.62709 28.0676V6.03367C1.62891 4.5133 2.86095 3.28125 4.38132 3.27943H29.7203C31.2407 3.28125 32.4727 4.5133 32.4745 6.03367V28.0676Z" fill="#CBFE1C"></path>
                                        <path d="M29.7203 6.58496H4.3813C3.46863 6.58496 2.72876 7.32483 2.72876 8.2375V28.068C2.72876 28.9807 3.46863 29.7206 4.3813 29.7206H29.7203C30.633 29.7206 31.3728 28.9807 31.3728 28.068V8.2375C31.3728 7.32483 30.633 6.58496 29.7203 6.58496ZM17.0508 17.527L11.0515 14.2968L17.0508 11.0667L23.0501 14.2968L17.0508 17.527ZM23.661 15.2189V21.6782L17.6016 24.9425V18.4833L23.661 15.2189ZM16.4999 18.4833V24.9425L10.4406 21.6782V15.2189L16.4999 18.4833ZM3.83045 8.2375C3.83045 7.93328 4.07708 7.68666 4.3813 7.68666H16.4999V10.1104L9.62867 13.8121C9.62151 13.8159 9.61325 13.8176 9.60609 13.822C9.43952 13.9221 9.338 14.1025 9.33893 14.2968V18.914L3.83045 21.6683V8.2375ZM30.2711 28.068C30.2711 28.3722 30.0245 28.6189 29.7203 28.6189H4.3813C4.07708 28.6189 3.83045 28.3722 3.83045 28.068V22.9L9.33893 20.1457V22.0087C9.33904 22.2113 9.45031 22.3974 9.62867 22.4934L16.7897 26.3494C16.9527 26.4371 17.1489 26.4371 17.3119 26.3494L24.4729 22.4934C24.6513 22.3974 24.7626 22.2113 24.7627 22.0087V20.1457L30.2711 22.9V28.068ZM30.2711 21.6683L24.7627 18.914V14.2968C24.7626 14.1031 24.6607 13.9236 24.4944 13.8242C24.4872 13.8198 24.479 13.8181 24.4718 13.8143L17.6016 10.1104V7.68666H29.7203C30.0245 7.68666 30.2711 7.93328 30.2711 8.2375V21.6683Z" fill="#CBFE1C"></path>
                                        <path d="M3.83039 5.48255C4.13461 5.48255 4.38124 5.23593 4.38124 4.93171C4.38124 4.62748 4.13461 4.38086 3.83039 4.38086C3.52616 4.38086 3.27954 4.62748 3.27954 4.93171C3.27954 5.23593 3.52616 5.48255 3.83039 5.48255Z" fill="#CBFE1C"></path>
                                        <path d="M6.03376 5.48255C6.33798 5.48255 6.58461 5.23593 6.58461 4.93171C6.58461 4.62748 6.33798 4.38086 6.03376 4.38086C5.72953 4.38086 5.48291 4.62748 5.48291 4.93171C5.48291 5.23593 5.72953 5.48255 6.03376 5.48255Z" fill="#CBFE1C"></path>
                                        <path d="M8.23713 5.48255C8.54135 5.48255 8.78797 5.23593 8.78797 4.93171C8.78797 4.62748 8.54135 4.38086 8.23713 4.38086C7.9329 4.38086 7.68628 4.62748 7.68628 4.93171C7.68628 5.23593 7.9329 5.48255 8.23713 5.48255Z" fill="#CBFE1C"></path>
                                        <path d="M22.0084 5.48255H29.7202C30.0245 5.48255 30.2711 5.23593 30.2711 4.93171C30.2711 4.62748 30.0245 4.38086 29.7202 4.38086H22.0084C21.7041 4.38086 21.4575 4.62748 21.4575 4.93171C21.4575 5.23593 21.7041 5.48255 22.0084 5.48255Z" fill="#CBFE1C"></path>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_106_386">
                                        <rect width="33.0508" height="33.0508" fill="white" transform="translate(0.525391 0.525391)"></rect>
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>
                            <h4>
                               <a href="music.php">Millions Of Songs</a>
                            </h4>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".4s">
                       <div class="superhero-feature-box bg-cover" style="background-image: url('assets/img/bg.jpg');">
                            <div class="thumb">
                                <img src="assets/img/superhero_1.png" alt="">
                            </div>
                       </div>
                    </div>
                     <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".6s">
                        <div class="game-feature-box-items">
                            <div class="shape-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="27" viewBox="0 0 30 27" fill="none">
                                    <path d="M1 27V6C1 3.23858 3.23858 1 6 1H30" stroke="white" stroke-opacity="0.2"></path>
                                </svg>
                            </div>
                            <div class="shape-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="27" viewBox="0 0 30 27" fill="none">
                                    <path d="M29 -9.53674e-07L29 21C29 23.7614 26.7614 26 24 26L-5.96046e-08 26" stroke="white" stroke-opacity="0.2"></path>
                                </svg>
                            </div>
                            <div class="icon">
                               <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                                <g clip-path="url(#clip0_106_353)">
                                    <path d="M8.40549 14.2412H0.220215V16.5651H8.40549C9.04619 16.5651 9.56743 17.0863 9.56743 17.727C9.56743 18.3677 9.04619 18.889 8.40549 18.889H3.70605C1.78396 18.889 0.220215 20.4527 0.220215 22.3748V25.8606H2.5441V22.3748C2.5441 21.7341 3.06535 21.2129 3.70605 21.2129H8.40549C10.3276 21.2129 11.8913 19.6491 11.8913 17.727C11.8913 15.805 10.3276 14.2412 8.40549 14.2412Z" fill="#CBFE1C"></path>
                                    <path d="M20.6316 14.2412C18.7095 14.2412 17.1457 15.805 17.1457 17.727V22.3748C17.1457 23.0155 16.6245 23.5368 15.9838 23.5368H10.7292V25.8606H15.9838C17.9059 25.8606 19.4696 24.2969 19.4696 22.3748V17.727C19.4696 17.0863 19.9909 16.5651 20.6316 16.5651H25.8861V14.2412H20.6316Z" fill="#CBFE1C"></path>
                                    <path d="M37.5573 21.2127V16.5651C37.5573 15.2837 36.5148 14.2412 35.2334 14.2412C34.7933 14.2412 34.3645 14.3652 33.9933 14.5996L26.6344 19.2473C25.9557 19.6759 25.5505 20.4107 25.5505 21.2129C25.5505 22.4943 26.593 23.5368 27.8744 23.5368H35.2334V25.8606H37.5573V23.5368H39.8812V21.2129L37.5573 21.2127ZM35.2334 21.2126L27.8753 21.2122L35.2334 16.5651V21.2126Z" fill="#CBFE1C"></path>
                                </g>
                                <defs>
                                    <clipPath id="clip0_106_353">
                                    <rect width="39.661" height="39.661" fill="white" transform="translate(0.220215 0.220703)"></rect>
                                    </clipPath>
                                </defs>
                                </svg>
                            </div>
                            <h4>
                              <a href="memes.php">Memes For Your Mood</a>
                            </h4>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <div class="game-feature-box-items wow fadeInUp" data-wow-delay=".8s">
                            <div class="shape-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="27" viewBox="0 0 30 27" fill="none">
                                    <path d="M1 27V6C1 3.23858 3.23858 1 6 1H30" stroke="white" stroke-opacity="0.2"></path>
                                </svg>
                            </div>
                            <div class="shape-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="27" viewBox="0 0 30 27" fill="none">
                                    <path d="M29 -9.53674e-07L29 21C29 23.7614 26.7614 26 24 26L-5.96046e-08 26" stroke="white" stroke-opacity="0.2"></path>
                                </svg>
                            </div>
                            <div class="icon">
                               <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                    <g clip-path="url(#clip0_106_404)">
                                        <path d="M31.3522 27.4493L30.6906 24.9802C30.6045 24.6588 30.274 24.4678 29.9527 24.5542C29.6312 24.6403 29.4405 24.9707 29.5266 25.2921L29.8163 26.373L25.5885 23.9614V13.9382C25.5885 13.7225 25.4732 13.5233 25.2863 13.4158L16.5507 8.39345V3.62075L17.3297 4.39976C17.565 4.63504 17.9465 4.63504 18.1818 4.39976C18.4171 4.16447 18.4171 3.78295 18.1818 3.54761L16.3742 1.74004C16.1387 1.50355 15.753 1.50831 15.5221 1.74004L13.7145 3.54761C13.4792 3.78289 13.4792 4.16441 13.7145 4.39976C13.9498 4.63498 14.3313 4.63498 14.5667 4.39976L15.3457 3.62075V8.39454L6.66869 13.4167C6.48258 13.5244 6.36798 13.7232 6.36798 13.9382V23.864L2.08073 26.3002L2.37084 25.2173C2.45694 24.8959 2.26618 24.5655 1.94474 24.4794C1.62323 24.3931 1.29293 24.584 1.20683 24.9055L0.545259 27.3752C0.461508 27.6879 0.648772 28.0359 0.989378 28.1179L3.4405 28.7747C3.76454 28.8615 4.09303 28.6674 4.17847 28.3486C4.26463 28.0272 4.07387 27.6969 3.75243 27.6107L2.70687 27.3306L6.96194 24.9126L15.6804 30.0465C15.867 30.1564 16.0988 30.1579 16.2871 30.0493L18.4973 28.7752C18.7856 28.609 18.8845 28.2405 18.7184 27.9523C18.5522 27.664 18.1837 27.5651 17.8954 27.7312L15.9894 28.83L8.1706 24.2259L15.9102 19.8278L23.799 24.3279L22.5921 25.0237C22.3038 25.1898 22.2049 25.5583 22.371 25.8465C22.5373 26.1349 22.9058 26.2339 23.194 26.0676L25.0118 25.0196L29.1922 27.4044L28.1452 27.6849C27.8238 27.771 27.633 28.1014 27.7192 28.4228C27.8049 28.7426 28.1342 28.9355 28.4571 28.8488L30.8719 28.2019C31.2167 28.141 31.4447 27.7944 31.3522 27.4493ZM24.3834 23.2739L16.5506 18.8058V9.78347L24.3834 14.2868V23.2739ZM7.57302 14.2856L15.3456 9.78697V18.7625L7.57302 23.1792V14.2856Z" fill="#CBFE1C"></path>
                                        <path d="M20.5273 27.4716C20.8601 27.4716 21.1299 27.2019 21.1299 26.8691C21.1299 26.5364 20.8601 26.2666 20.5273 26.2666C20.1946 26.2666 19.9248 26.5364 19.9248 26.8691C19.9248 27.2019 20.1946 27.4716 20.5273 27.4716Z" fill="#CBFE1C"></path>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_106_404">
                                        <rect width="30.8475" height="30.8475" fill="white" transform="translate(0.525391 0.423828)"></rect>
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>
                            <h4>
                            <a href="games.php">Amazing <br> Games</a>
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="video-play-banner">
                    <video class="video-play parallaxScaleScroll" autoplay muted loop playsinline preload="metadata">
                        <source src="assets/media/gaming-play.mp4" type="video/mp4">
                    </video>
                </div>
            </div>
        </section>

        <!-- Marque Section Start -->
        <div class="marque-section section-padding fix pb-0">
            <div class="gradient-shape">
                <img src="assets/img/gradient-shape.png" alt="img">
            </div>
            <h2 class="tx-title sec_title  tz-itm-title tz-itm-anim mb-5 text-center">
                          Popular <span>Games</span> For You
                        </h2>
            <div class="marquee-wrapper text-slider">
                <div class="marquee-inner to-left">
                    <ul class="marqee-list d-flex">
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-1.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-2.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-3.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-4.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-5.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-6.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-7.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-8.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-9.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-10.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-11.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-12.jpg" alt="">
                            </span>
                        </li>
                    </ul>
                     <ul class="marqee-list d-flex">
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-1.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-2.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-3.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-4.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-5.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-6.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-7.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-8.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-9.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-10.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-11.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-12.jpg" alt="">
                            </span>
                        </li>
                    </ul>
                     <ul class="marqee-list d-flex">
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-1.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-2.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-3.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-4.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-5.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-6.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-7.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-8.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-9.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-10.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-11.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-12.jpg" alt="">
                            </span>
                        </li>
                    </ul>
                     <ul class="marqee-list d-flex">
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-1.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-2.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-3.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-4.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-5.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-6.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-7.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-8.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-9.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-10.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-11.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-12.jpg" alt="">
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="marquee-wrapper text-slider style-2 mt-3">
                <div class="marquee-inner to-right">
                    <ul class="marqee-list d-flex">
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-12.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-11.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-10.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-9.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-8.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-7.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-6.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-5.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-4.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-3.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-2.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-1.jpg" alt="">
                            </span>
                        </li>
                    </ul>
                    <ul class="marqee-list d-flex">
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-12.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-11.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-10.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-9.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-8.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-7.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-6.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-5.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-4.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-3.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-2.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-1.jpg" alt="">
                            </span>
                        </li>
                    </ul>
                    <ul class="marqee-list d-flex">
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-12.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-11.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-10.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-9.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-8.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-7.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-6.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-5.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-4.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-3.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-2.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-1.jpg" alt="">
                            </span>
                        </li>
                    </ul>
                    <ul class="marqee-list d-flex">
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-12.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-11.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-10.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-9.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-8.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-7.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-6.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-5.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-4.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-3.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-2.jpg" alt="">
                            </span>
                        </li>
                        <li class="marquee-item">
                            <span class="text-slider">
                                <img src="assets/img/logo-1.jpg" alt="">
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        
            <div class="swiper line-slider">
                <div class="swiper-wrapper gt-slide-transtion">
                    <div class="swiper-slide gt-brand-slide-element">
                        <span class="line1"></span>
                    </div>
                     <div class="swiper-slide gt-brand-slide-element">
                        <span class="line1"></span>
                    </div>
                     <div class="swiper-slide gt-brand-slide-element">
                        <span class="line1"></span>
                    </div>
                    <div class="swiper-slide gt-brand-slide-element">
                        <span class="line1"></span>
                    </div>
                    <div class="swiper-slide gt-brand-slide-element">
                        <span class="line1"></span>
                    </div>
                    <div class="swiper-slide gt-brand-slide-element">
                        <span class="line1"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Section Start -->
        <section class="team-section fix mt-5 section-padding pt-0">
            <div class="radius-shape float-bob-y">
               <img src="assets/img/radius-shape.png" alt="img">
            </div>
            <div class="container">
                <div class="section-title-area">
                    <div class="section-title mb-0">
                        <h6 class="subtitle tz-sub-tilte tz-sub-anim  text-uppercase tx-subTitle">our team members</h6>
                        <h2 class="tx-title sec_title  tz-itm-title tz-itm-anim">
                          Our <span>Best Team</span> Members
                        </h2>
                    </div>
                     <div class="array-button d-flex align-items-center wow fadeInUp" data-wow-delay=".5s">
                        <button class="array-prev">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                <g clip-path="url(#clip0_135_87)">
                                    <path d="M0.000243405 1.16006L0.000243999 14.762C0.000244027 15.4019 0.520557 15.9222 1.1604 15.9222C1.80024 15.9222 2.32056 15.4019 2.32056 14.762L2.32056 3.96553L16.0174 17.6589C16.4709 18.1124 17.2057 18.1124 17.6592 17.6589C18.1127 17.2054 18.1127 16.4706 17.6592 16.0171L3.96235 2.32373L14.7588 2.32373C15.3987 2.32373 15.919 1.80342 15.919 1.16357C15.919 0.523731 15.3987 0.0034173 14.7588 0.00341732L1.1604 0.00341792C0.520556 -9.72975e-05 0.000243377 0.520214 0.000243405 1.16006Z" fill="#0B0E13"></path>
                                </g>
                                <defs>
                                    <clipPath id="clip0_135_87">
                                    <rect width="18" height="18" fill="white" transform="translate(0.000244141 18) rotate(-90)"></rect>
                                    </clipPath>
                                </defs>
                                </svg>
                        </button>
                        <button class="array-next">
                           <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <g clip-path="url(#clip0_135_80)">
                                <path d="M17.9944 16.8399L17.9944 3.23799C17.9944 2.59814 17.4741 2.07783 16.8342 2.07783C16.1944 2.07783 15.6741 2.59814 15.6741 3.23799L15.6741 14.0345L1.9772 0.341113C1.52368 -0.112403 0.788916 -0.112403 0.335402 0.341112C-0.118115 0.794628 -0.118115 1.52939 0.335401 1.98291L14.0323 15.6763L3.23579 15.6763C2.59595 15.6763 2.07563 16.1966 2.07563 16.8364C2.07563 17.4763 2.59595 17.9966 3.23579 17.9966L16.8342 17.9966C17.4741 18.0001 17.9944 17.4798 17.9944 16.8399Z" fill="#0B0E13"></path>
                            </g>
                            <defs>
                                <clipPath id="clip0_135_80">
                                <rect width="18" height="18" fill="white" transform="translate(17.9944) rotate(90)"></rect>
                                </clipPath>
                            </defs>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="swiper team-slider">
                    <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="team-box-items">
                            <div class="content">
                                <h3><a href="team-details.html">Maha Kanwal</a></h3>
                                <p>CEO & Founder</p>
                            </div>
                            <div class="thumb">
                                <img src="assets/img/maha.png" alt="img">
                                <div class="social-icon1 d-flex align-items-center">
                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#"><i class="fab fa-twitter"></i></a>
                                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="team-box-items">
                            <div class="content">
                                <h3><a href="team-details.html">Shikhon Islam</a></h3>
                                <p>Game artist</p>
                            </div>
                            <div class="thumb">
                                <img src="assets/img/team-02.png" alt="img">
                                <div class="social-icon1 d-flex align-items-center">
                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#"><i class="fab fa-twitter"></i></a>
                                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="team-box-items">
                            <div class="content">
                                <h3><a href="team-details.html">Robert David</a></h3>
                                <p>Game artist</p>
                            </div>
                            <div class="thumb">
                                <img src="assets/img/team-03.png" alt="img">
                                <div class="social-icon1 d-flex align-items-center">
                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#"><i class="fab fa-twitter"></i></a>
                                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="team-box-items">
                            <div class="content">
                                <h3><a href="team-details.html">Maha Kanwal</a></h3>
                                <p>CEO & Founder</p>
                            </div>
                            <div class="thumb">
                                <img src="assets/img/maha.png" alt="img">
                                <div class="social-icon1 d-flex align-items-center">
                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#"><i class="fab fa-twitter"></i></a>
                                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="swiper-slide">
                        <div class="team-box-items">
                            <div class="content">
                                <h3><a href="team-details.html">Donald Paul</a></h3>
                                <p>Game artist</p>
                            </div>
                            <div class="thumb">
                                <img src="assets/img/team-05.png" alt="img">
                                <div class="social-icon1 d-flex align-items-center">
                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#"><i class="fab fa-twitter"></i></a>
                                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="swiper-slide">
                        <div class="team-box-items">
                            <div class="content">
                                <h3><a href="team-details.html">Donald Paul</a></h3>
                                <p>Game artist</p>
                            </div>
                            <div class="thumb">
                                <img src="assets/img/team-05.png" alt="img">
                                <div class="social-icon1 d-flex align-items-center">
                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#"><i class="fab fa-twitter"></i></a>
                                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </section>

       
        <!-- Testimonial Section Start -->
        <section id="testimonials" class="testimonial-section fix bg-cover" style="background-image: url('assets/img/testimonial-bg.jpg');">
    <div class="radius-shapee float-bob-y">
        <img src="assets/img/radius-shape.png" alt="img">
    </div>
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-7 order-2 order-xl-1">
                <div class="testimonial-wrapper pin-area">
                    <div class="pin-inner">
                        <!-- Testimonial 1 -->
                        <div class="testimonial-sticky-hover" id="card1">
                            <div class="tetsimonial-box-items">
                                <div class="client-thumb">
                                    <img src="assets/img/team-03.png" alt="img">
                                    <div class="quote-icon">
                                        <img src="assets/img/quote-icon.png" alt="img">
                                    </div>
                                </div>
                                <div class="testi-content">
                                    <p>
                                        MoodBuddy always lifts my mood! Whenever I’m feeling low, it gives me positive quotes, songs, and even fun memes that make my day better.
                                    </p>
                                    <div class="client-info">
                                        <h6>Ayesha Khan</h6>
                                        <span>Student</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Testimonial 2 -->
                        <div class="testimonial-sticky-hover style-none" id="card2">
                            <div class="tetsimonial-box-items">
                                <div class="client-thumb">
                                    <img src="assets/img/team-02.png" alt="img">
                                    <div class="quote-icon">
                                        <img src="assets/img/quote-icon.png" alt="img">
                                    </div>
                                </div>
                                <div class="testi-content">
                                    <p>
                                        I love how MoodBuddy understands my emotions. The mood-based recommendations are always on point – from jokes to games!
                                    </p>
                                    <div class="client-info">
                                        <h6>Ali Raza</h6>
                                        <span>Software Engineer</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Testimonial 3 -->
                        <div class="testimonial-sticky-hover style-none" id="card3">
                            <div class="tetsimonial-box-items">
                                <div class="client-thumb">
                                    <img src="assets/img/team-01.png" alt="img">
                                    <div class="quote-icon">
                                        <img src="assets/img/quote-icon.png" alt="img">
                                    </div>
                                </div>
                                <div class="testi-content">
                                    <p>
                                        The best thing about MoodBuddy is that it feels personal. Whether I’m happy or stressed, it gives me exactly what I need.
                                    </p>
                                    <div class="client-info">
                                        <h6>Sara Ahmed</h6>
                                        <span>Content Creator</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Title Area -->
            <div class="col-lg-5 order-1 order-xl-2">
                <div class="section-title">
                    <h6 class="subtitle tz-sub-tilte tz-sub-anim text-uppercase tx-subTitle text-white">our testimonials</h6>
                    <h2 class="tx-title sec_title tz-itm-title tz-itm-anim">
                        What People <span>Say About <br> MoodBuddy</span>
                    </h2>
                </div>
            </div>
        </div>
    </div>
</section>

        
        <!-- News Section Start -->
        <section class="news-section fix section-padding">
            <div class="container">
                <div class="section-title-area">
                    <div class="section-title mb-0">
                        <h6 class="subtitle tz-sub-tilte tz-sub-anim  text-uppercase tx-subTitle">latest news</h6>
                        <h2 class="tx-title sec_title  tz-itm-title tz-itm-anim">
                           Our Latest <span>News & <br> Blog</span> Archive
                        </h2>
                    </div>
                    <a href="news.html" class="theme-btn wow fadeInUp" data-wow-delay=".5s">
                       view all blogs
                    </a>
                </div>
                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".2s">
                        <div class="news-box-items">
                            <div class="thumb">
                                <img src="assets/img/news-01.jpg" alt="img">
                                <img src="assets/img/news-01.jpg" alt="img">
                            </div>
                            <div class="content">
                                <h3><a href="news-details.html">The evolution of online gaming and its rise</a></h3>
                                <span class="gt-date">
                                    <i class="fa-solid fa-calendar-days"></i> 11 March 2025
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".4s">
                        <div class="news-box-items">
                            <div class="content">
                                <h3><a href="news-details.html">The Rise of Online Gaming in the Age of AI</a></h3>
                                <span class="gt-date">
                                    <i class="fa-solid fa-calendar-days"></i> 11 March 2025
                                </span>
                            </div>
                            <div class="thumb style-2">
                                 <img src="assets/img/news-02.jpg" alt="img">
                                 <img src="assets/img/news-02.jpg" alt="img">
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".6s">
                        <div class="news-box-items">
                            <div class="thumb">
                                <img src="assets/img/news-03.jpg" alt="img">
                                <img src="assets/img/news-03.jpg" alt="img">
                            </div>
                            <div class="content">
                                <h3><a href="news-details.html">The AI-Driven Future of Online Gaming</a></h3>
                                <span class="gt-date">
                                    <i class="fa-solid fa-calendar-days"></i> 11 March 2025
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".8s">
                        <div class="news-box-items">
                            <div class="content">
                                <h3><a href="news-details.html">How AI Is Changing the Game in Online Play</a></h3>
                                <span class="gt-date">
                                    <i class="fa-solid fa-calendar-days"></i> 11 March 2025
                                </span>
                            </div>
                            <div class="thumb style-2">
                                 <img src="assets/img/news-04.jpg" alt="img">
                                 <img src="assets/img/news-04.jpg" alt="img">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Brand Section Start -->
        <div class="brand-section">
            <div class="container mb-5">
                <div class="swiper brand-slider">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="brand-image">
                                <img src="assets/img/b1.png" alt="img">
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="brand-image">
                                <img src="assets/img/b2.png" alt="img">
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="brand-image">
                                <img src="assets/img/b3.png" alt="img">
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="brand-image">
                                <img src="assets/img/b4.png" alt="img">
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="brand-image">
                                <img src="assets/img/b5.png" alt="img">
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="brand-image">
                                <img src="assets/img/b6.png" alt="img">
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="brand-image">
                                <img src="assets/img/b7.png" alt="img">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="futuristic-footer fade-in">
        <div class="footer-container">
            <div class="footer-grid">
                <!-- About Section -->
                <div class="footer-section1">
                    <h3><i class="fas fa-heart"></i> About MoodBuddy</h3>
                    <p style="color: var(--text-secondary); line-height: 1.6; margin-bottom: 1.5rem;">
                        MoodBuddy 2027 is your AI-powered companion for personalized mood-based recommendations. 
                        Discover products, music, quotes, and experiences tailored to your emotional state.
                    </p>
                    <div class="social-icons">
                        <a href="#" class="social-icon" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-icon" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-icon" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-icon" title="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="social-icon" title="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-section1">
                    <h3><i class="fas fa-link"></i> Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="home.php"><i class="fas fa-home"></i> Home</a></li>
                        <li><a href="about.php"><i class="fas fa-info-circle"></i> About Us</a></li>
                        <li><a href="shop.php"><i class="fas fa-shopping-bag"></i> Shop</a></li>
                        <li><a href="music.php"><i class="fas fa-music"></i> Music Library</a></li>
                        <li><a href="#"><i class="fas fa-quote-right"></i> Inspirational Quotes</a></li>
                        <li><a href="memes.php"><i class="fas fa-smile"></i> Mood Memes</a></li>
                        <li><a href="games.php"><i class="fas fa-gamepad"></i> Interactive Games</a></li>
                    </ul>
                </div>

                <!-- Support & Services -->
                <div class="footer-section1">
                    <h3><i class="fas fa-headset"></i> Support & Services</h3>
                    <ul class="footer-links">
                        <li><a href="contact.php"><i class="fas fa-phone"></i> Contact Support</a></li>
                        <li><a href="contact.php"><i class="fas fa-question-circle"></i> FAQ</a></li>
                        <li><a href="contact.php"><i class="fas fa-life-ring"></i> Help Center</a></li>
                        <!-- <li><a href="api.html"><i class="fas fa-code"></i> API Documentation</a></li> -->
                        <li><a href="feedback.php"><i class="fas fa-comment"></i> Send Feedback</a></li>
                        <li><a href="contact.php"><i class="fas fa-users"></i> Community</a></li>
                        <!-- <li><a href="blog.html"><i class="fas fa-blog"></i> Blog</a></li> -->
                    </ul>
                </div>

                <!-- Newsletter Subscription -->
                <div class="footer-section1">
                    <div class="newsletter-section">
                        <h3><i class="fas fa-envelope"></i> Stay Updated</h3>
                        <p style="color: var(--text-secondary); margin-bottom: 1rem;">
                            Subscribe to our newsletter for the latest mood-based recommendations and AI insights.
                        </p>
                        <form class="newsletter-form" action="#" method="POST">
                            <input 
                                type="email" 
                                class="newsletter-input" 
                                placeholder="maha@gmail.com"
                                required
                            > <br>
                            <button type="submit" class="newsletter-btn">
                                <i class="fas fa-paper-plane"></i> Subscribe
                            </button>
                        </form>
                        <p style="font-size: 0.8rem; color: var(--text-secondary); margin-top: 0.5rem;">
                            <i class="fas fa-shield-alt"></i> We respect your privacy. Unsubscribe anytime.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-logo">MoodBuddy</div>
                <div class="footer-copyright">
                    © 2025 MoodBuddy. All rights reserved by <span >Maha Kanwal</span>
                </div>
                <ul class="footer-links-bottom">
                    <li><a href="privacy.html">Privacy Policy</a></li>
                    <li><a href="terms.html">Terms of Service</a></li>
                    <li><a href="cookies.html">Cookie Policy</a></li>
                </ul>
            </div>
        </div>
                </footer>
            </div>
        </div>

        
         
        <script src="assets/js/jquery-3.7.1.min.js"></script>
        <script src="assets/js/viewport.jquery.js"></script>
        <script src="assets/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/jquery.nice-select.min.js"></script>
        <script src="assets/js/jquery.waypoints.js"></script>
        <script src="assets/js/jquery.counterup.min.js"></script>
        <script src="assets/js/parallaxie.js"></script>
        <script src="assets/js/swiper-bundle.min.js"></script>
        <script src="assets/js/jquery.meanmenu.min.js"></script>
        <script src="assets/js/jquery.magnific-popup.min.js"></script>
        <script src="assets/js/wow-home.min.js"></script>
        <script src="assets/js/gsap.js"></script>
        <script src="assets/js/gsap-scroll-to-plugin.js"></script>
        <script src="assets/js/gsap-scroll-smoother.js"></script>
        <script src="assets/js/gsap-scroll-trigger.js"></script>
        <script src="assets/js/SplitText.min.js"></script>
        <script src="assets/js/splitType.js"></script>
        <script src="assets/js/main-home.js"></script>
        <script src="assets/js/app.js"></script>
        <script src="assets/js/hero.js"></script>
    
</body></html>