<?php include_once __DIR__ . '/includes/header.php'; ?>

    <section class="hero-section">
        <h1 class="hero-title">Contact Us</h1>
        <p class="hero-subtitle">We'd love to hear from you! Get in touch with our team</p>
    </section>

    <main class="main-content">
        <h2 class="section-title">Get In Touch</h2>
        
        <div class="contact-container">
            <div class="contact-info">
                <div class="contact-info-item fade-in">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="contact-text">
                        <h3>Our Location</h3>
                        <p>123 Innovation Drive, Tech Park<br>San Francisco, CA 94103</p>
                    </div>
                </div>
                
                <div class="contact-info-item fade-in">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="contact-text">
                        <h3>Phone Number</h3>
                        <p>+1 (555) 123-4567<br>+1 (555) 987-6543</p>
                    </div>
                </div>
                
                <div class="contact-info-item fade-in">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="contact-text">
                        <h3>Email Address</h3>
                        <p>hello@moodbuddy.com<br>support@moodbuddy.com</p>
                    </div>
                </div>
                
                <div class="contact-info-item fade-in">
                    <div class="contact-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="contact-text">
                        <h3>Working Hours</h3>
                        <p>Monday - Friday: 9AM - 6PM<br>Saturday: 10AM - 4PM</p>
                    </div>
                </div>
            </div>
            
            <div class="contact-form-container fade-in">
                <form id="contactForm" action="https://api.web3forms.com/submit" method="POST">
                    <!-- Web3Forms required parameters -->
                    <input type="hidden" name="access_key" value="385e26e5-1e29-442b-aa0c-6a18ce325c5f">
                    <input type="hidden" name="subject" value="New Contact Form Submission from MoodBuddy">
                    <input type="hidden" name="from_name" value="MoodBuddy Contact Form">
                    <input type="checkbox" name="botcheck" style="display: none;">
                    
                    <div class="form-group">
                        <label for="name" class="form-label">Your Name</label>
                        <input type="text" id="name" name="name" class="form-input" placeholder="Enter your full name" required>
                        <div class="error-message" id="nameError">Numbers are not allowed in the name field</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-input" placeholder="Enter your email address" required>
                        <div class="error-message" id="emailError">Please enter a valid email address</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number (Optional)</label>
                        <input type="tel" id="phone" name="phone" class="form-input" placeholder="Enter your phone number">
                        <div class="error-message" id="phoneError">Please enter a valid phone number</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="message" class="form-label">Your Message</label>
                        <textarea id="message" name="message" class="form-input" placeholder="Type your message here..." required></textarea>
                        <div class="error-message" id="messageError">Numbers are not allowed in the message field</div>
                    </div>
                    
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
                
                <div class="success-message" id="successMessage">
                    <i class="fas fa-check-circle"></i>
                    <h3>Message Sent Successfully!</h3>
                    <p>Thank you for contacting us. We'll get back to you soon.</p>
                </div>
            </div>
        </div>
    </main>
    <?php include_once __DIR__ . '/includes/footer.php'; ?>

    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Mobile menu functionality
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileNav = document.getElementById('mobileNav');
        
        mobileMenuBtn.addEventListener('click', () => {
            mobileNav.classList.toggle('active');
        });
        
        // Form validation
        const contactForm = document.getElementById('contactForm');
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const phoneInput = document.getElementById('phone');
        const messageInput = document.getElementById('message');
        const nameError = document.getElementById('nameError');
        const emailError = document.getElementById('emailError');
        const phoneError = document.getElementById('phoneError');
        const messageError = document.getElementById('messageError');
        const successMessage = document.getElementById('successMessage');
        
        // Function to check if text contains numbers
        function hasNumbers(text) {
            return /\d/.test(text);
        }
        
        // Function to validate email
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
        
        // Function to validate phone number (if provided)
        function isValidPhone(phone) {
            if (!phone) return true; // Phone is optional
            const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
            return phoneRegex.test(phone.replace(/[\s\-\(\)]/g, ''));
        }
        
        // Real-time validation
        nameInput.addEventListener('input', () => {
            if (hasNumbers(nameInput.value)) {
                nameInput.classList.add('error');
                nameError.style.display = 'block';
            } else {
                nameInput.classList.remove('error');
                nameError.style.display = 'none';
            }
        });
        
        emailInput.addEventListener('input', () => {
            if (!isValidEmail(emailInput.value)) {
                emailInput.classList.add('error');
                emailError.style.display = 'block';
            } else {
                emailInput.classList.remove('error');
                emailError.style.display = 'none';
            }
        });
        
        phoneInput.addEventListener('input', () => {
            if (!isValidPhone(phoneInput.value)) {
                phoneInput.classList.add('error');
                phoneError.style.display = 'block';
            } else {
                phoneInput.classList.remove('error');
                phoneError.style.display = 'none';
            }
        });
        
        messageInput.addEventListener('input', () => {
            if (hasNumbers(messageInput.value)) {
                messageInput.classList.add('error');
                messageError.style.display = 'block';
            } else {
                messageInput.classList.remove('error');
                messageError.style.display = 'none';
            }
        });
        
        // Form submission
        contactForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            let isValid = true;
            
            // Validate name
            if (hasNumbers(nameInput.value)) {
                nameInput.classList.add('error');
                nameError.style.display = 'block';
                isValid = false;
            }
            
            // Validate email
            if (!isValidEmail(emailInput.value)) {
                emailInput.classList.add('error');
                emailError.style.display = 'block';
                isValid = false;
            }
            
            // Validate phone
            if (!isValidPhone(phoneInput.value)) {
                phoneInput.classList.add('error');
                phoneError.style.display = 'block';
                isValid = false;
            }
            
            // Validate message
            if (hasNumbers(messageInput.value)) {
                messageInput.classList.add('error');
                messageError.style.display = 'block';
                isValid = false;
            }
            
            if (isValid) {
                // Show loading state with SweetAlert
                Swal.fire({
                    title: 'Sending Message',
                    text: 'Please wait while we send your message...',
                    icon: 'info',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Create a FormData object from the form
                const formData = new FormData(contactForm);
                
                // Send the form data to Web3Forms
                fetch('https://api.web3forms.com/submit', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message with SweetAlert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Your message has been sent successfully!',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#4e54c8'
                        }).then(() => {
                            // Reset form and show success message
                            contactForm.reset();
                            contactForm.style.display = 'none';
                            successMessage.style.display = 'block';
                        });
                    } else {
                        // Show error message with SweetAlert
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was an error sending your message. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'Try Again',
                            confirmButtonColor: '#4e54c8'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Show error message with SweetAlert
                    Swal.fire({
                        title: 'Error!',
                        text: 'There was an error sending your message. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'Try Again',
                        confirmButtonColor: '#4e54c8'
                    });
                });
            } else {
                // Show validation error with SweetAlert
                Swal.fire({
                    title: 'Validation Error',
                    text: 'Please fix the errors in the form before submitting.',
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#4e54c8'
                });
            }
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