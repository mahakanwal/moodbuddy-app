<?php include_once __DIR__ . '/includes/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - MoodBuddy</title>
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

        .policy-point {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-light);
        }

        .policy-point:last-child {
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

        .highlight-box {
            background: rgba(6, 182, 212, 0.1);
            border-left: 4px solid var(--primary-color);
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: rgba(15, 23, 42, 0.5);
            border-radius: 10px;
            overflow: hidden;
        }

        .data-table th {
            background: var(--primary-gradient);
            padding: 15px;
            text-align: left;
            font-weight: 700;
        }

        .data-table td {
            padding: 15px;
            border-bottom: 1px solid var(--border-light);
        }

        .data-table tr:last-child td {
            border-bottom: none;
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
            
            .data-table {
                display: block;
                overflow-x: auto;
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
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="hero-section">
            <h1 class="hero-title">PRIVACY POLICY</h1>
            <p class="hero-subtitle">Your privacy and data security are our top priorities</p>
            <div class="last-updated">
                <i class="fas fa-clock"></i> Last Updated: October 28, 2023
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="nav-tabs">
            <div class="nav-tab active" data-section="overview">Overview</div>
            <div class="nav-tab" data-section="data-collection">Data Collection</div>
            <div class="nav-tab" data-section="data-usage">Data Usage</div>
            <div class="nav-tab" data-section="data-protection">Data Protection</div>
            <div class="nav-tab" data-section="your-rights">Your Rights</div>
        </div>

        <!-- Overview Section -->
        <div class="content-section" id="overview">
            <h2 class="section-title">
                <i class="fas fa-shield-alt"></i> Privacy Overview
            </h2>
            
            <div class="policy-point">
                <div class="point-title">
                    <i class="fas fa-heart"></i> Our Commitment
                </div>
                <div class="point-content">
                    <p>At MoodBuddy, we believe that mental wellness and data privacy go hand in hand. We are committed to protecting your personal information and being transparent about how we collect, use, and safeguard your data.</p>
                    
                    <div class="highlight-box">
                        <p><strong>Important:</strong> MoodBuddy is not a substitute for professional medical advice, diagnosis, or treatment. Always seek the advice of your physician or qualified health provider with any questions you may have regarding a medical condition.</p>
                    </div>
                </div>
            </div>

            <div class="policy-point">
                <div class="point-title">
                    <i class="fas fa-scroll"></i> Policy Scope
                </div>
                <div class="point-content">
                    <p>This Privacy Policy applies to all information collected through our website, mobile applications, and any related services (collectively, the "Services"). By using our Services, you agree to the collection and use of information in accordance with this policy.</p>
                </div>
            </div>
        </div>

        <!-- Data Collection Section -->
        <div class="content-section" id="data-collection" style="display: none;">
            <h2 class="section-title">
                <i class="fas fa-database"></i> Information We Collect
            </h2>
            
            <div class="policy-point">
                <div class="point-title">
                    <i class="fas fa-user-circle"></i> Personal Information
                </div>
                <div class="point-content">
                    <p>We collect information that you provide directly to us when you:</p>
                    <ul>
                        <li>Create an account</li>
                        <li>Complete your profile</li>
                        <li>Use our mood tracking features</li>
                        <li>Participate in community features</li>
                        <li>Contact our support team</li>
                    </ul>
                </div>
            </div>

            <div class="policy-point">
                <div class="point-title">
                    <i class="fas fa-chart-line"></i> Usage Information
                </div>
                <div class="point-content">
                    <p>We automatically collect certain information about your interaction with our Services:</p>
                    <ul>
                        <li>Device information (type, operating system, unique device identifiers)</li>
                        <li>Log data (IP address, browser type, pages visited)</li>
                        <li>Usage patterns and preferences</li>
                        <li>Game and activity participation</li>
                    </ul>
                </div>
            </div>

            <div class="policy-point">
                <div class="point-title">
                    <i class="fas fa-brain"></i> Sensitive Information
                </div>
                <div class="point-content">
                    <p>With your explicit consent, we may collect sensitive information including:</p>
                    <ul>
                        <li>Mood and emotional state data</li>
                        <li>Mental wellness journal entries</li>
                        <li>Meditation and breathing exercise usage</li>
                        <li>Therapy session preferences (if applicable)</li>
                    </ul>
                    <div class="highlight-box">
                        <p><strong>You control your data:</strong> You can choose what sensitive information to share and can delete any data at any time through your account settings.</p>
                    </div>
                </div>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Data Type</th>
                        <th>Purpose</th>
                        <th>Required</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Account Information</td>
                        <td>Service access and personalization</td>
                        <td>Yes</td>
                    </tr>
                    <tr>
                        <td>Mood Data</td>
                        <td>Wellness tracking and insights</td>
                        <td>Optional</td>
                    </tr>
                    <tr>
                        <td>Usage Analytics</td>
                        <td>Service improvement</td>
                        <td>Automatic</td>
                    </tr>
                    <tr>
                        <td>Community Content</td>
                        <td>Social features</td>
                        <td>Optional</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Data Usage Section -->
        <div class="content-section" id="data-usage" style="display: none;">
            <h2 class="section-title">
                <i class="fas fa-cogs"></i> How We Use Your Information
            </h2>
            
            <div class="policy-point">
                <div class="point-title">
                    <i class="fas fa-rocket"></i> Service Provision
                </div>
                <div class="point-content">
                    <p>We use your information to:</p>
                    <ul>
                        <li>Provide and maintain our Services</li>
                        <li>Personalize your experience and content</li>
                        <li>Process your transactions and subscriptions</li>
                        <li>Send you service-related communications</li>
                        <li>Provide customer support</li>
                    </ul>
                </div>
            </div>

            <div class="policy-point">
                <div class="point-title">
                    <i class="fas fa-chart-bar"></i> Analytics & Improvement
                </div>
                <div class="point-content">
                    <p>We use aggregated, anonymized data to:</p>
                    <ul>
                        <li>Understand how users interact with our Services</li>
                        <li>Improve our features and user experience</li>
                        <li>Develop new tools and resources</li>
                        <li>Conduct research on mental wellness trends</li>
                    </ul>
                    <div class="highlight-box">
                        <p><strong>Research Participation:</strong> We may invite you to participate in optional research studies. Your participation is always voluntary, and separate consent will be obtained.</p>
                    </div>
                </div>
            </div>

            <div class="policy-point">
                <div class="point-title">
                    <i class="fas fa-bell"></i> Communications
                </div>
                <div class="point-content">
                    <p>We may use your contact information to:</p>
                    <ul>
                        <li>Send you important updates about our Services</li>
                        <li>Provide wellness tips and resources</li>
                        <li>Notify you about new features</li>
                        <li>Respond to your inquiries</li>
                    </ul>
                    <p>You can opt-out of promotional communications at any time through your account settings or by using the unsubscribe link in our emails.</p>
                </div>
            </div>
        </div>

        <!-- Data Protection Section -->
        <div class="content-section" id="data-protection" style="display: none;">
            <h2 class="section-title">
                <i class="fas fa-lock"></i> Data Protection & Security
            </h2>
            
            <div class="policy-point">
                <div class="point-title">
                    <i class="fas fa-shield-check"></i> Security Measures
                </div>
                <div class="point-content">
                    <p>We implement comprehensive security measures to protect your data:</p>
                    <ul>
                        <li><strong>Encryption:</strong> All data is encrypted in transit and at rest using industry-standard protocols</li>
                        <li><strong>Access Controls:</strong> Strict access controls limit employee access to your personal information</li>
                        <li><strong>Regular Audits:</strong> We conduct regular security assessments and penetration testing</li>
                        <li><strong>Data Minimization:</strong> We only collect data necessary for providing our Services</li>
                    </ul>
                </div>
            </div>

            <div class="policy-point">
                <div class="point-title">
                    <i class="fas fa-user-shield"></i> Data Sharing
                </div>
                <div class="point-content">
                    <p>We do not sell your personal information to third parties. We may share your data in the following limited circumstances:</p>
                    <ul>
                        <li><strong>Service Providers:</strong> With trusted partners who help us operate our Services (under strict confidentiality agreements)</li>
                        <li><strong>Legal Requirements:</strong> When required by law or to protect our rights and safety</li>
                        <li><strong>Business Transfers:</strong> In connection with a merger, acquisition, or sale of assets</li>
                        <li><strong>With Your Consent:</strong> When you explicitly authorize us to share specific information</li>
                    </ul>
                </div>
            </div>

            <div class="policy-point">
                <div class="point-title">
                    <i class="fas fa-globe-americas"></i> International Transfers
                </div>
                <div class="point-content">
                    <p>Your information may be transferred to and processed in countries other than your own. We ensure appropriate safeguards are in place to protect your data in accordance with this Privacy Policy and applicable laws.</p>
                </div>
            </div>
        </div>

        <!-- Your Rights Section -->
        <div class="content-section" id="your-rights" style="display: none;">
            <h2 class="section-title">
                <i class="fas fa-user-check"></i> Your Privacy Rights
            </h2>
            
            <div class="policy-point">
                <div class="point-title">
                    <i class="fas fa-download"></i> Access & Control
                </div>
                <div class="point-content">
                    <p>You have the right to:</p>
                    <ul>
                        <li><strong>Access</strong> your personal information</li>
                        <li><strong>Correct</strong> inaccurate or incomplete data</li>
                        <li><strong>Delete</strong> your personal information</li>
                        <li><strong>Restrict</strong> or object to processing</li>
                        <li><strong>Port</strong> your data to another service</li>
                        <li><strong>Withdraw</strong> consent at any time</li>
                    </ul>
                    <p>You can exercise most of these rights directly through your account settings.</p>
                </div>
            </div>

            <div class="policy-point">
                <div class="point-title">
                    <i class="fas fa-cog"></i> Account Settings
                </div>
                <div class="point-content">
                    <p>Through your account settings, you can:</p>
                    <ul>
                        <li>View and edit your profile information</li>
                        <li>Manage your privacy preferences</li>
                        <li>Control notification settings</li>
                        <li>Download your data</li>
                        <li>Delete your account permanently</li>
                    </ul>
                </div>
            </div>

            <div class="policy-point">
                <div class="point-title">
                    <i class="fas fa-headset"></i> Contact Us
                </div>
                <div class="point-content">
                    <p>If you have any questions about your privacy rights or wish to exercise rights not available in your account settings, please contact our Privacy Team:</p>
                    
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>privacy@moodbuddy.app</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>+1 (555) 123-PRIVACY</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-clock"></i>
                            <span>Response within 48 hours</span>
                        </div>
                    </div>
                </div>
            </div>
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
        document.querySelectorAll('.policy-point').forEach(point => {
            point.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(5px)';
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

        // Add some visual feedback when sections are displayed
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
    </script>
</body>
</html>