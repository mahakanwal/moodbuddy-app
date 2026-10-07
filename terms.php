<?php include_once __DIR__ . '/includes/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service - MoodBuddy</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
    

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--bg-darker);
            color: var(--text-primary);
            font-family: 'Exo 2', sans-serif;
            line-height: 1.6;
            min-height: 100vh;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(6, 182, 212, 0.1) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(139, 92, 246, 0.1) 0%, transparent 20%),
                radial-gradient(circle at 50% 50%, rgba(236, 72, 153, 0.05) 0%, transparent 30%);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .hero-section {
            text-align: center;
            padding: 60px 20px 40px;
            /* background: rgba(15, 23, 42, 0.7); */
            border-radius: 20px;
            margin-bottom: 30px;
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--primary-gradient);
            opacity: 0.05;
            z-index: -1;
        }

        .hero-title {
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

        .hero-subtitle {
            font-size: 1.2rem;
            color: var(--text-secondary);
            margin-bottom: 30px;
        }

        .last-updated {
            background: var(--card-bg);
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 0.9rem;
            color: var(--text-secondary);
            border: 1px solid var(--border-light);
        }

        /* Navigation Tabs */
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

        /* Content Sections */
        .content-section {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            border: 2px solid transparent;
            background-clip: padding-box;
            position: relative;
            overflow: hidden;
        }

        .content-section::before {
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
            opacity: 0.3;
        }

        .section-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 2rem;
            margin-bottom: 25px;
            background: var(--metallic-shine);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .section-title i {
            font-size: 1.8rem;
        }

        .terms-point {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-light);
        }

        .terms-point:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .point-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .point-content {
            color: var(--text-secondary);
            line-height: 1.7;
            margin-left: 30px;
        }

        .point-content ul {
            margin-left: 20px;
            margin-top: 10px;
        }

        .point-content li {
            margin-bottom: 8px;
        }

        .warning-box {
            background: rgba(236, 72, 153, 0.1);
            border-left: 4px solid var(--accent-color);
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .info-box {
            background: rgba(6, 182, 212, 0.1);
            border-left: 4px solid var(--primary-color);
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .acceptance-section {
            text-align: center;
            padding: 30px;
            background: rgba(15, 23, 42, 0.5);
            border-radius: 15px;
            margin: 40px 0;
            border: 2px solid var(--border-light);
        }

        .acceptance-btn {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 15px 40px;
            font-weight: 700;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.1rem;
            margin-top: 20px;
        }

        .acceptance-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px var(--glow-pink);
        }

        .contact-info {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            background: rgba(15, 23, 42, 0.5);
            border-radius: 10px;
            border: 1px solid var(--border-light);
        }

        .contact-item i {
            color: var(--primary-color);
            font-size: 1.2rem;
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
            z-index: 1000;
        }

        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            transform: translateY(-3px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .nav-tabs {
                flex-direction: column;
                align-items: center;
            }
            
            .nav-tab {
                width: 200px;
                text-align: center;
            }
            
            .content-section {
                padding: 30px 20px;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .container {
                padding: 10px;
            }
            
            .section-title {
                font-size: 1.6rem;
            }
            
            .acceptance-section {
                padding: 20px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="hero-section">
            <h1 class="hero-title">TERMS OF SERVICE</h1>
            <p class="hero-subtitle">Please read these terms carefully before using MoodBuddy</p>
            <div class="last-updated">
                <i class="fas fa-clock"></i> Last Updated: October 28, 2023
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="nav-tabs">
            <div class="nav-tab active" data-section="agreement">Agreement</div>
            <div class="nav-tab" data-section="accounts">Accounts</div>
            <div class="nav-tab" data-section="content">Content & Conduct</div>
            <div class="nav-tab" data-section="subscriptions">Subscriptions</div>
            <div class="nav-tab" data-section="liability">Liability</div>
        </div>

        <!-- Agreement Section -->
        <div class="content-section" id="agreement">
            <h2 class="section-title">
                <i class="fas fa-handshake"></i> Agreement to Terms
            </h2>
            
            <div class="terms-point">
                <div class="point-title">
                    <i class="fas fa-file-contract"></i> Acceptance of Terms
                </div>
                <div class="point-content">
                    <p>By accessing or using MoodBuddy's website, mobile applications, and services (collectively, the "Services"), you agree to be bound by these Terms of Service and our Privacy Policy. If you disagree with any part of these terms, you may not access our Services.</p>
                    
                    <div class="warning-box">
                        <p><strong>Important Medical Disclaimer:</strong> MoodBuddy is not a substitute for professional medical advice, diagnosis, or treatment. Always seek the advice of your physician or qualified health provider with any questions you may have regarding a medical condition.</p>
                    </div>
                </div>
            </div>

            <div class="terms-point">
                <div class="point-title">
                    <i class="fas fa-user-check"></i> Eligibility
                </div>
                <div class="point-content">
                    <p>To use our Services, you must:</p>
                    <ul>
                        <li>Be at least 13 years of age (or the age of digital consent in your jurisdiction)</li>
                        <li>Have the legal capacity to enter into binding contracts</li>
                        <li>Not be prohibited from receiving our Services under applicable laws</li>
                        <li>Provide accurate and complete registration information</li>
                    </ul>
                    <p>Users between 13-18 years of age must have parental or guardian consent to use our Services.</p>
                </div>
            </div>

            <div class="terms-point">
                <div class="point-title">
                    <i class="fas fa-sync-alt"></i> Changes to Terms
                </div>
                <div class="point-content">
                    <p>We may modify these Terms at any time. We will notify you of significant changes through:</p>
                    <ul>
                        <li>In-app notifications</li>
                        <li>Email communications</li>
                        <li>Updates to this page with a new "Last Updated" date</li>
                    </ul>
                    <p>Continued use of our Services after changes constitutes acceptance of the modified Terms.</p>
                </div>
            </div>
        </div>

        <!-- Accounts Section -->
        <div class="content-section" id="accounts" style="display: none;">
            <h2 class="section-title">
                <i class="fas fa-user-circle"></i> User Accounts
            </h2>
            
            <div class="terms-point">
                <div class="point-title">
                    <i class="fas fa-key"></i> Account Creation
                </div>
                <div class="point-content">
                    <p>When creating a MoodBuddy account, you agree to:</p>
                    <ul>
                        <li>Provide accurate, current, and complete information</li>
                        <li>Maintain and promptly update your account information</li>
                        <li>Maintain the security of your password and accept all risks of unauthorized access</li>
                        <li>Notify us immediately of any unauthorized use of your account</li>
                        <li>Take responsibility for all activities that occur under your account</li>
                    </ul>
                </div>
            </div>

            <div class="terms-point">
                <div class="point-title">
                    <i class="fas fa-ban"></i> Prohibited Activities
                </div>
                <div class="point-content">
                    <p>You may not use our Services to:</p>
                    <ul>
                        <li>Violate any applicable laws or regulations</li>
                        <li>Infringe upon intellectual property rights</li>
                        <li>Harass, abuse, or harm another person</li>
                        <li>Transmit viruses or malicious code</li>
                        <li>Attempt to gain unauthorized access to our systems</li>
                        <li>Interfere with or disrupt the integrity or performance of our Services</li>
                        <li>Use automated systems to access our Services without permission</li>
                    </ul>
                </div>
            </div>

            <div class="terms-point">
                <div class="point-title">
                    <i class="fas fa-user-slash"></i> Account Termination
                </div>
                <div class="point-content">
                    <p>We reserve the right to suspend or terminate your account if:</p>
                    <ul>
                        <li>You violate these Terms of Service</li>
                        <li>You create risk or possible legal exposure for us</li>
                        <li>Your account has been inactive for an extended period</li>
                        <li>We decide to discontinue the Services in your region</li>
                    </ul>
                    <p>You may terminate your account at any time through your account settings.</p>
                </div>
            </div>
        </div>

        <!-- Content & Conduct Section -->
        <div class="content-section" id="content" style="display: none;">
            <h2 class="section-title">
                <i class="fas fa-comments"></i> Content & Community Conduct
            </h2>
            
            <div class="terms-point">
                <div class="point-title">
                    <i class="fas fa-edit"></i> User Content
                </div>
                <div class="point-content">
                    <p>You retain ownership of any content you create, share, or store using our Services ("User Content"). By submitting User Content, you grant us a worldwide, non-exclusive, royalty-free license to use, store, display, and distribute your content solely for the purpose of operating and improving our Services.</p>
                    
                    <div class="info-box">
                        <p><strong>Community Guidelines:</strong> Our community features are designed to be supportive spaces. Be respectful, kind, and considerate of others' experiences and perspectives.</p>
                    </div>
                </div>
            </div>

            <div class="terms-point">
                <div class="point-title">
                    <i class="fas fa-exclamation-triangle"></i> Prohibited Content
                </div>
                <div class="point-content">
                    <p>You may not post content that:</p>
                    <ul>
                        <li>Is illegal, fraudulent, or promotes illegal activities</li>
                        <li>Contains hate speech, threats, or harassment</li>
                        <li>Violates others' privacy or intellectual property rights</li>
                        <li>Contains sexually explicit material</li>
                        <li>Promotes self-harm, suicide, or dangerous behavior</li>
                        <li>Provides unauthorized medical or mental health advice</li>
                        <li>Contains false or misleading information</li>
                    </ul>
                    <p>We reserve the right to remove any content that violates these guidelines.</p>
                </div>
            </div>

            <div class="terms-point">
                <div class="point-title">
                    <i class="fas fa-gavel"></i> Content Moderation
                </div>
                <div class="point-content">
                    <p>We may, but have no obligation to, monitor and review User Content. We have the right to:</p>
                    <ul>
                        <li>Remove or edit any User Content</li>
                        <li>Suspend or terminate accounts for violations</li>
                        <li>Cooperate with legal authorities and third parties</li>
                        <li>Disclose information to comply with legal obligations</li>
                    </ul>
                    <p>If you believe content violates these Terms, please report it through our in-app reporting system.</p>
                </div>
            </div>
        </div>

        <!-- Subscriptions Section -->
        <div class="content-section" id="subscriptions" style="display: none;">
            <h2 class="section-title">
                <i class="fas fa-crown"></i> Premium Subscriptions
            </h2>
            
            <div class="terms-point">
                <div class="point-title">
                    <i class="fas fa-shopping-cart"></i> Subscription Plans
                </div>
                <div class="point-content">
                    <p>MoodBuddy offers both free and premium subscription plans:</p>
                    <ul>
                        <li><strong>Free Plan:</strong> Access to basic features including mood tracking, community support, and limited games</li>
                        <li><strong>Premium Plan:</strong> Additional features including personalized therapy sessions, advanced analytics, and unlimited games</li>
                    </ul>
                    <p>We reserve the right to modify subscription fees with 30 days' notice to current subscribers.</p>
                </div>
            </div>

            <div class="terms-point">
                <div class="point-title">
                    <i class="fas fa-credit-card"></i> Billing & Payments
                </div>
                <div class="point-content">
                    <p>By purchasing a subscription, you agree to:</p>
                    <ul>
                        <li>Provide accurate billing information</li>
                        <li>Pay all charges at the prices then in effect</li>
                        <li>Authorize us to charge your chosen payment provider</li>
                        <li>Pay any applicable taxes</li>
                    </ul>
                    <p>Subscriptions automatically renew until cancelled. You can cancel at any time through your account settings.</p>
                </div>
            </div>

            <div class="terms-point">
                <div class="point-title">
                    <i class="fas fa-undo"></i> Refunds & Cancellations
                </div>
                <div class="point-content">
                    <p>Our refund policy includes:</p>
                    <ul>
                        <li>You may cancel your subscription at any time</li>
                        <li>Cancellation will take effect at the end of the current billing cycle</li>
                        <li>No refunds for partial subscription periods</li>
                        <li>Refunds may be provided at our discretion in exceptional circumstances</li>
                    </ul>
                    <div class="warning-box">
                        <p>If we terminate your subscription for violations of these Terms, you will not be eligible for a refund.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liability Section -->
        <div class="content-section" id="liability" style="display: none;">
            <h2 class="section-title">
                <i class="fas fa-balance-scale"></i> Liability & Disclaimers
            </h2>
            
            <div class="terms-point">
                <div class="point-title">
                    <i class="fas fa-stethoscope"></i> Medical Disclaimer
                </div>
                <div class="point-content">
                    <p><strong>MoodBuddy is not a medical service and does not provide medical advice.</strong></p>
                    <ul>
                        <li>Our Services are for informational and supportive purposes only</li>
                        <li>We are not a substitute for professional medical advice, diagnosis, or treatment</li>
                        <li>Never disregard professional medical advice or delay seeking it because of something you have read on our Services</li>
                        <li>If you think you may have a medical emergency, call your doctor or emergency services immediately</li>
                    </ul>
                </div>
            </div>

            <div class="terms-point">
                <div class="point-title">
                    <i class="fas fa-shield-alt"></i> Limitation of Liability
                </div>
                <div class="point-content">
                    <p>To the maximum extent permitted by law, MoodBuddy shall not be liable for:</p>
                    <ul>
                        <li>Any indirect, incidental, special, consequential, or punitive damages</li>
                        <li>Loss of profits, data, use, goodwill, or other intangible losses</li>
                        <li>Damages relating to your access, use, or inability to access or use the Services</li>
                        <li>Any conduct or content of any third party on the Services</li>
                        <li>Unauthorized access, use, or alteration of your content</li>
                    </ul>
                </div>
            </div>

            <div class="terms-point">
                <div class="point-title">
                    <i class="fas fa-gavel"></i> Governing Law
                </div>
                <div class="point-content">
                    <p>These Terms shall be governed by the laws of the State of California, without regard to its conflict of law provisions. Any disputes shall be resolved in the state or federal courts located in San Francisco, California.</p>
                    
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>legal@moodbuddy.app</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>+1 (555) 123-LEGAL</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>San Francisco, CA</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acceptance Section -->
        <div class="acceptance-section">
            <h3>Ready to Continue?</h3>
            <p>By clicking "I Accept", you acknowledge that you have read, understood, and agree to be bound by these Terms of Service and our Privacy Policy.</p>
            <button class="acceptance-btn" id="acceptTerms">
                <i class="fas fa-check-circle"></i> I Accept the Terms
            </button>
        </div>

        <!-- Back to Top Button -->
        <div class="back-to-top">↑</div>
    </div>

    <?php include_once __DIR__ . '/includes/footer.php'; ?>

    <script>
        // Navigation Tabs
        document.querySelectorAll('.nav-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                const section = this.getAttribute('data-section');
                
                // Update active tab
                document.querySelectorAll('.nav-tab').forEach(t => {
                    t.classList.remove('active');
                });
                this.classList.add('active');
                
                // Show selected section, hide others
                document.querySelectorAll('.content-section').forEach(sec => {
                    sec.style.display = 'none';
                });
                document.getElementById(section).style.display = 'block';
                
                // Scroll to top of section
                document.getElementById(section).scrollIntoView({ behavior: 'smooth' });
            });
        });

        // Terms Acceptance
        document.getElementById('acceptTerms').addEventListener('click', function() {
            Swal.fire({
                title: 'Terms Accepted!',
                html: `
                    <p>Thank you for accepting our Terms of Service.</p>
                    <div style="text-align: left; margin-top: 20px;">
                        <p>✅ You can now access all MoodBuddy features</p>
                        <p>✅ Your account is fully activated</p>
                        <p>✅ You understand your rights and responsibilities</p>
                    </div>
                `,
                icon: 'success',
                confirmButtonText: 'Continue to MoodBuddy',
                background: 'var(--card-bg)',
                color: 'var(--text-primary)',
                confirmButtonColor: 'var(--primary-color)'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to main app or dashboard
                    window.location.href = 'dashboard.php';
                }
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

        // Add interactive animations
        document.querySelectorAll('.terms-point').forEach(point => {
            point.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(5px)';
                this.style.transition = 'transform 0.3s ease';
            });
            
            point.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add visual feedback when sections are displayed
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.content-section').forEach(section => {
            section.style.opacity = '0';
            section.style.transform = 'translateY(20px)';
            section.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(section);
        });

        // Add highlight effect to important sections
        document.querySelectorAll('.warning-box, .info-box').forEach(box => {
            box.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.02)';
                this.style.transition = 'transform 0.3s ease';
            });
            
            box.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>