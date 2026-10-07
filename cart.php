<?php include_once __DIR__ . '/includes/header.php'; ?>
<?php
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Access Denied',
            text: 'Please login first to access the shop!',
            confirmButtonText: 'Login Now'
        }).then(() => {
            window.location.href = 'admin/index.php';
        });
    </script>
    ";
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle quantity update
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $id => $qty) {
        $id = intval($id);
        $qty = intval($qty);
        if ($qty <= 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id]['quantity'] = $qty;
        }
    }
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Cart Updated!',
            text: 'Your cart has been successfully updated.',
            showConfirmButton: false,
            timer: 2000
        });
        setTimeout(() => {
            window.location.href = 'cart.php';
        }, 2000);
    </script>
    ";
    exit;
}

// Handle remove single item
if (isset($_GET['remove'])) {
    $id = intval($_GET['remove']);
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'info',
            title: 'Item Removed!',
            text: 'The item has been removed from your cart.',
            showConfirmButton: false,
            timer: 2000
        });
        setTimeout(() => {
            window.location.href = 'cart.php';
        }, 2000);
    </script>
    ";
    exit;
}

// Clear cart
if (isset($_GET['clear_cart'])) {
    $_SESSION['cart'] = [];
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Cart Cleared!',
            text: 'All items have been removed from your cart.',
            showConfirmButton: false,
            timer: 2000
        });
        setTimeout(() => {
            window.location.href = 'cart.php';
        }, 2000);
    </script>
    ";
    exit;
}

// Calculate total and item count
$total = 0;
$cart_count = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id => $item) {
        if (isset($item['price']) && isset($item['quantity'])) {
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
            $cart_count += $item['quantity'];
            $_SESSION['cart'][$id]['subtotal'] = $subtotal;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cart - MoodBuddy</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
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


    
        /* Enhanced section titles with metallic underline */
        .section-title {
            font-family: 'Orbitron', monospace;
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 3rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: var(--metallic-shine);
            background-size: 300% 300%;
            border-radius: 2px;
            box-shadow: 0 0 15px var(--glow-blue);
            animation: shimmer 2s ease infinite;
        }

        /* Cart Table */
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--card-bg);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(6, 182, 212, 0.1);
            margin-bottom: 2rem;
        }

        .cart-table th, .cart-table td {
            padding: 1.5rem;
            text-align: left;
            border-bottom: 1px solid var(--border-light);
        }

        .cart-table th {
            /* background: var(--primary-gradient); */
            border: 2px solid var(--border-light);
            color: black;
            font-family: 'Orbitron', monospace;
            font-weight: 700;
        }

        .cart-table tr:last-child td {
            border-bottom: none;
        }

        .product-cell {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 12px;
            border: 2px solid transparent;
            background: linear-gradient(white, white) padding-box, var(--primary-gradient) border-box;
        }

        .product-info {
            display: flex;
            flex-direction: column;
        }

        .product-title {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .product-category {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .product-price {
            font-family: 'Orbitron', monospace;
            font-weight: 700;
            font-size: 1.2rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .quantity-input {
            width: 70px;
            padding: 0.75rem;
            border: 2px solid var(--border-light);
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
            font-family: 'Exo 2', sans-serif;
            transition: all 0.3s ease;
        }

        .quantity-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 15px var(--glow-blue);
        }

        .subtotal {
            font-family: 'Orbitron', monospace;
            font-weight: 700;
            font-size: 1.2rem;
        }

        /* Enhanced buttons with metallic gradient and glow */
        .futuristic-btn {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 0.875rem 2rem;
            border-radius: 25px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            position: relative;
            overflow: hidden;
            cursor: pointer;
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

        .remove-btn {
            background: transparent;
            border: 2px solid #f87171;
            color: #f87171;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .remove-btn:hover {
            background: #f87171;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(248, 113, 113, 0.4);
        }

        /* Cart Actions */
        .cart-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .cart-total {
            font-family: 'Orbitron', monospace;
            font-size: 1.8rem;
            font-weight: 900;
            background: var(--metallic-shine);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 3s ease infinite;
        }

        /* Empty Cart */
        .empty-cart {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(6, 182, 212, 0.1);
        }

        .empty-cart-icon {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .empty-cart-text {
            font-size: 1.2rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
        }

        /* Debug info */
        .debug-info {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            font-family: monospace;
            font-size: 0.9rem;
            color: #6b7280;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-container {
                padding: 1rem;
            }
            
            .nav-menu {
                display: none;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .cart-table {
                display: block;
                overflow-x: auto;
            }
            
            .product-cell {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .cart-actions {
                flex-direction: column;
                align-items: stretch;
            }
            
            .futuristic-btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Floating objects for background */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .floating-object {
            position: absolute;
            opacity: 0.3;
            animation: float 20s infinite linear;
            filter: drop-shadow(0 0 10px var(--glow-blue));
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
  </style>
</head>
<body>

    <!-- Main Content -->
    <main class="main-content">
        <h2 class="section-title">Your Shopping Cart</h2>
        
        <?php if(!empty($_SESSION['cart'])): ?>
        <form method="POST">
            <!-- Cart Items -->
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($_SESSION['cart'] as $id => $item): 
                        if (!isset($item['title']) || !isset($item['price']) || !isset($item['quantity'])) {
                            continue;
                        }
                        $subtotal = $item['price'] * $item['quantity'];
                    ?>
                    <tr>
                        <td>
                            <div class="product-cell">
                                <?php if (!empty($item['image'])): ?>
                                    <img src="admin/uploads/products/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="product-image">
                                <?php else: ?>
                                    <img src="https://placehold.co/100x100/06b6d4/ffffff?text=MB" alt="Product" class="product-image">
                                <?php endif; ?>
                                <div class="product-info">
                                    <div class="product-title"><?= htmlspecialchars($item['title']) ?></div>
                                    <div class="product-category"><?= isset($item['category']) ? htmlspecialchars($item['category']) : 'Mood Enhancement' ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="product-price">$<?= number_format($item['price'], 2) ?></td>
                        <td>
                            <input type="number" class="quantity-input" name="quantities[<?= $id ?>]" value="<?= $item['quantity'] ?>" min="1">
                        </td>
                        <td class="subtotal">$<?= number_format($subtotal, 2) ?></td>
                        <td>
                            <a href="cart.php?remove=<?= $id ?>" class="remove-btn">
                                <i class="fas fa-trash"></i> Remove
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Cart Actions -->
            <div class="cart-actions">
                <a href="shop.php" class="futuristic-btn">
                    <i class="fas fa-arrow-left"></i> Continue Shopping
                </a>
                <div class="cart-total">Total: $<?= number_format($total, 2) ?></div>
                <div>
                    <button type="submit" name="update_cart" class="futuristic-btn">
                        <i class="fas fa-sync"></i> Update Cart
                    </button>
                    <button type="button" class="futuristic-btn" onclick="goToCheckout()" style="margin-left: 10px;">
                        <i class="fas fa-credit-card"></i> Checkout
                    </button>
                </div>
            </div>
        </form>
        <?php else: ?>
            <div class="empty-cart">
                <i class="fas fa-shopping-cart empty-cart-icon"></i>
                <p class="empty-cart-text">Your cart is empty! Start shopping to add items.</p>
                <a href="shop.php" class="futuristic-btn">
                    <i class="fas fa-arrow-left"></i> Continue Shopping
                </a>
            </div>
        <?php endif; ?>
    </main>
     <!-- Modern Futuristic Footer -->
     <footer class="futuristic-footer fade-in">
        <div class="footer-container">
            <div class="footer-grid">
                <!-- About Section -->
                <div class="footer-section">
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
                <div class="footer-section">
                    <h3><i class="fas fa-link"></i> Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="index.html"><i class="fas fa-home"></i> Home</a></li>
                        <li><a href="about.html"><i class="fas fa-info-circle"></i> About Us</a></li>
                        <li><a href="shop.html"><i class="fas fa-shopping-bag"></i> Shop</a></li>
                        <li><a href="music.html"><i class="fas fa-music"></i> Music Library</a></li>
                        <li><a href="quotes.html"><i class="fas fa-quote-right"></i> Inspirational Quotes</a></li>
                        <li><a href="memes.html"><i class="fas fa-smile"></i> Mood Memes</a></li>
                        <li><a href="games.html"><i class="fas fa-gamepad"></i> Interactive Games</a></li>
                    </ul>
                </div>

                <!-- Support & Services -->
                <div class="footer-section">
                    <h3><i class="fas fa-headset"></i> Support & Services</h3>
                    <ul class="footer-links">
                        <li><a href="contact.html"><i class="fas fa-phone"></i> Contact Support</a></li>
                        <li><a href="faq.html"><i class="fas fa-question-circle"></i> FAQ</a></li>
                        <li><a href="help.html"><i class="fas fa-life-ring"></i> Help Center</a></li>
                        <li><a href="api.html"><i class="fas fa-code"></i> API Documentation</a></li>
                        <li><a href="feedback.html"><i class="fas fa-comment"></i> Send Feedback</a></li>
                        <li><a href="community.html"><i class="fas fa-users"></i> Community</a></li>
                        <li><a href="blog.html"><i class="fas fa-blog"></i> Blog</a></li>
                    </ul>
                </div>

                <!-- Newsletter Subscription -->
                <div class="footer-section">
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
    <script>
        function goToCheckout() {
            <?php if(!empty($_SESSION['cart'])): ?>
                window.location.href = 'checkout.php';
            <?php else: ?>
                Swal.fire({
                    title: 'Your cart is empty',
                    text: 'Please add products to your cart before proceeding to checkout.',
                    icon: 'warning',
                    confirmButtonColor: '#06b6d4',
                    confirmButtonText: 'Continue Shopping'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'shop.php';
                    }
                });
            <?php endif; ?>
        }

        // Remove confirmation
        document.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const href = this.getAttribute('href');
                Swal.fire({
                    title: 'Remove Item',
                    text: 'Are you sure you want to remove this item from your cart?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#06b6d4',
                    cancelButtonColor: '#ec4899',
                    confirmButtonText: 'Yes, remove it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                });
            });
        });
    </script>
</body>
</html>
