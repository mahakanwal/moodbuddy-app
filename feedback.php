<?php include_once __DIR__ . '/includes/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - MoodBuddy</title>
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

        /* Form Section */
        .form-section {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 50px;
            border: 2px solid transparent;
            background-clip: padding-box;
            position: relative;
            overflow: hidden;
        }

        .form-section::before {
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
            opacity: 0.7;
        }

        .section-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 2rem;
            margin-bottom: 30px;
            background: var(--metallic-shine);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-align: center;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            /* background: rgba(15, 23, 42, 0.7); */
            border: 1px solid var(--border-light);
            border-radius: 12px;
            padding: 15px 20px;
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: 'Exo 2', sans-serif;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 15px var(--glow-blue);
        }

        .form-textarea {
            min-height: 150px;
            resize: vertical;
        }

        .rating-container {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        .rating-star {
            font-size: 2rem;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .rating-star:hover, .rating-star.active {
            color: gold;
            transform: scale(1.1);
        }

        .submit-btn {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 15px 35px;
            font-weight: 700;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            display: block;
            margin: 30px auto 0;
            width: 200px;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px var(--glow-pink);
        }

        /* Feedback Display Section */
        .feedback-section {
            margin-top: 50px;
        }

        .feedback-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .feedback-card {
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

        .feedback-card::before {
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

        .feedback-card:hover::before {
            opacity: 1;
        }

        .feedback-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(6, 182, 212, 0.3);
        }

        .feedback-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .feedback-user {
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .feedback-date {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .feedback-rating {
            color: gold;
            margin-bottom: 10px;
        }

        .feedback-category {
            display: inline-block;
            background: var(--primary-gradient);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            margin-bottom: 15px;
        }

        .feedback-content {
            color: var(--text-secondary);
            line-height: 1.6;
        }

        .no-feedback {
            text-align: center;
            padding: 40px;
            color: var(--text-secondary);
            font-style: italic;
            grid-column: 1 / -1;
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
            
            .feedback-grid {
                grid-template-columns: 1fr;
            }
            
            .form-section {
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
            
            .feedback-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="hero-section">
            <h1 class="hero-title">SHARE YOUR FEEDBACK</h1>
            <p class="hero-subtitle">Help us improve MoodBuddy with your valuable insights</p>
        </div>

        <!-- Feedback Form Section -->
        <div class="form-section">
            <h2 class="section-title">Share Your Experience</h2>
            <form id="feedbackForm">
                <div class="form-group">
                    <label class="form-label" for="name">Your Name (Optional)</label>
                    <input type="text" id="name" class="form-input" placeholder="Enter your name">
                </div>

                <div class="form-group">
                    <label class="form-label" for="category">Feedback Category</label>
                    <select id="category" class="form-select">
                        <option value="">Select a category</option>
                        <option value="general">General Feedback</option>
                        <option value="feature-request">Feature Request</option>
                        <option value="bug-report">Bug Report</option>
                        <option value="user-interface">User Interface</option>
                        <option value="games">Games & Activities</option>
                        <option value="mood-tracking">Mood Tracking</option>
                        <option value="community">Community Features</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Rating</label>
                    <div class="rating-container">
                        <span class="rating-star" data-rating="1">★</span>
                        <span class="rating-star" data-rating="2">★</span>
                        <span class="rating-star" data-rating="3">★</span>
                        <span class="rating-star" data-rating="4">★</span>
                        <span class="rating-star" data-rating="5">★</span>
                    </div>
                    <input type="hidden" id="rating" value="0">
                </div>

                <div class="form-group">
                    <label class="form-label" for="feedback">Your Feedback</label>
                    <textarea id="feedback" class="form-textarea" placeholder="Tell us about your experience with MoodBuddy, what you like, what could be improved, or any suggestions you have..." required></textarea>
                </div>

                <button type="submit" class="submit-btn">Submit Feedback</button>
            </form>
        </div>

        <!-- Feedback Display Section -->
        <div class="feedback-section">
            <h2 class="section-title">Community Feedback</h2>
            <div class="feedback-grid" id="feedbackContainer">
                <!-- Feedback cards will be dynamically added here -->
                <div class="no-feedback" id="noFeedbackMessage">
                    <p>No feedback yet. Be the first to share your thoughts!</p>
                </div>
            </div>
        </div>

        <!-- Back to Top Button -->
        <div class="back-to-top">↑</div>
    </div>

    <?php include_once __DIR__ . '/includes/footer.php'; ?>

    <script>
        // Sample feedback data (in a real app, this would come from a database)
        let feedbackData = [
            {
                id: 1,
                name: "Alex Johnson",
                category: "games",
                rating: 5,
                content: "The mindfulness games are incredibly helpful for my anxiety. The breathing exercises game has become a daily ritual for me.",
                date: "2023-10-15"
            },
            {
                id: 2,
                name: "Sam Rivera",
                category: "mood-tracking",
                rating: 4,
                content: "Love the mood tracking feature! It's helped me identify patterns in my emotional wellbeing. Would be great to have more customization options for the tracking categories.",
                date: "2023-10-12"
            },
            {
                id: 3,
                name: "Taylor Smith",
                category: "user-interface",
                rating: 5,
                content: "The interface is so clean and intuitive. The color scheme is calming and the navigation is straightforward. Great job!",
                date: "2023-10-10"
            }
        ];

        // DOM Elements
        const feedbackForm = document.getElementById('feedbackForm');
        const feedbackContainer = document.getElementById('feedbackContainer');
        const noFeedbackMessage = document.getElementById('noFeedbackMessage');
        const ratingStars = document.querySelectorAll('.rating-star');
        const ratingInput = document.getElementById('rating');

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            displayFeedback();
            setupEventListeners();
        });

        // Set up event listeners
        function setupEventListeners() {
            // Rating stars
            ratingStars.forEach(star => {
                star.addEventListener('click', function() {
                    const rating = parseInt(this.getAttribute('data-rating'));
                    setRating(rating);
                });
            });

            // Form submission
            feedbackForm.addEventListener('submit', function(e) {
                e.preventDefault();
                submitFeedback();
            });

            // Back to top button
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
        }

        // Set rating stars
        function setRating(rating) {
            ratingInput.value = rating;
            
            ratingStars.forEach(star => {
                const starRating = parseInt(star.getAttribute('data-rating'));
                if (starRating <= rating) {
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                }
            });
        }

        // Submit feedback
        function submitFeedback() {
            const name = document.getElementById('name').value || 'Anonymous';
            const category = document.getElementById('category').value || 'general';
            const rating = parseInt(ratingInput.value);
            const content = document.getElementById('feedback').value;

            if (!content.trim()) {
                Swal.fire({
                    title: 'Feedback Required',
                    text: 'Please provide your feedback before submitting.',
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    background: 'var(--card-bg)',
                    color: 'var(--text-primary)',
                    confirmButtonColor: 'var(--primary-color)'
                });
                return;
            }

            if (rating === 0) {
                Swal.fire({
                    title: 'Rating Required',
                    text: 'Please provide a rating by clicking on the stars.',
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    background: 'var(--card-bg)',
                    color: 'var(--text-primary)',
                    confirmButtonColor: 'var(--primary-color)'
                });
                return;
            }

            // Create new feedback object
            const newFeedback = {
                id: feedbackData.length + 1,
                name: name,
                category: category,
                rating: rating,
                content: content,
                date: new Date().toISOString().split('T')[0] // Current date in YYYY-MM-DD format
            };

            // Add to feedback data
            feedbackData.unshift(newFeedback);

            // Display success message
            Swal.fire({
                title: 'Thank You!',
                text: 'Your feedback has been submitted successfully.',
                icon: 'success',
                confirmButtonText: 'OK',
                background: 'var(--card-bg)',
                color: 'var(--text-primary)',
                confirmButtonColor: 'var(--primary-color)'
            });

            // Reset form
            feedbackForm.reset();
            setRating(0);

            // Update displayed feedback
            displayFeedback();
        }

        // Display all feedback
        function displayFeedback() {
            // Clear container
            feedbackContainer.innerHTML = '';

            // Check if there's any feedback
            if (feedbackData.length === 0) {
                feedbackContainer.appendChild(noFeedbackMessage);
                noFeedbackMessage.style.display = 'block';
                return;
            }

            // Hide no feedback message
            noFeedbackMessage.style.display = 'none';

            // Create and append feedback cards
            feedbackData.forEach(feedback => {
                const feedbackCard = createFeedbackCard(feedback);
                feedbackContainer.appendChild(feedbackCard);
            });
        }

        // Create a feedback card element
        function createFeedbackCard(feedback) {
            const card = document.createElement('div');
            card.className = 'feedback-card';

            // Format category for display
            const categoryDisplay = formatCategory(feedback.category);

            // Create stars for rating
            let starsHtml = '';
            for (let i = 1; i <= 5; i++) {
                starsHtml += i <= feedback.rating ? '★' : '☆';
            }

            card.innerHTML = `
                <div class="feedback-header">
                    <div class="feedback-user">
                        <i class="fas fa-user-circle"></i>
                        ${feedback.name}
                    </div>
                    <div class="feedback-date">${feedback.date}</div>
                </div>
                <div class="feedback-rating" title="${feedback.rating} out of 5 stars">
                    ${starsHtml}
                </div>
                <div class="feedback-category">${categoryDisplay}</div>
                <div class="feedback-content">${feedback.content}</div>
            `;

            return card;
        }

        // Format category for display
        function formatCategory(category) {
            const categoryMap = {
                'general': 'General Feedback',
                'feature-request': 'Feature Request',
                'bug-report': 'Bug Report',
                'user-interface': 'User Interface',
                'games': 'Games & Activities',
                'mood-tracking': 'Mood Tracking',
                'community': 'Community Features'
            };

            return categoryMap[category] || category;
        }

        // Add hover effects to feedback cards
        document.addEventListener('mouseover', function(e) {
            if (e.target.closest('.feedback-card')) {
                const card = e.target.closest('.feedback-card');
                card.style.transform = 'translateY(-5px)';
            }
        });

        document.addEventListener('mouseout', function(e) {
            if (e.target.closest('.feedback-card')) {
                const card = e.target.closest('.feedback-card');
                card.style.transform = 'translateY(0)';
            }
        });
    </script>
</body>
</html>