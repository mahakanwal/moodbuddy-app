<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neon Pong - MoodBuddy</title>
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

        #playerScore {
            color: #0ff;
            text-shadow: 0 0 10px var(--glow-blue);
        }

        #aiScore {
            color: #ff0;
            text-shadow: 0 0 10px rgba(255, 255, 0, 0.5);
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

        /* Prevent vertical scrolling */
        html, body {
            height: 100%;
            overflow: hidden;
        }
        
        /* Ball trail effect */
        .ball-trail {
            position: absolute;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: rgba(6, 182, 212, 0.5);
            pointer-events: none;
            opacity: 0;
        }
        
        /* Paddle glow effect */
        .paddle-glow {
            box-shadow: 0 0 15px var(--glow-blue);
        }
    </style>
</head>
<body>

<div class="futuristic-card">
    <h3 class="card-title">NEON PONG</h3>

    <div class="score-display">
        <div class="score-item">
            <div class="score-label">PLAYER</div>
            <div id="playerScore" class="score-value">0</div>
        </div>
        <div class="score-item">
            <div class="score-label">AI</div>
            <div id="aiScore" class="score-value">0</div>
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
        Use MOUSE to move your paddle. First to 5 points wins!
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
}

// Initial resize
resizeCanvas();
window.addEventListener('resize', resizeCanvas);

// Game variables
let playerScore = 0;
let aiScore = 0;
let gameOver = false;
let gameStarted = false;
let gamePaused = false;
let ballTrails = [];

// Game objects
const ball = {
    x: 0,
    y: 0,
    radius: 10,
    speed: 5,
    velocityX: 5,
    velocityY: 5,
    color: '#06b6d4',
    
    reset: function() {
        this.x = canvas.width / 2;
        this.y = canvas.height / 2;
        this.speed = 5;
        
        // Random direction
        this.velocityX = (Math.random() > 0.5 ? 1 : -1) * this.speed;
        this.velocityY = (Math.random() > 0.5 ? 1 : -1) * this.speed;
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
        
        // Create trail effect
        if (gameStarted && !gamePaused) {
            ballTrails.push({
                x: this.x,
                y: this.y,
                opacity: 0.7,
                radius: this.radius * 0.8
            });
        }
        
        // Ball collision with top and bottom
        if (this.y + this.radius > canvas.height || this.y - this.radius < 0) {
            this.velocityY = -this.velocityY;
        }
        
        // Ball collision with paddles
        if (this.x - this.radius < playerPaddle.x + playerPaddle.width &&
            this.x + this.radius > playerPaddle.x &&
            this.y > playerPaddle.y &&
            this.y < playerPaddle.y + playerPaddle.height) {
            
            // Calculate hit position (affects bounce angle)
            let hitPos = (this.y - (playerPaddle.y + playerPaddle.height / 2));
            hitPos = hitPos / (playerPaddle.height / 2);
            
            // Calculate angle (max 60 degrees)
            let angle = hitPos * (Math.PI / 4);
            
            // Change direction and add speed
            this.velocityX = Math.cos(angle) * this.speed;
            this.velocityY = Math.sin(angle) * this.speed;
            
            // Ensure ball moves to the right after hitting player paddle
            if (this.velocityX < 0) this.velocityX = -this.velocityX;
            
            // Add paddle glow effect
            playerPaddle.glow = true;
            setTimeout(() => { playerPaddle.glow = false; }, 100);
        }
        
        if (this.x + this.radius > aiPaddle.x &&
            this.x - this.radius < aiPaddle.x + aiPaddle.width &&
            this.y > aiPaddle.y &&
            this.y < aiPaddle.y + aiPaddle.height) {
            
            // Calculate hit position (affects bounce angle)
            let hitPos = (this.y - (aiPaddle.y + aiPaddle.height / 2));
            hitPos = hitPos / (aiPaddle.height / 2);
            
            // Calculate angle (max 60 degrees)
            let angle = hitPos * (Math.PI / 4);
            
            // Change direction and add speed
            this.velocityX = Math.cos(angle) * this.speed;
            this.velocityY = Math.sin(angle) * this.speed;
            
            // Ensure ball moves to the left after hitting AI paddle
            if (this.velocityX > 0) this.velocityX = -this.velocityX;
            
            // Add paddle glow effect
            aiPaddle.glow = true;
            setTimeout(() => { aiPaddle.glow = false; }, 100);
        }
        
        // Score points
        if (this.x - this.radius < 0) {
            aiScore++;
            document.getElementById("aiScore").innerText = aiScore;
            this.reset();
            checkGameOver();
        } else if (this.x + this.radius > canvas.width) {
            playerScore++;
            document.getElementById("playerScore").innerText = playerScore;
            this.reset();
            checkGameOver();
        }
    }
};

const playerPaddle = {
    x: 20,
    y: 0,
    width: 10,
    height: 100,
    color: '#06b6d4',
    speed: 8,
    glow: false,
    
    reset: function() {
        this.y = canvas.height / 2 - this.height / 2;
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
        
        ctx.fillRect(this.x, this.y, this.width, this.height);
        
        // Reset shadow
        ctx.shadowBlur = 0;
        
        // Paddle border
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.5)';
        ctx.lineWidth = 2;
        ctx.strokeRect(this.x, this.y, this.width, this.height);
    },
    
    update: function() {
        if (gamePaused || !gameStarted) return;
        
        // Mouse movement
        canvas.addEventListener('mousemove', (e) => {
            if (gamePaused || !gameStarted) return;
            
            const rect = canvas.getBoundingClientRect();
            const mouseY = e.clientY - rect.top;
            
            // Move paddle to mouse position
            this.y = mouseY - this.height / 2;
            
            // Keep paddle within canvas
            if (this.y < 0) this.y = 0;
            if (this.y + this.height > canvas.height) this.y = canvas.height - this.height;
        });
    }
};

const aiPaddle = {
    x: 0,
    y: 0,
    width: 10,
    height: 100,
    color: '#ec4899',
    speed: 5,
    glow: false,
    
    reset: function() {
        this.x = canvas.width - 30;
        this.y = canvas.height / 2 - this.height / 2;
    },
    
    draw: function() {
        // Paddle gradient
        const gradient = ctx.createLinearGradient(
            this.x, this.y, 
            this.x + this.width, this.y + this.height
        );
        gradient.addColorStop(0, '#ec4899');
        gradient.addColorStop(1, '#db2777');
        
        ctx.fillStyle = gradient;
        
        // Add glow effect
        if (this.glow) {
            ctx.shadowColor = this.color;
            ctx.shadowBlur = 20;
        }
        
        ctx.fillRect(this.x, this.y, this.width, this.height);
        
        // Reset shadow
        ctx.shadowBlur = 0;
        
        // Paddle border
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.5)';
        ctx.lineWidth = 2;
        ctx.strokeRect(this.x, this.y, this.width, this.height);
    },
    
    update: function() {
        if (gamePaused || !gameStarted) return;
        
        // Simple AI - follow the ball with some delay
        const targetY = ball.y - this.height / 2;
        
        // Move towards target with some speed limitation
        if (this.y < targetY) {
            this.y += Math.min(this.speed, targetY - this.y);
        } else if (this.y > targetY) {
            this.y -= Math.min(this.speed, this.y - targetY);
        }
        
        // Keep paddle within canvas
        if (this.y < 0) this.y = 0;
        if (this.y + this.height > canvas.height) this.y = canvas.height - this.height;
    }
};

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
    
    // Draw center line
    ctx.strokeStyle = 'rgba(255, 255, 255, 0.1)';
    ctx.setLineDash([10, 10]);
    ctx.beginPath();
    ctx.moveTo(canvas.width / 2, 0);
    ctx.lineTo(canvas.width / 2, canvas.height);
    ctx.stroke();
    ctx.setLineDash([]);
    
    // Draw ball trails
    for (let i = 0; i < ballTrails.length; i++) {
        const trail = ballTrails[i];
        ctx.fillStyle = `rgba(6, 182, 212, ${trail.opacity})`;
        ctx.beginPath();
        ctx.arc(trail.x, trail.y, trail.radius, 0, Math.PI * 2);
        ctx.fill();
        
        // Fade out trails
        trail.opacity -= 0.02;
        trail.radius -= 0.1;
        
        // Remove faded trails
        if (trail.opacity <= 0) {
            ballTrails.splice(i, 1);
            i--;
        }
    }
    
    // Draw paddles
    playerPaddle.draw();
    aiPaddle.draw();
    
    // Draw ball
    ball.draw();
    
    // Draw game over text
    if (gameOver) {
        ctx.fillStyle = 'rgba(0, 0, 0, 0.7)';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        ctx.font = '30px Orbitron';
        ctx.fillStyle = playerScore > aiScore ? '#06b6d4' : '#ec4899';
        ctx.textAlign = 'center';
        ctx.fillText(playerScore > aiScore ? 'YOU WIN!' : 'AI WINS!', canvas.width / 2, canvas.height / 2 - 30);
        
        ctx.font = '20px Exo 2';
        ctx.fillStyle = 'white';
        ctx.fillText(`Final Score: ${playerScore} - ${aiScore}`, canvas.width / 2, canvas.height / 2 + 20);
    }
}

// Reset ball position
function resetBall() {
    ball.reset();
    ballTrails = [];
}

// Check if game is over
function checkGameOver() {
    if (playerScore >= 5 || aiScore >= 5) {
        gameOver = true;
        gameStarted = false;
        
        Swal.fire({
            title: playerScore > aiScore ? 'Victory!' : 'Game Over!',
            html: `Final Score: <b>${playerScore} - ${aiScore}</b>`,
            icon: playerScore > aiScore ? 'success' : 'info',
            confirmButtonText: 'Play Again',
            background: 'var(--card-bg)',
            color: 'var(--text-primary)',
            confirmButtonColor: 'var(--primary-color)'
        }).then(() => {
            resetGame();
        });
    }
}

// Reset game
function resetGame() {
    playerScore = 0;
    aiScore = 0;
    gameOver = false;
    document.getElementById("playerScore").innerText = playerScore;
    document.getElementById("aiScore").innerText = aiScore;
    playerPaddle.reset();
    aiPaddle.reset();
    resetBall();
}

// Start game
function startGame() {
    if (!gameStarted) {
        gameStarted = true;
        gamePaused = false;
        resetGame();
        gameLoop();
        
        document.getElementById("playBtn").style.display = "none";
        document.getElementById("pauseBtn").style.display = "block";
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
        requestAnimationFrame(gameLoop);
    }
}

// Update game state
function update() {
    ball.update();
    playerPaddle.update();
    aiPaddle.update();
}

// Initialize game objects
function initGame() {
    playerPaddle.reset();
    aiPaddle.reset();
    ball.reset();
}

// Event listeners
document.getElementById("playBtn").addEventListener("click", startGame);
document.getElementById("pauseBtn").addEventListener("click", togglePause);

// Load initial state
window.addEventListener('load', function() {
    initGame();
    drawScene();
});

// Prevent arrow keys from scrolling
document.addEventListener("keydown", function(e) {
    if(["Space","ArrowUp","ArrowDown","ArrowLeft","ArrowRight"].indexOf(e.code) > -1) {
        e.preventDefault();
    }
}, false);
</script>

</body>
</html>