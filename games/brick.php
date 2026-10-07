<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neon Brick Breaker - MoodBuddy</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary-color: #06b6d4;
            --secondary-color: #ec4899;
            --primary-gradient: linear-gradient(135deg, #06b6d4 0%, #ec4899 100%);
            --metallic-shine: linear-gradient(45deg, #06b6d4, #0891b2, #ec4899, #db2777);
            --dark-bg: #111827;
            --card-bg: rgba(30, 41, 59, 0.95);
            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
            --border-light: rgba(6, 182, 212, 0.2);
            --glow-pink: rgba(236, 72, 153, 0.4);
            --glow-blue: rgba(6, 182, 212, 0.4);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Exo 2', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: var(--dark-bg);
            color: var(--text-primary);
            overflow: hidden;
            padding: 20px;
        }

        .futuristic-card {
            width: 500px;
            max-width: 95%;
            background: var(--card-bg);
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(6, 182, 212, 0.2);
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 2px solid transparent;
            background-clip: padding-box;
            height: 90vh;
            display: flex;
            flex-direction: column;
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
        }

        .card-title {
            font-family: 'Orbitron', sans-serif;
            margin-bottom: 10px;
            font-size: 1.8rem;
            font-weight: 900;
            background: var(--metallic-shine);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 3s ease infinite;
            flex-shrink: 0;
        }

        @keyframes shimmer {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .score-display {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            background: rgba(15, 23, 42, 0.7);
            padding: 8px 15px;
            border-radius: 15px;
            flex-shrink: 0;
        }

        .score-item {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .score-label {
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin-bottom: 3px;
        }

        .score-value {
            font-family: 'Orbitron', monospace;
            font-weight: 700;
            font-size: 1.3rem;
        }

        #scoreDisplay {
            color: #0ff;
            text-shadow: 0 0 10px var(--glow-blue);
        }

        #livesDisplay {
            color: #ff0;
            text-shadow: 0 0 10px rgba(255, 255, 0, 0.5);
        }

        #levelDisplay {
            color: #ec4899;
            text-shadow: 0 0 10px var(--glow-pink);
        }

        .controls {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            gap: 10px;
            flex-shrink: 0;
        }

        .futuristic-btn {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 10px 20px;
            font-weight: 700;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex: 1;
            font-size: 0.9rem;
        }

        .futuristic-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px var(--glow-pink);
        }

        .game-container {
            position: relative;
            margin: 0 auto;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 0 20px var(--glow-blue);
            flex: 1;
            min-height: 0;
        }

        #gameCanvas {
            display: block;
            background: #0a0e1a;
            width: 100%;
            height: 100%;
        }

        .instructions {
            margin-top: 10px;
            font-size: 0.8rem;
            color: var(--text-secondary);
            background: rgba(15, 23, 42, 0.5);
            padding: 8px;
            border-radius: 10px;
            flex-shrink: 0;
        }

        /* Responsive adjustments */
        @media (max-width: 600px) {
            .controls {
                flex-direction: column;
            }
            
            .score-display {
                flex-direction: column;
                gap: 8px;
            }
            
            .card-title {
                font-size: 1.5rem;
            }
            
            .futuristic-card {
                padding: 15px;
            }
        }

        html, body {
            height: 100%;
            overflow: hidden;
        }
    </style>
</head>
<body>

<div class="futuristic-card">
    <h3 class="card-title">NEON BRICK BREAKER</h3>

    <div class="score-display">
        <div class="score-item">
            <div class="score-label">SCORE</div>
            <div id="scoreDisplay" class="score-value">0</div>
        </div>
        <div class="score-item">
            <div class="score-label">LIVES</div>
            <div id="livesDisplay" class="score-value">3</div>
        </div>
        <div class="score-item">
            <div class="score-label">LEVEL</div>
            <div id="levelDisplay" class="score-value">1</div>
        </div>
    </div>

    <div class="controls">
        <button id="playBtn" class="futuristic-btn">
            ▶ START GAME
        </button>
        <button id="pauseBtn" class="futuristic-btn" style="display:none;">
            ⏸ PAUSE
        </button>
    </div>

    <div class="game-container">
        <canvas id="gameCanvas"></canvas>
    </div>

    <div class="instructions">
        Use MOUSE or LEFT/RIGHT ARROWS to move paddle. Break all bricks to advance!
    </div>
</div>

<script>
const canvas = document.getElementById("gameCanvas");
const ctx = canvas.getContext("2d");

// Set canvas size to fit container
function resizeCanvas() {
    const container = canvas.parentElement;
    canvas.width = container.clientWidth;
    canvas.height = container.clientHeight;
    initGameObjects();
}

// Initial resize
resizeCanvas();
window.addEventListener('resize', resizeCanvas);

// Game variables
let score = 0;
let lives = 3;
let level = 1;
let gameOver = false;
let gameStarted = false;
let gamePaused = false;
let frames = 0;

// Game objects
const ball = {
    x: 0,
    y: 0,
    radius: 0,
    speed: 5,
    velocityX: 0,
    velocityY: 0,
    color: '#06b6d4',
    
    reset: function() {
        this.radius = Math.min(canvas.width, canvas.height) * 0.015;
        this.x = canvas.width / 2;
        this.y = canvas.height - 100;
        this.speed = 5;
        
        // Random direction upward
        this.velocityX = (Math.random() * 4 - 2);
        this.velocityY = -this.speed;
    },
    
    draw: function() {
        // Ball gradient
        const gradient = ctx.createRadialGradient(
            this.x - 3, this.y - 3, 1,
            this.x, this.y, this.radius
        );
        gradient.addColorStop(0, 'white');
        gradient.addColorStop(0.5, this.color);
        gradient.addColorStop(1, '#0891b2');
        
        ctx.fillStyle = gradient;
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
        ctx.fill();
        
        // Ball glow
        ctx.shadowColor = this.color;
        ctx.shadowBlur = 15;
        ctx.fill();
        ctx.shadowBlur = 0;
    },
    
    update: function() {
        if (gamePaused || !gameStarted) return;
        
        // Move ball
        this.x += this.velocityX;
        this.y += this.velocityY;
        
        // Ball collision with walls
        if (this.x + this.radius > canvas.width || this.x - this.radius < 0) {
            this.velocityX = -this.velocityX;
        }
        
        // Ball collision with top
        if (this.y - this.radius < 0) {
            this.velocityY = -this.velocityY;
        }
        
        // Ball collision with paddle
        if (this.y + this.radius > paddle.y &&
            this.y - this.radius < paddle.y + paddle.height &&
            this.x + this.radius > paddle.x &&
            this.x - this.radius < paddle.x + paddle.width) {
            
            // Calculate hit position (affects bounce angle)
            let hitPos = (this.x - (paddle.x + paddle.width / 2));
            hitPos = hitPos / (paddle.width / 2);
            
            // Calculate angle (max 75 degrees)
            let angle = hitPos * (Math.PI / 3);
            
            // Change direction
            this.velocityX = Math.sin(angle) * this.speed;
            this.velocityY = -Math.cos(angle) * this.speed;
            
            // Increase speed slightly every hit
            this.speed += 0.1;
            
            // Paddle glow effect
            paddle.glow = true;
            setTimeout(() => { paddle.glow = false; }, 100);
        }
        
        // Ball falls below paddle
        if (this.y - this.radius > canvas.height) {
            lives--;
            document.getElementById("livesDisplay").innerText = lives;
            
            if (lives <= 0) {
                endGame();
            } else {
                this.reset();
                paddle.reset();
            }
        }
    }
};

const paddle = {
    x: 0,
    y: 0,
    width: 0,
    height: 0,
    color: '#06b6d4',
    speed: 8,
    glow: false,
    
    reset: function() {
        this.width = canvas.width * 0.15;
        this.height = canvas.height * 0.02;
        this.x = canvas.width / 2 - this.width / 2;
        this.y = canvas.height - 40;
    },
    
    draw: function() {
        // Paddle gradient
        const gradient = ctx.createLinearGradient(
            this.x, this.y, 
            this.x + this.width, this.y + this.height
        );
        gradient.addColorStop(0, '#06b6d4');
        gradient.addColorStop(1, '#0891b2');
        
        ctx.fillStyle = gradient;
        
        // Add glow effect
        if (this.glow) {
            ctx.shadowColor = this.color;
            ctx.shadowBlur = 20;
        }
        
        // Rounded paddle
        ctx.beginPath();
        ctx.roundRect(this.x, this.y, this.width, this.height, 10);
        ctx.fill();
        
        // Reset shadow
        ctx.shadowBlur = 0;
        
        // Paddle border
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.5)';
        ctx.lineWidth = 2;
        ctx.stroke();
    },
    
    moveLeft: function() {
        if (gamePaused || !gameStarted) return;
        this.x -= this.speed;
        if (this.x < 0) this.x = 0;
    },
    
    moveRight: function() {
        if (gamePaused || !gameStarted) return;
        this.x += this.speed;
        if (this.x + this.width > canvas.width) this.x = canvas.width - this.width;
    }
};

// Brick properties
const brick = {
    rows: 4,
    cols: 8,
    width: 0,
    height: 0,
    padding: 5,
    offsetTop: 60,
    offsetLeft: 10,
    bricks: [],
    
    colors: [
        '#ec4899', '#db2777', '#a855f7', '#8b5cf6',
        '#06b6d4', '#0891b2', '#10b981', '#059669'
    ],
    
    init: function() {
        this.bricks = [];
        this.width = (canvas.width - this.offsetLeft * 2 - this.padding * (this.cols - 1)) / this.cols;
        this.height = 20;
        
        for (let r = 0; r < this.rows; r++) {
            for (let c = 0; c < this.cols; c++) {
                this.bricks.push({
                    x: c * (this.width + this.padding) + this.offsetLeft,
                    y: r * (this.height + this.padding) + this.offsetTop,
                    width: this.width,
                    height: this.height,
                    color: this.colors[r % this.colors.length],
                    visible: true
                });
            }
        }
    },
    
    draw: function() {
        this.bricks.forEach(brick => {
            if (!brick.visible) return;
            
            // Brick gradient
            const gradient = ctx.createLinearGradient(
                brick.x, brick.y, 
                brick.x + brick.width, brick.y + brick.height
            );
            gradient.addColorStop(0, brick.color);
            gradient.addColorStop(1, this.darkenColor(brick.color, 40));
            
            ctx.fillStyle = gradient;
            ctx.fillRect(brick.x, brick.y, brick.width, brick.height);
            
            // Brick border
            ctx.strokeStyle = 'rgba(255, 255, 255, 0.3)';
            ctx.lineWidth = 1;
            ctx.strokeRect(brick.x, brick.y, brick.width, brick.height);
            
            // Brick highlight
            ctx.fillStyle = 'rgba(255, 255, 255, 0.2)';
            ctx.fillRect(brick.x, brick.y, brick.width, 3);
        });
    },
    
    darkenColor: function(color, percent) {
        const num = parseInt(color.slice(1), 16);
        const amt = Math.round(2.55 * percent);
        const R = (num >> 16) - amt;
        const G = (num >> 8 & 0x00FF) - amt;
        const B = (num & 0x0000FF) - amt;
        return "#" + (0x1000000 + (R < 0 ? 0 : R) * 0x10000 + (G < 0 ? 0 : G) * 0x100 + (B < 0 ? 0 : B)).toString(16).slice(1);
    },
    
    checkCollision: function() {
        let bricksLeft = 0;
        
        this.bricks.forEach(brick => {
            if (!brick.visible) {
                return;
            }
            
            bricksLeft++;
            
            // Check collision with brick
            if (ball.x + ball.radius > brick.x &&
                ball.x - ball.radius < brick.x + brick.width &&
                ball.y + ball.radius > brick.y &&
                ball.y - ball.radius < brick.y + brick.height) {
                
                // Determine collision side
                const ballFromLeft = ball.x + ball.radius - brick.x;
                const ballFromRight = brick.x + brick.width - (ball.x - ball.radius);
                const ballFromTop = ball.y + ball.radius - brick.y;
                const ballFromBottom = brick.y + brick.height - (ball.y - ball.radius);
                
                // Find minimum overlap
                const minOverlap = Math.min(ballFromLeft, ballFromRight, ballFromTop, ballFromBottom);
                
                // Bounce based on collision side
                if (minOverlap === ballFromLeft || minOverlap === ballFromRight) {
                    ball.velocityX = -ball.velocityX;
                } else {
                    ball.velocityY = -ball.velocityY;
                }
                
                // Remove brick and add score
                brick.visible = false;
                score += 10;
                document.getElementById("scoreDisplay").innerText = score;
            }
        });
        
        // Check if all bricks are destroyed
        if (bricksLeft === 0) {
            levelComplete();
        }
    }
};

// Initialize game objects
function initGameObjects() {
    ball.reset();
    paddle.reset();
    brick.init();
}

// Draw game scene
function drawScene() {
    // Clear canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    // Draw background
    const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
    gradient.addColorStop(0, "#0f172a");
    gradient.addColorStop(1, "#1e293b");
    ctx.fillStyle = gradient;
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    
    // Draw bricks
    brick.draw();
    
    // Draw paddle
    paddle.draw();
    
    // Draw ball
    ball.draw();
    
    // Draw game over text
    if (gameOver) {
        ctx.fillStyle = 'rgba(0, 0, 0, 0.7)';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        ctx.font = `bold ${Math.min(canvas.width * 0.08, 30)}px Orbitron`;
        ctx.fillStyle = '#ec4899';
        ctx.textAlign = 'center';
        ctx.fillText('GAME OVER', canvas.width / 2, canvas.height / 2 - 30);
        
        ctx.font = `bold ${Math.min(canvas.width * 0.05, 20)}px Exo 2`;
        ctx.fillStyle = '#06b6d4';
        ctx.fillText(`Final Score: ${score}`, canvas.width / 2, canvas.height / 2 + 20);
        
        ctx.font = `bold ${Math.min(canvas.width * 0.04, 16)}px Exo 2`;
        ctx.fillStyle = 'white';
        ctx.fillText(`Level Reached: ${level}`, canvas.width / 2, canvas.height / 2 + 50);
    }
}

// Level complete
function levelComplete() {
    level++;
    document.getElementById("levelDisplay").innerText = level;
    
    // Increase difficulty
    ball.speed += 0.5;
    if (paddle.width > canvas.width * 0.08) {
        paddle.width *= 0.9; // Make paddle smaller each level
    }
    
    Swal.fire({
        title: 'Level Complete!',
        html: `Moving to Level <b>${level}</b>`,
        icon: 'success',
        confirmButtonText: 'Continue',
        background: 'var(--card-bg)',
        color: 'var(--text-primary)',
        confirmButtonColor: 'var(--primary-color)'
    }).then(() => {
        initGameObjects();
        if (gameStarted && !gamePaused) {
            gameLoop();
        }
    });
}

// End game
function endGame() {
    gameOver = true;
    gameStarted = false;
    
    Swal.fire({
        title: 'Game Over!',
        html: `Final Score: <b>${score}</b><br>Level Reached: <b>${level}</b>`,
        icon: 'info',
        confirmButtonText: 'Play Again',
        background: 'var(--card-bg)',
        color: 'var(--text-primary)',
        confirmButtonColor: 'var(--primary-color)'
    }).then(() => {
        resetGame();
    });
}

// Reset game
function resetGame() {
    score = 0;
    lives = 3;
    level = 1;
    gameOver = false;
    document.getElementById("scoreDisplay").innerText = score;
    document.getElementById("livesDisplay").innerText = lives;
    document.getElementById("levelDisplay").innerText = level;
    initGameObjects();
}

// Start game
function startGame() {
    if (!gameStarted) {
        gameStarted = true;
        gamePaused = false;
        gameOver = false;
        resetGame();
        
        document.getElementById("playBtn").style.display = "none";
        document.getElementById("pauseBtn").style.display = "block";
        
        gameLoop();
    }
}

// Toggle pause
function togglePause() {
    if (gameStarted && !gameOver) {
        gamePaused = !gamePaused;
        document.getElementById("pauseBtn").innerHTML = gamePaused ? "▶ RESUME" : "⏸ PAUSE";
        
        if (!gamePaused) {
            gameLoop();
        }
    }
}

// Game loop
function gameLoop() {
    if (!gameOver && gameStarted && !gamePaused) {
        update();
        drawScene();
        frames++;
        requestAnimationFrame(gameLoop);
    }
}

// Update game state
function update() {
    ball.update();
    brick.checkCollision();
}

// Mouse movement for paddle
canvas.addEventListener('mousemove', (e) => {
    if (gamePaused || !gameStarted) return;
    
    const rect = canvas.getBoundingClientRect();
    const mouseX = e.clientX - rect.left;
    
    // Move paddle to mouse position
    paddle.x = mouseX - paddle.width / 2;
    
    // Keep paddle within canvas
    if (paddle.x < 0) paddle.x = 0;
    if (paddle.x + paddle.width > canvas.width) {
        paddle.x = canvas.width - paddle.width;
    }
});

// Keyboard controls
document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowLeft') {
        paddle.moveLeft();
    } else if (e.key === 'ArrowRight') {
        paddle.moveRight();
    } else if (e.key === ' ') {
        if (!gameStarted && !gameOver) {
            startGame();
        } else if (gameStarted && !gameOver) {
            togglePause();
        }
    }
});

// Event listeners
document.getElementById("playBtn").addEventListener("click", startGame);
document.getElementById("pauseBtn").addEventListener("click", togglePause);

// Load initial state
window.addEventListener('load', function() {
    initGameObjects();
    drawScene();
});

// Prevent arrow keys from scrolling
document.addEventListener("keydown", function(e) {
    if(["ArrowLeft","ArrowRight"," "].indexOf(e.key) > -1) {
        e.preventDefault();
    }
}, false);
</script>

</body>
</html>