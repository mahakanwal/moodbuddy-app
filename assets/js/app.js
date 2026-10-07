// Products Section
document.addEventListener('DOMContentLoaded', function() {
    const track = document.getElementById('carouselTrack');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const indicatorsContainer = document.getElementById('carouselIndicators');
    
    const cards = document.querySelectorAll('.futuristic-card');
    if (!track || !cards.length || !document.querySelector('.carousel-container')) return; // page has no product carousel
    const cardCount = cards.length;
    const cardWidth = cards[0].offsetWidth + 20; // Width + gap
    const visibleCards = Math.floor(document.querySelector('.carousel-container').offsetWidth / cardWidth);
    
    let currentPosition = 0;
    let maxPosition = cardCount - visibleCards;
    
    // Create indicators
    for (let i = 0; i <= maxPosition; i++) {
        const indicator = document.createElement('div');
        indicator.classList.add('indicator');
        if (i === 0) indicator.classList.add('active');
        indicator.addEventListener('click', () => {
            goToPosition(i);
        });
        indicatorsContainer.appendChild(indicator);
    }
    
    // Update button states
    function updateButtonStates() {
        prevBtn.disabled = currentPosition === 0;
        nextBtn.disabled = currentPosition === maxPosition;
        
        // Update indicators
        document.querySelectorAll('.indicator').forEach((ind, index) => {
            ind.classList.toggle('active', index === currentPosition);
        });
    }
    
    // Go to specific position
    function goToPosition(position) {
        currentPosition = Math.max(0, Math.min(position, maxPosition));
        track.style.transform = `translateX(-${currentPosition * cardWidth}px)`;
        updateButtonStates();
    }
    
    // Next button click
    nextBtn.addEventListener('click', () => {
        goToPosition(currentPosition + 1);
    });
    
    // Previous button click
    prevBtn.addEventListener('click', () => {
        goToPosition(currentPosition - 1);
    });
    
    // Handle window resize
    window.addEventListener('resize', () => {
        const newCardWidth = cards[0].offsetWidth + 20;
        const newVisibleCards = Math.floor(document.querySelector('.carousel-container').offsetWidth / newCardWidth);
        maxPosition = cardCount - newVisibleCards;
        
        // Adjust current position if needed
        if (currentPosition > maxPosition) {
            currentPosition = maxPosition;
        }
        
        track.style.transform = `translateX(-${currentPosition * newCardWidth}px)`;
        updateButtonStates();
        
        // Recreate indicators if needed
        const currentIndicators = document.querySelectorAll('.indicator').length;
        if (currentIndicators !== maxPosition + 1) {
            indicatorsContainer.innerHTML = '';
            for (let i = 0; i <= maxPosition; i++) {
                const indicator = document.createElement('div');
                indicator.classList.add('indicator');
                if (i === currentPosition) indicator.classList.add('active');
                indicator.addEventListener('click', () => {
                    goToPosition(i);
                });
                indicatorsContainer.appendChild(indicator);
            }
        }
    });
    
    // Initialize button states
    updateButtonStates();
    
    // Auto-advance carousel (optional)
    let autoSlideInterval = setInterval(() => {
        if (currentPosition < maxPosition) {
            goToPosition(currentPosition + 1);
        } else {
            goToPosition(0);
        }
    }, 5000);
    
    // Pause auto-slide on hover
    track.addEventListener('mouseenter', () => {
        clearInterval(autoSlideInterval);
    });
    
    track.addEventListener('mouseleave', () => {
        autoSlideInterval = setInterval(() => {
            if (currentPosition < maxPosition) {
                goToPosition(currentPosition + 1);
            } else {
                goToPosition(0);
            }
        }, 5000);
    });
});

function addToCart(productId) {
fetch('actions/add_to_cart.php', {
method: 'POST',
headers: {'Content-Type': 'application/x-www-form-urlencoded'},
body: 'product_id=' + productId
})
.then(res => res.json())
.then(data => {
if (data.success) {
    Swal.fire({
        icon: 'success',
        title: 'Added to Cart!',
        text: 'Product has been added successfully.',
        showConfirmButton: false,
        timer: 1800,
        background: '#121212',
        color: '#fff',
        iconColor: '#00ff88',
        toast: true,
        position: 'center'
    });
} else {
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: 'Unable to add product to cart.',
        showConfirmButton: false,
        timer: 1800
    });
}
})
.catch(err => {
console.error(err);
Swal.fire({
    icon: 'error',
    title: 'Oops!',
    text: 'Something went wrong while adding to cart.',
    showConfirmButton: false,
    timer: 1800
});
});
}
// Songs Section
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileNav = document.getElementById('mobileNav');
    let isMenuOpen = false;
    
    mobileMenuBtn.addEventListener('click', function() {
        isMenuOpen = !isMenuOpen;
        mobileNav.classList.toggle('active');
        
        const icon = mobileMenuBtn.querySelector('i');
        icon.className = isMenuOpen ? 'fas fa-times' : 'fas fa-bars';
    });
    
    // Close menu when clicking on nav links
    document.querySelectorAll('.mobile-nav-link').forEach(link => {
        link.addEventListener('click', function() {
            mobileNav.classList.remove('active');
            isMenuOpen = false;
            const icon = mobileMenuBtn.querySelector('i');
            icon.className = 'fas fa-bars';
        });
    });
});

// Scroll Animation (block-scoped so pages with their own observer don't clash)
{
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

    document.querySelectorAll('.fade-in').forEach(el => {
        observer.observe(el);
    });
}

// Enhanced scroll effects
window.addEventListener('scroll', () => {
    const header = document.querySelector('.futuristic-header');
    if (!header) return;
    if (window.scrollY > 50) {
        header.style.background = 'rgba(255, 255, 255, 0.98)';
        header.style.boxShadow = '0 8px 30px rgba(6, 182, 212, 0.12)';
    } else {
        header.style.background = 'rgba(255, 255, 255, 0.95)';
        header.style.boxShadow = '0 4px 20px rgba(6, 182, 212, 0.08)';
    }
});
// song audio button
function showPlayer(button) {
const wrapper = button.nextElementSibling;
const audio = wrapper.querySelector("audio");

if (wrapper.style.display === "none") {
wrapper.style.display = "block";
button.style.display = "none";

// Song auto play
audio.play();
}
}

// custom cursor
        class CursorTrail {
            constructor() {
                this.trail = [];
                //  Reduced trail length from 15 to 8 for shorter trail
                this.maxTrailLength = 15;
                this.mouseX = 0;
                this.mouseY = 0;
                this.mainCursor = document.querySelector('.main-cursor');
                //  Added inactivity tracking variables
                this.isMoving = false;
                this.inactivityTimer = null;
                this.inactivityDelay = 150; // milliseconds before trail starts fading
                this.trailVisible = true;
                
                this.init();
            }

            init() {
                // Track mouse movement
                document.addEventListener('mousemove', (e) => {
                    this.mouseX = e.clientX;
                    this.mouseY = e.clientY;
                    this.updateMainCursor();
                    //  Added movement detection and timer reset
                    this.handleMouseMovement();
                });

                // Hide cursor when leaving window
                document.addEventListener('mouseleave', () => {
                    this.mainCursor.style.opacity = '0';
                    //  Clear trail when mouse leaves
                    this.clearTrail();
                });

                document.addEventListener('mouseenter', () => {
                    this.mainCursor.style.opacity = '1';
                });

                // Start animation loop
                this.animate();
            }

            //  Added method to handle mouse movement and inactivity detection
            handleMouseMovement() {
                this.isMoving = true;
                this.trailVisible = true;
                
                // Clear existing timer
                if (this.inactivityTimer) {
                    clearTimeout(this.inactivityTimer);
                }
                
                // Add trail point only when moving
                this.addTrailPoint();
                
                // Set new timer for inactivity
                this.inactivityTimer = setTimeout(() => {
                    this.isMoving = false;
                    this.startTrailFadeOut();
                }, this.inactivityDelay);
            }

            //  Added method to start fading out the trail
            startTrailFadeOut() {
                this.trailVisible = false;
                // Gradually remove trail points
                const fadeInterval = setInterval(() => {
                    if (this.trail.length > 0 && !this.isMoving) {
                        const oldPoint = this.trail.shift();
                        this.removeTrailElement(oldPoint.element);
                    } else {
                        clearInterval(fadeInterval);
                    }
                }, 50);
            }

            //  Added method to clear all trail points immediately
            clearTrail() {
                while (this.trail.length > 0) {
                    const point = this.trail.shift();
                    this.removeTrailElement(point.element);
                }
            }

            updateMainCursor() {
                this.mainCursor.style.left = (this.mouseX - 10) + 'px';
                this.mainCursor.style.top = (this.mouseY - 10) + 'px';
            }

            addTrailPoint() {
                //  Only add trail points when trail should be visible
                if (!this.trailVisible) return;
                
                // Add new trail point
                const trailPoint = {
                    x: this.mouseX,
                    y: this.mouseY,
                    age: 0,
                    element: this.createTrailElement()
                };

                this.trail.push(trailPoint);

                // Remove old trail points
                if (this.trail.length > this.maxTrailLength) {
                    const oldPoint = this.trail.shift();
                    this.removeTrailElement(oldPoint.element);
                }
            }

            createTrailElement() {
                const element = document.createElement('div');
                element.className = 'trail-circle';
                document.body.appendChild(element);
                return element;
            }

            removeTrailElement(element) {
                element.style.animation = 'fadeOut 0.3s ease-out forwards';
                setTimeout(() => {
                    if (element.parentNode) {
                        element.parentNode.removeChild(element);
                    }
                }, 300);
            }

            animate() {
                this.trail.forEach((point, index) => {
                    point.age++;
                    
                    // Calculate properties based on age and position in trail
                    const ageRatio = index / (this.trail.length - 1);
                    const size = Math.max(5, 25 - (ageRatio * 20)); // Size from 25px to 5px
                    const opacity = Math.max(0.1, 1 - (ageRatio * 0.9)); // Opacity from 1 to 0.1
                    
                    // Apply styles
                    point.element.style.left = (point.x - size/2) + 'px';
                    point.element.style.top = (point.y - size/2) + 'px';
                    point.element.style.width = size + 'px';
                    point.element.style.height = size + 'px';
                    point.element.style.opacity = opacity;
                    
                    // Add some randomness for organic feel
                    const wobble = Math.sin(point.age * 0.1) * 2;
                    point.element.style.transform = `translate(${wobble}px, ${wobble}px)`;
                });

                requestAnimationFrame(() => this.animate());
            }
        }

        // Initialize the cursor trail when page loads
        window.addEventListener('load', () => {
            new CursorTrail();
        });

        // Add some interactive elements
        document.addEventListener('click', (e) => {
            // Create a burst effect on click
            for (let i = 0; i < 8; i++) {
                setTimeout(() => {
                    const burst = document.createElement('div');
                    burst.className = 'trail-circle';
                    burst.style.left = (e.clientX - 15) + 'px';
                    burst.style.top = (e.clientY - 15) + 'px';
                    burst.style.width = '30px';
                    burst.style.height = '30px';
                    burst.style.background = `hsl(${Math.random() * 360}, 70%, 70%)`;
                    burst.style.animation = 'fadeOut 0.8s ease-out forwards';
                    
                    const angle = (i / 8) * Math.PI * 2;
                    const distance = 50;
                    const endX = e.clientX + Math.cos(angle) * distance;
                    const endY = e.clientY + Math.sin(angle) * distance;
                    
                    setTimeout(() => {
                        burst.style.left = (endX - 15) + 'px';
                        burst.style.top = (endY - 15) + 'px';
                    }, 10);
                    
                    document.body.appendChild(burst);
                    
                    setTimeout(() => {
                        if (burst.parentNode) {
                            burst.parentNode.removeChild(burst);
                        }
                    }, 800);
                }, i * 50);
            }
        });
         // Back to Top Functionality
         const backToTop = document.querySelector('.back-to-top');
         if (backToTop) {
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
