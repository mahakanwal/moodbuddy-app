<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neon Bubble Shooter - MoodBuddy</title>
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
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(6, 182, 212, 0.2);
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 2px solid transparent;
            background-clip: padding-box;
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
            margin-bottom: 15px;
            font-size: 2rem;
            font-weight: 900;
            background: var(--metallic-shine);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 3s ease infinite;
        }

        @keyframes shimmer {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .game-container {
            position: relative;
            margin: 0 auto 15px auto;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 0 20px var(--glow-blue);
            height: 500px;
        }

        #gameCanvas {
            display: block;
            background: #0a0e1a;
            width: 100%;
            height: 100%;
        }

        .controls {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            gap: 10px;
        }

        .futuristic-btn {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 12px 25px;
            font-weight: 700;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex: 1;
        }

        .futuristic-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px var(--glow-pink);
        }

        .score-display {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            background: rgba(15, 23, 42, 0.7);
            padding: 10px 15px;
            border-radius: 15px;
        }

        .score-item {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .score-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-bottom: 5px;
        }

        .score-value {
            font-family: 'Orbitron', monospace;
            font-weight: 700;
            font-size: 1.2rem;
        }

        #scoreDisplay {
            color: #0ff;
            text-shadow: 0 0 10px var(--glow-blue);
        }

        #highscoreDisplay {
            color: #ff0;
            text-shadow: 0 0 10px rgba(255, 255, 0, 0.5);
        }

        .instructions {
            margin-top: 15px;
            font-size: 0.9rem;
            color: var(--text-secondary);
            background: rgba(15, 23, 42, 0.5);
            padding: 10px;
            border-radius: 10px;
        }

        /* Responsive adjustments */
        @media (max-width: 600px) {
            .controls {
                flex-direction: column;
            }
            
            .score-display {
                flex-direction: column;
                gap: 10px;
            }
            
            .card-title {
                font-size: 1.7rem;
            }
            
            .game-container {
                height: 400px;
            }
        }

        /* Prevent vertical scrolling */
        html, body {
            height: 100%;
            overflow: hidden;
        }
        
        /* Bubble animation */
        @keyframes bubbleFloat {
            0% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
            100% { transform: translateY(0); }
        }
        
        .bubble {
            animation: bubbleFloat 2s infinite;
        }
        
        /* Aiming line */
        #aimLine {
            position: absolute;
            background: rgba(6, 182, 212, 0.7);
            height: 2px;
            transform-origin: 0 0;
            pointer-events: none;
            display: none;
        }
        
        /* Next bubble indicator */
        .next-bubble {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-left: 10px;
            display: inline-block;
            vertical-align: middle;
        }
    </style>
</head>
<body>

<div class="futuristic-card">
    <h3 class="card-title">NEON BUBBLE SHOOTER</h3>

    <div class="score-display">
        <div class="score-item">
            <div class="score-label">SCORE</div>
            <div id="scoreDisplay" class="score-value">0</div>
        </div>
        <div class="score-item">
            <div class="score-label">HIGHSCORE</div>
            <div id="highscoreDisplay" class="score-value">0</div>
        </div>
        <div class="score-item">
            <div class="score-label">NEXT</div>
            <div id="nextBubbleDisplay" class="next-bubble"></div>
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
        <canvas id="gameCanvas" width="400" height="500"></canvas>
        <div id="aimLine"></div>
    </div>

    <div class="instructions">
        Aim with mouse/touch, click to shoot. Match 3+ bubbles of same color to pop them!
    </div>
</div>

<script>
const canvas = document.getElementById("gameCanvas");
const ctx = canvas.getContext("2d");
const aimLine = document.getElementById("aimLine");

// Set canvas size
canvas.width = 400;
canvas.height = 500;

// Game variables
let score = 0;
let highscore = 0;
let gameOver = false;
let gameStarted = false;
let gamePaused = false;
let bubbles = [];
let shooterBubble = null;
let nextBubble = null;
let bubbleRadius = 20;
let rows = 8;
let cols = 10;
let shooting = false;
let aimAngle = 0;

// Bubble colors
const bubbleColors = [
    '#06b6d4', // cyan
    '#ec4899', // pink
    '#a855f7', // purple
    '#10b981', // green
    '#f59e0b', // yellow
    '#ef4444'  // red
];

// Initialize the game
function initGame() {
    bubbles = [];
    score = 0;
    document.getElementById("scoreDisplay").innerText = score;
    
    // Create initial bubble grid
    for (let row = 0; row < rows; row++) {
        for (let col = 0; col < cols; col++) {
            // Skip some bubbles to make it easier
            if (Math.random() > 0.3) {
                const offset = row % 2 === 0 ? 0 : bubbleRadius;
                const x = col * (bubbleRadius * 2) + bubbleRadius + offset;
                const y = row * (bubbleRadius * 1.8) + bubbleRadius + 50;
                
                bubbles.push({
                    x: x,
                    y: y,
                    color: bubbleColors[Math.floor(Math.random() * bubbleColors.length)],
                    row: row,
                    col: col
                });
            }
        }
    }
    
    // Create shooter and next bubble
    createShooterBubble();
    createNextBubble();
}

// Create shooter bubble
function createShooterBubble() {
    shooterBubble = {
        x: canvas.width / 2,
        y: canvas.height - 60,
        color: nextBubble ? nextBubble.color : bubbleColors[Math.floor(Math.random() * bubbleColors.length)],
        dx: 0,
        dy: 0,
        speed: 8,
        active: true
    };
}

// Create next bubble
function createNextBubble() {
    nextBubble = {
        color: bubbleColors[Math.floor(Math.random() * bubbleColors.length)]
    };
    document.getElementById("nextBubbleDisplay").style.backgroundColor = nextBubble.color;
}

// Draw game elements
function draw() {
    // Clear canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    // Draw background
    const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
    gradient.addColorStop(0, "#0f172a");
    gradient.addColorStop(1, "#1e293b");
    ctx.fillStyle = gradient;
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    
    // Draw bubbles
    bubbles.forEach(bubble => {
        drawBubble(bubble.x, bubble.y, bubble.color);
    });
    
    // Draw shooter bubble if not shooting
    if (shooterBubble && !shooting) {
        drawBubble(shooterBubble.x, shooterBubble.y, shooterBubble.color);
        
        // Draw shooter base
        ctx.fillStyle = 'rgba(255, 255, 255, 0.2)';
        ctx.beginPath();
        ctx.arc(shooterBubble.x, shooterBubble.y + 30, 15, 0, Math.PI * 2);
        ctx.fill();
    }
    
    // Draw shooting bubble
    if (shooterBubble && shooting) {
        drawBubble(shooterBubble.x, shooterBubble.y, shooterBubble.color);
    }
    
    // Draw game over text
    if (gameOver) {
        ctx.fillStyle = 'rgba(0, 0, 0, 0.7)';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        ctx.font = '30px Orbitron';
        ctx.fillStyle = '#ec4899';
        ctx.textAlign = 'center';
        ctx.fillText('GAME OVER', canvas.width / 2, canvas.height / 2);
        
        ctx.font = '20px Exo 2';
        ctx.fillStyle = '#06b6d4';
        ctx.fillText(`Score: ${score}`, canvas.width / 2, canvas.height / 2 + 40);
    }
}

// Draw a bubble
function drawBubble(x, y, color) {
    // Bubble gradient
    const gradient = ctx.createRadialGradient(x - 5, y - 5, 1, x, y, bubbleRadius);
    gradient.addColorStop(0, 'white');
    gradient.addColorStop(0.5, color);
    gradient.addColorStop(1, shadeColor(color, -50));
    
    ctx.fillStyle = gradient;
    ctx.beginPath();
    ctx.arc(x, y, bubbleRadius, 0, Math.PI * 2);
    ctx.fill();
    
    // Bubble highlight
    ctx.fillStyle = 'rgba(255, 255, 255, 0.6)';
    ctx.beginPath();
    ctx.arc(x - bubbleRadius/3, y - bubbleRadius/3, bubbleRadius/3, 0, Math.PI * 2);
    ctx.fill();
    
    // Bubble border
    ctx.strokeStyle = 'rgba(255, 255, 255, 0.3)';
    ctx.lineWidth = 2;
    ctx.beginPath();
    ctx.arc(x, y, bubbleRadius, 0, Math.PI * 2);
    ctx.stroke();
}

// Helper function to darken a color
function shadeColor(color, percent) {
    let R = parseInt(color.substring(1, 3), 16);
    let G = parseInt(color.substring(3, 5), 16);
    let B = parseInt(color.substring(5, 7), 16);

    R = parseInt(R * (100 + percent) / 100);
    G = parseInt(G * (100 + percent) / 100);
    B = parseInt(B * (100 + percent) / 100);

    R = (R < 255) ? R : 255;
    G = (G < 255) ? G : 255;
    B = (B < 255) ? B : 255;

    R = (R < 0) ? 0 : R;
    G = (G < 0) ? 0 : G;
    B = (B < 0) ? 0 : B;

    return '#' + 
        ((R.toString(16).length === 1) ? '0' + R.toString(16) : R.toString(16)) +
        ((G.toString(16).length === 1) ? '0' + G.toString(16) : G.toString(16)) +
        ((B.toString(16).length === 1) ? '0' + B.toString(16) : B.toString(16));
}

// Update game state
function update() {
    if (gamePaused || gameOver || !gameStarted) return;
    
    // Update shooting bubble
    if (shooting && shooterBubble) {
        shooterBubble.x += shooterBubble.dx;
        shooterBubble.y += shooterBubble.dy;
        
        // Check for wall collisions
        if (shooterBubble.x - bubbleRadius < 0) {
            shooterBubble.x = bubbleRadius;
            shooterBubble.dx = -shooterBubble.dx;
        } else if (shooterBubble.x + bubbleRadius > canvas.width) {
            shooterBubble.x = canvas.width - bubbleRadius;
            shooterBubble.dx = -shooterBubble.dx;
        }
        
        // Check for ceiling collision
        if (shooterBubble.y - bubbleRadius < 0) {
            shooterBubble.y = bubbleRadius;
            attachBubble();
            return;
        }
        
        // Check for bubble collisions
        for (let i = 0; i < bubbles.length; i++) {
            const bubble = bubbles[i];
            const dx = shooterBubble.x - bubble.x;
            const dy = shooterBubble.y - bubble.y;
            const distance = Math.sqrt(dx * dx + dy * dy);
            
            if (distance < bubbleRadius * 2) {
                attachBubble();
                return;
            }
        }
    }
}

// Attach bubble to the grid
function attachBubble() {
    // Find the closest grid position
    let minDist = Infinity;
    let closestBubble = null;
    
    for (let i = 0; i < bubbles.length; i++) {
        const bubble = bubbles[i];
        const dx = shooterBubble.x - bubble.x;
        const dy = shooterBubble.y - bubble.y;
        const distance = Math.sqrt(dx * dx + dy * dy);
        
        if (distance < minDist) {
            minDist = distance;
            closestBubble = bubble;
        }
    }
    
    // Calculate grid position for new bubble
    if (closestBubble) {
        const row = closestBubble.row;
        const col = closestBubble.col;
        const offset = row % 2 === 0 ? 0 : bubbleRadius;
        
        // Add new bubble to the grid
        bubbles.push({
            x: col * (bubbleRadius * 2) + bubbleRadius + offset,
            y: row * (bubbleRadius * 1.8) + bubbleRadius + 50,
            color: shooterBubble.color,
            row: row,
            col: col
        });
    }
    
    // Check for matches
    checkMatches();
    
    // Reset shooter
    shooting = false;
    shooterBubble = null;
    createShooterBubble();
    
    // Check if game is over (bubbles too low)
    checkGameOver();
}

// Check for matching bubbles
function checkMatches() {
    // This is a simplified version - a real implementation would use
    // a more sophisticated algorithm like flood fill
    
    let matched = [];
    
    // For each bubble, check if it has same-colored neighbors
    bubbles.forEach(bubble => {
        const sameColorNeighbors = bubbles.filter(b => 
            b.color === bubble.color &&
            Math.sqrt(Math.pow(b.x - bubble.x, 2) + Math.pow(b.y - bubble.y, 2)) < bubbleRadius * 2.5
        );
        
        if (sameColorNeighbors.length >= 2) { // 3 including itself
            matched = matched.concat(sameColorNeighbors);
        }
    });
    
    // Remove duplicates
    matched = [...new Set(matched)];
    
    // Remove matched bubbles
    if (matched.length >= 3) {
        bubbles = bubbles.filter(bubble => !matched.includes(bubble));
        
        // Add score
        score += matched.length * 10;
        document.getElementById("scoreDisplay").innerText = score;
    }
}

// Check if game is over
function checkGameOver() {
    // Check if any bubble is too low
    const lowestBubble = bubbles.reduce((lowest, bubble) => 
        bubble.y > lowest ? bubble.y : lowest, 0
    );
    
    if (lowestBubble > canvas.height - 100) {
        gameOver = true;
        
        if (score > highscore) {
            highscore = score;
            document.getElementById("highscoreDisplay").innerText = highscore;
            localStorage.setItem('bubbleHighscore', highscore);
        }
        
        Swal.fire({
            title: 'Game Over!',
            html: `Your score: <b>${score}</b><br>High score: <b>${highscore}</b>`,
            icon: 'info',
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
    gameOver = false;
    initGame();
}

// Start game
function startGame() {
    if (!gameStarted) {
        gameStarted = true;
        gamePaused = false;
        initGame();
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
    }
}

// Game loop
function gameLoop() {
    if (!gameOver) {
        update();
        draw();
        requestAnimationFrame(gameLoop);
    }
}

// Handle mouse movement for aiming
canvas.addEventListener('mousemove', (e) => {
    if (gamePaused || gameOver || !gameStarted) return;
    
    const rect = canvas.getBoundingClientRect();
    const mouseX = e.clientX - rect.left;
    const mouseY = e.clientY - rect.top;
    
    // Calculate angle
    const dx = mouseX - shooterBubble.x;
    const dy = mouseY - shooterBubble.y;
    aimAngle = Math.atan2(dy, dx);
    
    // Show aiming line
    if (!shooting) {
        aimLine.style.display = 'block';
        aimLine.style.width = '100px';
        aimLine.style.left = (shooterBubble.x + rect.left) + 'px';
        aimLine.style.top = (shooterBubble.y + rect.top) + 'px';
        aimLine.style.transform = `rotate(${aimAngle}rad)`;
    }
});

canvas.addEventListener('mouseleave', () => {
    aimLine.style.display = 'none';
});

// Handle shooting
canvas.addEventListener('click', (e) => {
    if (gamePaused || gameOver || !gameStarted || shooting) return;
    
    shooting = true;
    aimLine.style.display = 'none';
    
    // Set bubble velocity based on angle
    shooterBubble.dx = Math.cos(aimAngle) * shooterBubble.speed;
    shooterBubble.dy = Math.sin(aimAngle) * shooterBubble.speed;
});

// Event listeners
document.getElementById("playBtn").addEventListener("click", startGame);
document.getElementById("pauseBtn").addEventListener("click", togglePause);

// Load highscore from localStorage
window.addEventListener('load', function() {
    const savedHighscore = localStorage.getItem('bubbleHighscore');
    if (savedHighscore) {
        highscore = parseInt(savedHighscore);
        document.getElementById("highscoreDisplay").innerText = highscore;
    }
    
    // Draw initial state
    draw();
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