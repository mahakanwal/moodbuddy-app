<?php include_once __DIR__ . '/includes/header.php'; 
// Get mood from POST or default to 'happy'
$mood = strtolower($_SESSION['mood'] ?? 'happy');

// Mood -> Subreddit mapping with fallbacks
$subs = [
    'happy' => 'wholesomememes',
    'sad' => 'meirl',
    'angry' => 'funny',
    'anxious' => 'wholesomememes',
    'excited' => 'memes',
    'calm' => 'wholesomememes'
];

$subreddit = $subs[$mood] ?? 'memes';

// API URL
$url = "https://meme-api.com/gimme/$subreddit/12";

// Initialize memes array
$memes = [];

// Try to fetch memes with error handling
try {
    $ctx = stream_context_create(['http' => ['timeout' => 6, 'header' => "User-Agent: MoodBuddy/1.0\r\n"]]);
    $response = @file_get_contents($url, false, $ctx);
    
    if ($response === FALSE) {
        throw new Exception("Failed to connect to meme API");
    }
    
    $data = json_decode($response, true);
    
    // Check if we have memes in the response
    if (isset($data['memes']) && is_array($data['memes'])) {
        foreach ($data['memes'] as $m) {
            if (isset($m['url'])) {
                $memes[] = ['image' => $m['url']];
            }
        }
    } else if (isset($data['url'])) {
        // Handle case where API returns a single meme directly
        $memes[] = ['image' => $data['url']];
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}

// If no memes found, use fallback local memes
if (empty($memes)) {
    $memes = [
        ['image' => 'fallback1.jpg'],
        ['image' => 'fallback2.jpg'],
        ['image' => 'fallback3.jpg'],
        ['image' => 'fallback4.jpg'],
        ['image' => 'fallback5.jpg'],
        ['image' => 'fallback6.jpg']
    ];
}
?>

<!-- Hero Section -->
<section class="hero-section">
    <h1 class="hero-title">Memes For Your <?= ucfirst($mood) ?> Mood </h1>
    <p class="hero-subtitle">Discover personalized recommendations crafted by AI for your current vibe</p>
</section>

<!-- Memes Section -->
<section class="fade-in">
    <h2 class="section-title">Mood Boosters</h2>
    
    <?php if (isset($error)): ?>
        <div class="error-message">
            <p>Couldn't fetch memes at the moment. Showing fallback content.</p>
            <p><small>Error: <?= $error ?></small></p>
        </div>
    <?php endif; ?>
    
    <div class="card-grid">
        <?php foreach($memes as $meme): ?>
            <div class="futuristic-card">
                <img src="<?= $meme['image'] ?>" alt="Mood Meme" class="card-image" 
                     onerror="this.src='fallback.jpg'; this.alt='Fallback Meme'">
            </div>
        <?php endforeach; ?>
    </div>
</section>

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