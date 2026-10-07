<?php include_once __DIR__ . '/includes/header.php'; ?>
<?php
$cart = $_SESSION['cart'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - MoodBuddy</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>

        /* Main Content */
        .main-content {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        /* Checkout Container */
        .checkout-container {
            background: var(--card-bg);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(6, 182, 212, 0.1);
            margin-bottom: 2rem;
        }

        /* Enhanced section titles with metallic underline */
        .section-title {
            font-family: 'Orbitron', monospace;
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 2rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
            padding-top: 2rem;
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

        /* Order Summary */
        .order-summary {
            background: linear-gradient(135deg, rgba(6, 182, 212, 0.05) 0%, rgba(236, 72, 153, 0.05) 100%);
            padding: 2rem;
            border-radius: 15px;
            margin: 1.5rem;
            border: 2px solid transparent;
            background-clip: padding-box;
        }

        .order-summary h3 {
            font-family: 'Orbitron', monospace;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .order-summary ul {
            list-style: none;
            padding-left: 0;
            margin-bottom: 1.5rem;
        }

        .order-summary li {
            margin-bottom: 0.8rem;
            padding: 0.5rem;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            justify-content: space-between;
        }

        .order-total {
            font-family: 'Orbitron', monospace;
            font-size: 1.8rem;
            font-weight: 900;
            background: var(--metallic-shine);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 3s ease infinite;
            text-align: center;
            padding: 1rem;
            margin-top: 1rem;
            border-top: 2px solid var(--border-light);
        }

        /* Checkout Form */
        .checkout-form {
            padding: 2rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--border-light);
            border-radius: 12px;
            font-family: 'Exo 2', sans-serif;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus, .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 15px var(--glow-blue);
        }

        .form-group-full {
            grid-column: 1 / -1;
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .header-container {
                padding: 1rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .order-summary, .checkout-form {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>



    <!-- Main Content -->
    <main class="main-content">
        <h2 class="section-title">Checkout</h2>
        
        <div class="checkout-container">
            <?php if(empty($cart)): ?>
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart empty-cart-icon"></i>
                    <p class="empty-cart-text">Your cart is empty! Start shopping to add items.</p>
                    <a href="shop.php" class="futuristic-btn">
                        <i class="fas fa-arrow-left"></i> Continue Shopping
                    </a>
                </div>
            <?php else: ?>
                <!-- Order Summary -->
                <div class="order-summary">
                    <h3>Order Summary</h3>
                    <ul>
                    <?php
                    $total = 0;
                    foreach($cart as $item):
                        if (!isset($item['title']) || !isset($item['price']) || !isset($item['quantity'])) {
                            continue;
                        }
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                        <li>
                            <span><?= htmlspecialchars($item['title']) ?> x <?= $item['quantity'] ?></span>
                            <span>$<?= number_format($subtotal, 2) ?></span>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                    <div class="order-total">Total: $<?= number_format($total, 2) ?></div>
                </div>

                <!-- Checkout Form -->
                <form class="checkout-form" method="POST" action="https://api.web3forms.com/submit" id="checkoutForm">
                    <input type="hidden" name="access_key" value="385e26e5-1e29-442b-aa0c-6a18ce325c5f">
                    <input type="hidden" name="subject" value="New MoodBuddy Order">
                    <input type="hidden" name="redirect" value="checkout_success.php">

                    <!-- Honeypot field -->
                    <input type="text" name="website" style="display:none">

                    <!-- Order Details hidden -->
                    <input type="hidden" name="message" value="<?php
                    $orderMsg = "ORDER DETAILS:\n\n";
                    foreach($cart as $item) {
                        if (isset($item['title']) && isset($item['price']) && isset($item['quantity'])) {
                            $subtotal = $item['price'] * $item['quantity'];
                            $orderMsg .= "{$item['title']} x {$item['quantity']} = $" . number_format($subtotal, 2) . "\n";
                        }
                    }
                    $orderMsg .= "\nTotal: $" . number_format($total, 2);
                    echo htmlspecialchars($orderMsg);
                    ?>">

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="full_name"><i class="fas fa-user"></i> Full Name</label>
                            <input type="text" id="full_name" name="full_name" required>
                        </div>

                        <div class="form-group">
                            <label for="email"><i class="fas fa-envelope"></i> Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>

                        <div class="form-group">
                            <label for="phone"><i class="fas fa-phone"></i> Phone</label>
                            <input type="text" id="phone" name="phone" required>
                        </div>

                        <div class="form-group">
                            <label for="whatsapp"><i class="fab fa-whatsapp"></i> WhatsApp</label>
                            <input type="text" id="whatsapp" name="whatsapp" required>
                        </div>

                        <div class="form-group form-group-full">
                            <label for="address"><i class="fas fa-map-marker-alt"></i> Address</label>
                            <textarea id="address" name="address" rows="3" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="postal_code"><i class="fas fa-mail-bulk"></i> Postal Code</label>
                            <input type="text" id="postal_code" name="postal_code" required>
                        </div>

                        <div class="form-group">
                            <label for="city"><i class="fas fa-city"></i> City</label>
                            <input type="text" id="city" name="city" value="Karachi" readonly>
                        </div>
                    </div>

                    <button type="submit" class="futuristic-btn">
                        <i class="fas fa-credit-card"></i> Place Order
                    </button>
                </form>
            <?php endif; ?>
        </div>
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
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            submitBtn.disabled = true;
            
            // Create a temporary form for submission
            const formData = new FormData(this);
            
            // Send the form data using fetch
            fetch('https://api.web3forms.com/submit', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    Swal.fire({
                        title: 'Order Placed Successfully!',
                        text: 'Thank you for your order. You will receive a confirmation email shortly.',
                        icon: 'success',
                        confirmButtonColor: '#06b6d4',
                        confirmButtonText: 'Continue Shopping'
                    }).then(() => {
                        // Clear cart and redirect
                        window.location.href = 'shop.php';
                    });
                } else {
                    // Show error message
                    Swal.fire({
                        title: 'Submission Error',
                        text: 'There was an issue with your order. Please try again.',
                        icon: 'error',
                        confirmButtonColor: '#06b6d4'
                    });
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                // Show error message
                Swal.fire({
                    title: 'Network Error',
                    text: 'There was a network issue. Please check your connection and try again.',
                    icon: 'error',
                    confirmButtonColor: '#06b6d4'
                });
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
            
            // Prevent the default form submission
            return false;
        });
    </script>

</body>
</html>
