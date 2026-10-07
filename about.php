<?php include_once __DIR__ . '/includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <h1 class="hero-title">About MoodBuddy</h1>
        <p class="hero-subtitle">Your AI-powered companion for personalized mood-based recommendations</p>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <!-- About Content -->
        <section class="fade-in">
            <div class="about-content">
                <div class="about-text">
                    <p>Welcome to MoodBuddy 2027, where cutting-edge AI technology meets emotional wellness. Our mission is to enhance your daily life by providing personalized recommendations that align with your current emotional state.</p>
                    
                    <p>Founded in 2023, MoodBuddy has evolved from a simple mood tracker to a comprehensive emotional wellness platform. Our team of psychologists, data scientists, and designers work together to create an experience that's both scientifically grounded and delightfully engaging.</p>
                    
                    <p>Using advanced sentiment analysis and machine learning algorithms, MoodBuddy curates products, music, quotes, memes, and games specifically tailored to uplift, calm, or energize you based on your current mood.</p>
                </div>
                
                <div class="about-image fade-in">
                    <i class="fas fa-brain"></i>
                </div>
            </div>
        </section>

        <!-- Mission Section -->
        <section class="mission-section fade-in">
            <h2 class="section-title">Our Mission</h2>
            <p class="mission-text">To empower individuals to navigate their emotional landscape with confidence and joy, using AI-powered personalized recommendations that enhance wellbeing and daily life experiences.</p>
        </section>

        <!-- Stats Section -->
        <section class="fade-in">
            <h2 class="section-title">By The Numbers</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">500K+</div>
                    <div class="stat-label">Happy Users</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">10M+</div>
                    <div class="stat-label">Recommendations</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">95%</div>
                    <div class="stat-label">Satisfaction Rate</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">AI Support</div>
                </div>
            </div>
        </section>

        <!-- Team Section -->
        <section class="fade-in">
            <h2 class="section-title">Meet Our Team</h2>
            <div class="team-grid">
                <div class="team-card">
                    <img src="assets/img/mahi.jpeg" alt="Sarah Davis" class="team-image">
                    <h3 class="team-name">Maha Kanwal</h3>
                    <div class="team-role">CEO & Founder</div>
                    <p class="team-bio">Full Stack Developer & Visionary leader with 1+ years in AI and emotional intelligence technology.</p>
                    <a href="" class="futuristic-btn"><i class="fab fa-linkedin"></i> Connect</a>
                </div>
                
                <div class="team-card">
                    <img src="assets/img/author/author3.jpg" alt="Michael Rodriguez" class="team-image">
                    <h3 class="team-name">Fatima Amir</h3>
                    <div class="team-role">Chief AI Scientist</div>
                    <p class="team-bio">Full Stack Developer with hands on experience in FrontEnd Development.</p>
                    <a href="" class="futuristic-btn"><i class="fab fa-linkedin"></i> Connect</a>
                </div>
                
        </section>
    </main>

    <?php include_once __DIR__ . '/includes/footer.php'; ?>

    <script>
        // Mobile menu functionality
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileNav = document.getElementById('mobileNav');
        
        mobileMenuBtn.addEventListener('click', () => {
            mobileNav.classList.toggle('active');
        });
        
        // Custom cursor
        const customCursor = document.getElementById('customCursor');
        
        document.addEventListener('mousemove', (e) => {
            customCursor.style.left = e.clientX + 'px';
            customCursor.style.top = e.clientY + 'px';
        });
        
        // Make cursor smaller when hovering over clickable elements
        const clickableElements = document.querySelectorAll('a, button, .nav-link, .futuristic-btn, .mobile-nav-link, .social-icon');
        
        clickableElements.forEach(element => {
            element.addEventListener('mouseenter', () => {
                customCursor.style.transform = 'translate(-50%, -50%) scale(0.7)';
                customCursor.style.background = 'radial-gradient(circle at 50% 50%, #ff1493, #ff69b4)';
            });
            
            element.addEventListener('mouseleave', () => {
                customCursor.style.transform = 'translate(-50%, -50%) scale(1)';
                customCursor.style.background = 'radial-gradient(circle at 50% 50%, #ff69b4, #ff1493)';
            });
        });
        
        // Scroll animations
        const fadeElements = document.querySelectorAll('.fade-in');
        
        const fadeInOptions = {
            threshold: 0.3,
            rootMargin: "0px 0px -100px 0px"
        };
        
        const fadeInObserver = new IntersectionObserver((entries, fadeInObserver) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('visible');
                fadeInObserver.unobserve(entry.target);
            });
        }, fadeInOptions);
        
        fadeElements.forEach(element => {
            fadeInObserver.observe(element);
        });
    </script>
</body>
</html>