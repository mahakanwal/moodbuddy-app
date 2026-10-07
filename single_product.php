<?php
// Get product ID from URL parameter
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Sample product data (in real application, this would come from database)
$products = [
    1 => [
        'id' => 1,
        'title' => 'Premium Wireless Headphones',
        'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=300&fit=crop',
        'price' => '$299.99',
        'description' => 'Experience crystal-clear audio with our premium wireless headphones featuring noise cancellation and 30-hour battery life.',
        'features' => [
            'Active Noise Cancellation',
            '30-hour Battery Life',
            'Bluetooth 5.0 Connectivity',
            'Premium Leather Comfort',
            'Quick Charge Technology'
        ],
        'rating' => 4.8,
        'reviews' => 1247,
        'category' => 'Electronics',
        'brand' => 'AudioTech Pro',
        'link' => '#'
    ],
    2 => [
        'id' => 2,
        'title' => 'Smart Fitness Watch',
        'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=300&fit=crop',
        'price' => '$199.99',
        'description' => 'Track your fitness goals with advanced health monitoring, GPS tracking, and smart notifications.',
        'features' => [
            'Heart Rate Monitor',
            'GPS Tracking',
            'Water Resistant',
            'Sleep Tracking',
            '7-day Battery Life'
        ],
        'rating' => 4.6,
        'reviews' => 892,
        'category' => 'Wearables',
        'brand' => 'FitTech',
        'link' => '#'
    ],
    3 => [
        'id' => 3,
        'title' => 'Ergonomic Office Chair',
        'image' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=400&h=300&fit=crop',
        'price' => '$449.99',
        'description' => 'Enhance your workspace comfort with our ergonomic office chair designed for long hours of productivity.',
        'features' => [
            'Lumbar Support',
            'Adjustable Height',
            'Breathable Mesh',
            '360° Swivel',
            'Premium Materials'
        ],
        'rating' => 4.9,
        'reviews' => 634,
        'category' => 'Furniture',
        'brand' => 'ErgoComfort',
        'link' => '#'
    ]
];

// Get current product or default to first product
$current_product = isset($products[$product_id]) ? $products[$product_id] : $products[1];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $current_product['title'] ?> - MoodBuddy</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }      
        :root {
            /* Same pink and sky blue theme as fetch-data.php */
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
            font-family: 'Orbitron', monospace;
            background: var(--light-bg);
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Same animated background as fetch-data.php */
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

        /* Floating objects animation */
        .floating-object {
            position: absolute;
            border-radius: 50%;
            background: var(--primary-gradient);
            opacity: 0.1;
            animation: float 20s infinite linear;
        }

        .floating-object:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-object:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 15%;
            animation-delay: -5s;
        }

        .floating-object:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 30%;
            left: 20%;
            animation-delay: -10s;
        }

        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(120deg); }
            66% { transform: translateY(10px) rotate(240deg); }
            100% { transform: translateY(0px) rotate(360deg); }
        }

        /* Header styling */
        .header {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border-bottom: 2px solid transparent;
            border-image: var(--primary-gradient) 1;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 900;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-links a:hover {
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        /* Main container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Product detail layout */
        .product-detail {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            margin: 3rem 0;
        }

        .product-image-container {
            position: relative;
        }

        .product-image {
            width: 100%;
            height: 500px;
            object-fit: cover;
            border-radius: 20px;
            border: 2px solid transparent;
            background-clip: padding-box;
            /* border-image: var(--primary-gradient) 1; */
        }

        .product-info {
            padding: 2rem 0;
        }

        .product-title {
            font-size: 2.5rem;
            font-weight: 900;
            margin-bottom: 1rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .product-price {
            font-size: 2rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }

        .product-rating {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stars {
            color: #fbbf24;
            font-size: 1.2rem;
        }

        .rating-text {
            color: var(--text-secondary);
        }

        .product-description {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 2rem;
            color: var(--text-secondary);
        }

        .product-features {
            margin-bottom: 2rem;
        }

        .features-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        .features-list {
            list-style: none;
        }

        .features-list li {
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .features-list li::before {
            content: '✓';
            color: var(--primary-color);
            font-weight: bold;
            font-size: 1.2rem;
        }

        /* Same button styling as fetch-data.php */
        .futuristic-btn {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 1rem 2.5rem;
            border-radius: 25px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            font-size: 1rem;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            margin-right: 1rem;
        }

        .futuristic-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }

        .futuristic-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px var(--glow-blue);
            filter: brightness(1.1);
        }

        .futuristic-btn:hover::before {
            left: 100%;
        }

        .btn-secondary {
            background: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }

        .btn-secondary:hover {
            background: var(--primary-gradient);
            color: white;
        }

        /* Product meta info */
        .product-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }

        .meta-item {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 2px solid transparent;
            background-clip: padding-box;
            border-image: var(--primary-gradient) 1;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
        }

        .meta-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .meta-value {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .product-detail {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .product-title {
                font-size: 2rem;
            }

            .nav-links {
                display: none;
            }

            .container {
                padding: 1rem;
            }
        }

        /* Scroll animations */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        /* From Uiverse.io by Mohammad-Rahme-576 */ 
/* Container Styles */
.tooltip-container {
  position: relative;
  display: inline-block;
  font-family: "Arial", sans-serif;
  overflow: visible;
}

/* Button Styles */
.button-content {
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #6e8efb, #a777e3);
  color: white;
  padding: 14px 28px;
  border-radius: 50px;
  cursor: pointer;
  transition:
    background 0.4s cubic-bezier(0.25, 0.8, 0.25, 1),
    transform 0.3s ease,
    box-shadow 0.4s ease;
  box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
  position: relative;
  z-index: 10;
  overflow: hidden;
}

.button-content::before {
  content: "";
  position: absolute;
  inset: 0;
  border-radius: inherit;
  background: linear-gradient(
    135deg,
    rgba(110, 142, 251, 0.4),
    rgba(167, 119, 227, 0.4)
  );
  filter: blur(15px);
  opacity: 0;
  transition: opacity 0.5s ease;
  z-index: -1;
}

.button-content::after {
  content: "";
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(
    circle,
    rgba(255, 255, 255, 0.3) 0%,
    rgba(255, 255, 255, 0) 70%
  );
  transform: scale(0);
  transition: transform 0.6s ease-out;
  z-index: -1;
}

.button-content:hover::before {
  opacity: 1;
}

.button-content:hover::after {
  transform: scale(1);
}

.button-content:hover {
  background: linear-gradient(135deg, #a777e3, #6e8efb);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
  transform: translateY(-4px) scale(1.03);
}

.button-content:active {
  transform: translateY(-2px) scale(0.98);
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
}

.text {
  font-size: 18px;
  font-weight: 600;
  margin-right: 12px;
  white-space: nowrap;
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
  transition: letter-spacing 0.3s ease;
}

.button-content:hover .text {
  letter-spacing: 1px;
}

.share-icon {
  fill: white;
  transition:
    transform 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55),
    fill 0.3s ease;
  filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.1));
}

.button-content:hover .share-icon {
  transform: rotate(180deg) scale(1.1);
  fill: #ffffff;
}

/* Tooltip Styles */
.tooltip-content {
  position: absolute;
  top: 102%;
  left: 50%;
  transform: translateX(-50%) scale(0.8);
  background: white;
  border-radius: 15px;
  padding: 22px;
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
  opacity: 0;
  visibility: hidden;
  transition:
    opacity 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55),
    transform 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55),
    visibility 0.5s ease;
  z-index: 100;
  pointer-events: none;
  backdrop-filter: blur(10px);
  background: rgba(255, 255, 255, 0.9);
}

.tooltip-container:hover .tooltip-content {
  opacity: 1;
  visibility: visible;
  transform: translateX(-50%) scale(1);
  pointer-events: auto;
}

/* Social Icons Styles */
.social-icons {
  display: flex;
  justify-content: space-between;
  gap: 12px;
}

.social-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: #f0f0f0;
  transition:
    transform 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55),
    background 0.3s ease,
    box-shadow 0.4s ease;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  position: relative;
  overflow: hidden;
}

.social-icon::before {
  content: "";
  position: absolute;
  inset: 0;
  background: radial-gradient(
    circle at center,
    rgba(255, 255, 255, 0.8) 0%,
    rgba(255, 255, 255, 0) 70%
  );
  opacity: 0;
  transition: opacity 0.3s ease;
}

.social-icon:hover::before {
  opacity: 1;
}

.social-icon svg {
  width: 24px;
  height: 24px;
  fill: #333;
  transition:
    transform 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55),
    fill 0.3s ease;
  z-index: 1;
}

.social-icon:hover {
  transform: translateY(-5px) scale(1.1);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
}

.social-icon:active {
  transform: translateY(-2px) scale(1.05);
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
}

.social-icon:hover svg {
  transform: scale(1.2);
  fill: white;
}

.social-icon.twitter:hover {
  background: linear-gradient(135deg, #1da1f2, #1a91da);
}

.social-icon.facebook:hover {
  background: linear-gradient(135deg, #1877f2, #165ed0);
}

.social-icon.linkedin:hover {
  background: linear-gradient(135deg, #0077b5, #005e94);
}

/* Animation for Pulse Effect */
@keyframes pulse {
  0% {
    box-shadow: 0 0 0 0 rgba(110, 142, 251, 0.4);
  }
  70% {
    box-shadow: 0 0 0 20px rgba(110, 142, 251, 0);
  }
  100% {
    box-shadow: 0 0 0 0 rgba(110, 142, 251, 0);
  }
}

.button-content {
  animation: pulse 3s infinite;
}

/* Hover Ripple Effect */
@keyframes ripple {
  0% {
    transform: scale(0);
    opacity: 1;
  }
  100% {
    transform: scale(4);
    opacity: 0;
  }
}

.button-content::before {
  content: "";
  position: absolute;
  inset: 0;
  background: rgba(255, 255, 255, 0.3);
  border-radius: inherit;
  transform: scale(0);
  opacity: 0;
}

.button-content:active::before {
  animation: ripple 0.6s linear;
}

/* Tooltip Arrow */
.tooltip-content::before {
  content: "";
  position: absolute;
  top: -10px;
  left: 50%;
  transform: translateX(-50%);
  border-width: 0 10px 10px 10px;
  border-style: solid;
  border-color: transparent transparent rgba(255, 255, 255, 0.9) transparent;
  filter: drop-shadow(0 -3px 3px rgba(0, 0, 0, 0.1));
}

/* Accessibility */
.button-content:focus {
  outline: none;
  box-shadow:
    0 0 0 3px rgba(110, 142, 251, 0.5),
    0 8px 15px rgba(0, 0, 0, 0.1);
}

.button-content:focus:not(:focus-visible) {
  box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
}

/* Responsive Design */
@media (max-width: 768px) {
  .button-content {
    padding: 12px 24px;
    border-radius: 40px;
  }

  .text {
    font-size: 16px;
  }

  .tooltip-content {
    width: 240px;
    padding: 18px;
  }

  .social-icon {
    width: 44px;
    height: 44px;
  }

  .social-icon svg {
    width: 20px;
    height: 20px;
  }
}

@media (max-width: 480px) {
  .button-content {
    padding: 10px 20px;
  }

  .text {
    font-size: 14px;
  }

  .tooltip-content {
    width: 200px;
    padding: 15px;
  }

  .social-icon {
    width: 40px;
    height: 40px;
  }

  .social-icon svg {
    width: 18px;
    height: 18px;
  }
}

/* Dark Mode Support */
@media (prefers-color-scheme: dark) {
  .tooltip-content {
    background: rgba(30, 30, 30, 0.9);
    color: white;
  }

  .tooltip-content::before {
    border-color: transparent transparent rgba(30, 30, 30, 0.9) transparent;
  }

  .social-icon {
    background: #2a2a2a;
  }

  .social-icon svg {
    fill: #e0e0e0;
  }
}

/* Print Styles */
@media print {
  .tooltip-container {
    display: none;
  }
}

/* Reduced Motion */
@media (prefers-reduced-motion: reduce) {
  .button-content,
  .share-icon,
  .social-icon,
  .tooltip-content {
    transition: none;
  }

  .button-content {
    animation: none;
  }
}

/* Custom Scrollbar for Tooltip Content */
.tooltip-content::-webkit-scrollbar {
  width: 6px;
}

.tooltip-content::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

.tooltip-content::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 3px;
}

.tooltip-content::-webkit-scrollbar-thumb:hover {
  background: #555;
}

    </style>
</head>
<body class="body-bg">
    <!-- Same animated background as fetch-data.php -->
    <div class="bg-animation">
        <div class="floating-object"></div>
        <div class="floating-object"></div>
        <div class="floating-object"></div>
    </div>

    <!-- Header -->
    <header class="header">
        <div class="nav-container">
            <div class="logo">MoodBuddy</div>
            <nav>
                <ul class="nav-links">
                    <li><a href="index.html">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#shop">Shop</a></li>
                    <li><a href="#music">Music</a></li>
                    <li><a href="#quotes">Quotes</a></li>
                    <li><a href="#memes">Memes</a></li>
                    <li><a href="#games">Games</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <!-- Product Detail Section -->
        <div class="product-detail fade-in">
            <div class="product-image-container">
                <img src="<?= $current_product['image'] ?>" alt="<?= $current_product['title'] ?>" class="product-image">
            </div>
            
            <div class="product-info">
                <h1 class="product-title"><?= $current_product['title'] ?></h1>
                <div class="product-price"><?= $current_product['price'] ?></div>
                
                <div class="product-rating">
                    <div class="stars">
                        <?php 
                        $rating = $current_product['rating'];
                        for($i = 1; $i <= 5; $i++) {
                            if($i <= floor($rating)) {
                                echo '<i class="fas fa-star"></i>';
                            } elseif($i <= ceil($rating)) {
                                echo '<i class="fas fa-star-half-alt"></i>';
                            } else {
                                echo '<i class="far fa-star"></i>';
                            }
                        }
                        ?>
                    </div>
                    <span class="rating-text"><?= $current_product['rating'] ?> (<?= $current_product['reviews'] ?> reviews)</span>
                </div>

                <p class="product-description"><?= $current_product['description'] ?></p>

                <div class="product-features">
                    <h3 class="features-title">Key Features</h3>
                    <ul class="features-list">
                        <?php foreach($current_product['features'] as $feature): ?>
                            <li><?= $feature ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="product-actions">
                    <a href="<?= $current_product['link'] ?>" class="futuristic-btn">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </a>
                    <button class="futuristic-btn btn-secondary">
                        <i class="fas fa-heart"></i> Add to Wishlist
                    </button>
                </div>
            </div>
        </div>
        <!-- From Uiverse.io by Mohammad-Rahme-576 --> 
<div class="tooltip-container">
  <div class="button-content">
    <span class="text">Share</span>
    <svg
      class="share-icon"
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 24 24"
      width="24"
      height="24"
    >
      <path
        d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92s2.92-1.31 2.92-2.92c0-1.61-1.31-2.92-2.92-2.92zM18 4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zM6 13c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm12 7.02c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1z"
      ></path>
    </svg>
  </div>
  <div class="tooltip-content">
    <div class="social-icons">
      <a href="#" class="social-icon twitter">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
          width="24"
          height="24"
        >
          <path
            d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"
          ></path>
        </svg>
      </a>
      <a href="#" class="social-icon facebook">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
          width="24"
          height="24"
        >
          <path
            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"
          ></path>
        </svg>
      </a>
      <a href="#" class="social-icon linkedin">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
          width="24"
          height="24"
        >
          <path
            d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"
          ></path>
        </svg>
      </a>
    </div>
  </div>
</div>


        <!-- Product Meta Information -->
        <div class="product-meta fade-in">
            <div class="meta-item">
                <div class="meta-label">Brand</div>
                <div class="meta-value"><?= $current_product['brand'] ?></div>
            </div>
            <div class="meta-item">
                <div class="meta-label">Category</div>
                <div class="meta-value"><?= $current_product['category'] ?></div>
            </div>
            <div class="meta-item">
                <div class="meta-label">Rating</div>
                <div class="meta-value"><?= $current_product['rating'] ?>/5</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">Reviews</div>
                <div class="meta-value"><?= $current_product['reviews'] ?></div>
            </div>
        </div>
    </div>

    <script>
        // Scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        // Observe all fade-in elements
        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });

        // Enhanced floating objects animation
        document.querySelectorAll('.floating-object').forEach((obj, index) => {
            obj.style.animationDelay = `${index * -5}s`;
            obj.style.animationDuration = `${20 + index * 5}s`;
        });
    </script>
</body>
</html>
