<?php include_once __DIR__ . '/includes/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - MoodBuddy</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>




        .main-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 3.5rem;
            font-weight: 900;
            background: var(--metallic-shine);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 3s ease infinite;
            margin-bottom: 10px;
        }

        @keyframes shimmer {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .subtitle {
            font-size: 1.2rem;
            color: var(--text-secondary);
            margin-bottom: 30px;
        }

        /* Navigation */
        .nav-tabs {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 40px;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        .nav-tab {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid var(--border-light);
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .nav-tab:hover {
            background: var(--card-bg);
            color: var(--text-primary);
            transform: translateY(-2px);
        }

        .nav-tab.active {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 5px 15px var(--glow-pink);
        }

        /* FAQ Grid */
        .faq-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 50px;
            padding: 40px;
        }

        .futuristic-card {
            background: var(--card-bg);
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(6, 182, 212, 0.2);
            position: relative;
            overflow: hidden;
            border: 2px solid transparent;
            background-clip: padding-box;
            transition: all 0.3s ease;
        }

        .futuristic-card::before {
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
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .futuristic-card:hover::before {
            opacity: 1;
        }

        .futuristic-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(6, 182, 212, 0.3);
        }

        .faq-category {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .faq-category i {
            font-size: 1.3rem;
        }

        .faq-item {
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border-light);
            padding-bottom: 15px;
        }

        .faq-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .faq-question {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--text-primary);
            margin-bottom: 10px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: color 0.3s ease;
        }

        .faq-question:hover {
            color: var(--primary-color);
        }

        .faq-answer {
            color: var(--text-secondary);
            line-height: 1.6;
            font-size: 0.95rem;
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .faq-answer.active {
            max-height: 500px;
            padding-top: 10px;
        }

        .faq-toggle {
            background: var(--primary-gradient);
            color: white;
            border: none;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.3s ease;
        }

        .faq-toggle:hover {
            transform: scale(1.1);
        }

        /* Search Section */
        .search-section {
            max-width: 600px;
            margin: 0 auto 50px auto;
        }

        .search-container {
            position: relative;
        }

        .search-input {
            width: 100%;
            background: var(--card-bg);
            border: 2px solid var(--border-light);
            border-radius: 25px;
            padding: 15px 50px 15px 25px;
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 20px var(--glow-blue);
        }

        .search-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 1.2rem;
        }

        /* Contact Section */
        .contact-section {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            margin-top: 50px;
            border: 2px solid transparent;
            background-clip: padding-box;
            position: relative;
            overflow: hidden;
            width: 90%;
            margin-left: 5%;
        }

        .contact-section::before {
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

        .contact-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 2rem;
            margin-bottom: 20px;
            background: var(--metallic-shine);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .contact-text {
            color: var(--text-secondary);
            margin-bottom: 30px;
            font-size: 1.1rem;
        }

        .contact-btn {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 15px 35px;
            font-weight: 700;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .contact-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px var(--glow-pink);
        }

    

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-title {
                font-size: 2.5rem;
            }
            
            .faq-grid {
                grid-template-columns: 1fr;
            }
            
            .nav-tabs {
                flex-direction: column;
                align-items: center;
            }
            
            .nav-tab {
                width: 200px;
                text-align: center;
            }
            
            .contact-section {
                padding: 30px 20px;
            }
        }

        @media (max-width: 480px) {
            .main-title {
                font-size: 2rem;
            }
            
            .container {
                padding: 10px;
            }
            
            .futuristic-card {
                padding: 20px;
            }
        }

        /* Back to Top */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--primary-gradient);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            opacity: 0;
            visibility: hidden;
            box-shadow: 0 5px 15px var(--glow-pink);
        }

        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            transform: translateY(-3px);
        }
    </style>
</head>
<body>

            <div class="hero-section">
            <h1 class="hero-title">MOODBUDDY FAQ</h1>
            <p class="her-subtitle">Find answers to commonly asked questions about your mental wellness companion</p>
            </div>
        

        <!-- Navigation Tabs -->
        <div class="nav-tabs">
            <div class="nav-tab active" data-category="all">All Questions</div>
            <div class="nav-tab" data-category="getting-started">Getting Started</div>
            <div class="nav-tab" data-category="features">Features</div>
            <div class="nav-tab" data-category="games">Games & Activities</div>
            <div class="nav-tab" data-category="technical">Technical Support</div>
        </div>

        <!-- Search Section -->
        <div class="search-section">
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Search for answers...">
                <div class="search-icon"><i class="fas fa-search"></i></div>
            </div>
        </div>

        <!-- FAQ Grid -->
        <div class="faq-grid">
            <!-- Getting Started Category -->
            <div class="futuristic-card" data-category="getting-started">
                <div class="faq-category">
                    <span>🚀</span> Getting Started
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        How do I create a MoodBuddy account?
                        <button class="faq-toggle">+</button>
                    </div>
                    <div class="faq-answer">
                        Creating a MoodBuddy account is simple! Click on the "Sign Up" button, enter your email address, create a secure password, and complete your profile. You can also sign up using your Google or Facebook account for faster access.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Is MoodBuddy free to use?
                        <button class="faq-toggle">+</button>
                    </div>
                    <div class="faq-answer">
                        Yes! MoodBuddy offers a comprehensive free version with access to mood tracking, basic games, and community features. We also offer Premium subscription with additional features like personalized therapy sessions and advanced analytics.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        How do I set up my daily mood tracking?
                        <button class="faq-toggle">+</button>
                    </div>
                    <div class="faq-answer">
                        Navigate to the "Mood Tracker" section in the app. You can set daily reminders, choose from various mood indicators, and add notes about your day. The app will help you identify patterns and triggers over time.
                    </div>
                </div>
            </div>

            <!-- Features Category -->
            <div class="futuristic-card" data-category="features">
                <div class="faq-category">
                    <span>💫</span> Features
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        What mental health resources does MoodBuddy offer?
                        <button class="faq-toggle">+</button>
                    </div>
                    <div class="faq-answer">
                        MoodBuddy provides guided meditation sessions, breathing exercises, cognitive behavioral therapy techniques, mood tracking, journaling features, and access to a supportive community. We also offer crisis resources and professional therapist matching.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        How does the mood-based game recommendation work?
                        <button class="faq-toggle">+</button>
                    </div>
                    <div class="faq-answer">
                        Our AI analyzes your current mood and suggests games specifically designed to help improve your mental state. For example, calming games for anxiety or energizing games for low mood. The system learns from your preferences over time.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Can I connect with friends on MoodBuddy?
                        <button class="faq-toggle">+</button>
                    </div>
                    <div class="faq-answer">
                        Yes! You can create private groups with friends, share achievements (with permission), and participate in community challenges. All social features are designed with privacy and mental wellness in mind.
                    </div>
                </div>
            </div>

            <!-- Games Category -->
            <div class="futuristic-card" data-category="games">
                <div class="faq-category">
                    <span>🎮</span> Games & Activities
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        How do games help with mental wellness?
                        <button class="faq-toggle">+</button>
                    </div>
                    <div class="faq-answer">
                        Our games are designed by mental health professionals to help with various aspects of wellness: stress reduction, focus improvement, mood elevation, and anxiety management. They incorporate principles of mindfulness and cognitive therapy.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Can I suggest new games for MoodBuddy?
                        <button class="faq-toggle">+</button>
                    </div>
                    <div class="faq-answer">
                        Absolutely! We love hearing from our community. Use the "Suggest a Feature" option in the app settings or email us at suggestions@moodbuddy.app. Our team reviews all suggestions regularly.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Are there multiplayer wellness games?
                        <button class="faq-toggle">+</button>
                    </div>
                    <div class="faq-answer">
                        Yes, we offer cooperative multiplayer games designed to build connections and practice social skills in a safe environment. These include team meditation challenges and collaborative puzzle games.
                    </div>
                </div>
            </div>

            <!-- Technical Support Category -->
            <div class="futuristic-card" data-category="technical">
                <div class="faq-category">
                    <span>🔧</span> Technical Support
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        The app is running slow. What can I do?
                        <button class="faq-toggle">+</button>
                    </div>
                    <div class="faq-answer">
                        Try these steps: 1) Restart the app 2) Check your internet connection 3) Clear app cache in settings 4) Update to the latest version 5) Ensure your device has sufficient storage. If issues persist, contact our support team.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        How do I reset my password?
                        <button class="faq-toggle">+</button>
                    </div>
                    <div class="faq-answer">
                        Click "Forgot Password" on the login screen. Enter your registered email address, and we'll send you a secure link to reset your password. The link expires in 24 hours for security.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Is my data secure and private?
                        <button class="faq-toggle">+</button>
                    </div>
                    <div class="faq-answer">
                        Your privacy is our top priority. We use end-to-end encryption, never sell your data, and comply with global privacy regulations (GDPR, HIPAA). You can download or delete your data anytime in settings.
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="contact-section">
            <h2 class="contact-title">Still Need Help?</h2>
            <p class="contact-text">Can't find the answer you're looking for? Our support team is here to help you with any questions or concerns.</p>
            <a href="contact.php">
            <button class="contact-btn">
                 Contact Support
            </button>
            </a>
        </div>


        <!-- Back to Top Button -->
        <div class="back-to-top">↑</div>
    </div>
    <?php include_once __DIR__ . '/includes/footer.php'; ?>
    <script>
        // FAQ Toggle Functionality
        document.querySelectorAll('.faq-toggle').forEach(toggle => {
            toggle.addEventListener('click', function() {
                const answer = this.parentElement.nextElementSibling;
                const isActive = answer.classList.contains('active');
                
                // Close all other answers
                document.querySelectorAll('.faq-answer').forEach(item => {
                    item.classList.remove('active');
                });
                document.querySelectorAll('.faq-toggle').forEach(btn => {
                    btn.textContent = '+';
                });
                
                // Toggle current answer
                if (!isActive) {
                    answer.classList.add('active');
                    this.textContent = '−';
                }
            });
        });

        // Category Filtering
        document.querySelectorAll('.nav-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                const category = this.getAttribute('data-category');
                
                // Update active tab
                document.querySelectorAll('.nav-tab').forEach(t => {
                    t.classList.remove('active');
                });
                this.classList.add('active');
                
                // Filter FAQ cards
                document.querySelectorAll('.futuristic-card').forEach(card => {
                    if (category === 'all' || card.getAttribute('data-category') === category) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Search Functionality
        const searchInput = document.querySelector('.search-input');
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            document.querySelectorAll('.futuristic-card').forEach(card => {
                const questions = card.querySelectorAll('.faq-question');
                let hasMatch = false;
                
                questions.forEach(question => {
                    const questionText = question.textContent.toLowerCase();
                    if (questionText.includes(searchTerm)) {
                        hasMatch = true;
                        // Highlight matching text
                        question.innerHTML = question.textContent.replace(
                            new RegExp(searchTerm, 'gi'),
                            match => `<span style="background: var(--glow-blue); padding: 2px 4px; border-radius: 4px;">${match}</span>`
                        );
                    }
                });
                
                card.style.display = hasMatch ? 'block' : 'none';
            });
        });

        // Back to Top Functionality
        const backToTop = document.querySelector('.back-to-top');
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        });

        backToTop.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Contact Button Animation
        document.querySelector('.contact-btn').addEventListener('click', function() {
            Swal.fire({
                title: 'Contact Support',
                html: `
                    <p>We're here to help you!</p>
                    <div style="text-align: left; margin-top: 20px;">
                        <p>📧 Email: support@moodbuddy.app</p>
                        <p>📞 Phone: +1 (555) 123-HELP</p>
                        <p>💬 Live Chat: Available in app</p>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'Got it!',
                background: 'var(--card-bg)',
                color: 'var(--text-primary)',
                confirmButtonColor: 'var(--primary-color)'
            });
        });

        // Add some interactive animations
        document.querySelectorAll('.futuristic-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    </script>
</body>
</html>