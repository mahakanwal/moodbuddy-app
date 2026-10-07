<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cosmic Racer - MoodBuddy</title>
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
        
        /* Speed effect */
        @keyframes speedLines {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(100%); }
        }
        
        .speed-lines {
            position: absolute;
            width: 100%;
            height: 100%;
            pointer-events: none;
            opacity: 0;
        }
        
        .speed-line {
            position: absolute;
            width: 2px;
            height: 20px;
            background: rgba(255, 255, 255, 0.7);
            animation: speedLines 0.5s linear infinite;
        }
    </style>
</head>
<body>

<div class="futuristic-card">
    <h3 class="card-title">COSMIC RACER</h3>

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
            <div class="score-label">SPEED</div>
            <div id="speedDisplay" class="score-value">0 km/h</div>
        </div>
    </div>

    <div class="controls">
        <button id="playBtn" class="futuristic-btn">
            ▶ START RACE
        </button>
        <button id="pauseBtn" class="futuristic-btn" style="display:none;">
            ⏸ PAUSE
        </button>
    </div>

    <div class="game-container">
        <canvas id="gameCanvas" width="400" height="500"></canvas>
        <div id="speedLines" class="speed-lines"></div>
    </div>

    <div class="instructions">
        Use LEFT/RIGHT arrows to move. Avoid obstacles and collect power-ups!
    </div>
</div>

<script>
const canvas = document.getElementById("gameCanvas");
const ctx = canvas.getContext("2d");
const speedLines = document.getElementById("speedLines");

// Set canvas size
canvas.width = 400;
canvas.height = 500;

// Game variables
let frames = 0;
let score = 0;
let highscore = 0;
let gameOver = false;
let gameStarted = false;
let gamePaused = false;
let speed = 0;
let maxSpeed = 200;
let roadWidth = 200;
let roadX = canvas.width / 2 - roadWidth / 2;

// Player car properties
const playerCar = {
    x: canvas.width / 2 - 15,
    y: canvas.height - 100,
    width: 30,
    height: 50,
    color: '#06b6d4',
    speed: 5,
    
    draw: function() {
        // Car body
        ctx.fillStyle = this.color;
        ctx.fillRect(this.x, this.y, this.width, this.height);
        
        // Car details
        ctx.fillStyle = '#ec4899';
        ctx.fillRect(this.x, this.y, this.width, 10); // Top
        ctx.fillRect(this.x, this.y + this.height - 10, this.width, 10); // Bottom
        
        // Headlights
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(this.x + 5, this.y, 5, 5);
        ctx.fillRect(this.x + this.width - 10, this.y, 5, 5);
        
        // Wheels
        ctx.fillStyle = '#333333';
        ctx.fillRect(this.x - 5, this.y + 10, 5, 15);
        ctx.fillRect(this.x + this.width, this.y + 10, 5, 15);
        ctx.fillRect(this.x - 5, this.y + this.height - 25, 5, 15);
        ctx.fillRect(this.x + this.width, this.y + this.height - 25, 5, 15);
    },
    
    update: function() {
        // Boundary checking
        if (this.x < roadX) this.x = roadX;
        if (this.x + this.width > roadX + roadWidth) this.x = roadX + roadWidth - this.width;
    },
    
    moveLeft: function() {
        if (gameStarted && !gamePaused) this.x -= this.speed;
    },
    
    moveRight: function() {
        if (gameStarted && !gamePaused) this.x += this.speed;
    },
    
    reset: function() {
        this.x = canvas.width / 2 - 15;
        this.y = canvas.height - 100;
    }
};

// Obstacles properties
const obstacles = {
    items: [],
    minWidth: 30,
    maxWidth: 70,
    minHeight: 40,
    maxHeight: 60,
    minSpeed: 2,
    maxSpeed: 6,
    spawnRate: 100, // frames between spawns
    
    draw: function() {
        for (let i = 0; i < this.items.length; i++) {
            let o = this.items[i];
            
            // Obstacle body
            ctx.fillStyle = o.color;
            ctx.fillRect(o.x, o.y, o.width, o.height);
            
            // Obstacle details
            ctx.fillStyle = '#000000';
            ctx.fillRect(o.x + 5, o.y + 5, 10, 5);
            ctx.fillRect(o.x + o.width - 15, o.y + 5, 10, 5);
        }
    },
    
    update: function() {
        if (gameStarted && !gamePaused) {
            // Spawn new obstacles
            if (frames % Math.max(20, this.spawnRate - Math.floor(speed/10)) === 0) {
                const width = this.minWidth + Math.random() * (this.maxWidth - this.minWidth);
                this.items.push({
                    x: roadX + Math.random() * (roadWidth - width),
                    y: -50,
                    width: width,
                    height: this.minHeight + Math.random() * (this.maxHeight - this.minHeight),
                    speed: this.minSpeed + Math.random() * (this.maxSpeed - this.minSpeed),
                    color: ['#ec4899', '#db2777', '#a855f7', '#f43f5e'][Math.floor(Math.random() * 4)]
                });
            }
            
            for (let i = 0; i < this.items.length; i++) {
                let o = this.items[i];
                
                // Move obstacles down
                o.y += o.speed + speed/30;
                
                // Remove obstacles that are off screen
                if (o.y > canvas.height) {
                    this.items.splice(i, 1);
                    i--;
                    score++;
                    document.getElementById("scoreDisplay").innerText = score;
                    continue;
                }
                
                // Collision detection
                if (playerCar.x < o.x + o.width &&
                    playerCar.x + playerCar.width > o.x &&
                    playerCar.y < o.y + o.height &&
                    playerCar.y + playerCar.height > o.y) {
                    endGame();
                }
            }
        }
    },
    
    reset: function() {
        this.items = [];
    }
};

// Road markings
const roadMarkings = {
    markings: [],
    width: 10,
    height: 30,
    gap: 40,
    
    draw: function() {
        ctx.fillStyle = '#ffffff';
        for (let i = 0; i < this.markings.length; i++) {
            let m = this.markings[i];
            ctx.fillRect(m.x, m.y, this.width, this.height);
        }
    },
    
    update: function() {
        if (gameStarted && !gamePaused) {
            // Create new markings if needed
            if (this.markings.length === 0 || this.markings[this.markings.length-1].y > this.gap) {
                this.markings.push({
                    x: roadX + roadWidth/2 - this.width/2,
                    y: -this.height
                });
            }
            
            for (let i = 0; i < this.markings.length; i++) {
                let m = this.markings[i];
                
                // Move markings down
                m.y += 3 + speed/20;
                
                // Remove markings that are off screen
                if (m.y > canvas.height) {
                    this.markings.splice(i, 1);
                    i--;
                }
            }
        }
    },
    
    reset: function() {
        this.markings = [];
    }
};

// Draw game scene
function drawScene() {
    // Draw road
    ctx.fillStyle = '#334155';
    ctx.fillRect(roadX, 0, roadWidth, canvas.height);
    
    // Draw road borders
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(roadX - 5, 0, 5, canvas.height);
    ctx.fillRect(roadX + roadWidth, 0, 5, canvas.height);
    
    // Draw road markings
    roadMarkings.draw();
    
    // Draw obstacles
    obstacles.draw();
    
    // Draw player car
    playerCar.draw();
    
    // Draw speed effect
    if (speed > 100) {
        const intensity = Math.min(1, (speed - 100) / 100);
        speedLines.style.opacity = intensity;
        
        // Create speed lines if needed
        if (speedLines.children.length < 20 * intensity) {
            const line = document.createElement('div');
            line.className = 'speed-line';
            line.style.left = Math.random() * 100 + '%';
            line.style.animationDuration = (0.3 + Math.random() * 0.4) + 's';
            speedLines.appendChild(line);
            
            // Remove lines that finish animation
            setTimeout(() => {
                if (line.parentNode === speedLines) {
                    speedLines.removeChild(line);
                }
            }, 500);
        }
    } else {
        speedLines.style.opacity = 0;
    }
}

// Game loop
function loop() {
    // Clear canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    // Draw scene
    drawScene();
    
    // Update game state
    playerCar.update();
    obstacles.update();
    roadMarkings.update();
    
    // Gradually increase speed
    if (gameStarted && !gamePaused && speed < maxSpeed) {
        speed += 0.1;
        document.getElementById("speedDisplay").innerText = Math.floor(speed) + " km/h";
    }
    
    frames++;
    
    if (!gameOver) {
        requestAnimationFrame(loop);
    }
}

// Start game with countdown
function startGame() {
    if (!gameStarted) {
        gameStarted = true;
        gameOver = false;
        gamePaused = false;
        score = 0;
        speed = 0;
        document.getElementById("scoreDisplay").innerText = score;
        document.getElementById("speedDisplay").innerText = "0 km/h";
        playerCar.reset();
        obstacles.reset();
        roadMarkings.reset();
        frames = 0;
        
        document.getElementById("playBtn").style.display = "none";
        document.getElementById("pauseBtn").style.display = "block";

        let countdown = 3;
        Swal.fire({
            title: 'Get Ready!',
            html: `Starting in <b>${countdown}</b>`,
            timer: 3000,
            timerProgressBar: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            background: 'var(--card-bg)',
            color: 'var(--text-primary)',
            didOpen: () => {
                const b = Swal.getHtmlContainer().querySelector('b');
                const interval = setInterval(() => {
                    countdown--;
                    b.textContent = countdown;
                    if (countdown <= 0) clearInterval(interval);
                }, 1000);
            }
        }).then(() => {
            loop();
        });
    }
}

// Toggle pause
function togglePause() {
    if (gameStarted && !gameOver) {
        gamePaused = !gamePaused;
        document.getElementById("pauseBtn").innerHTML = gamePaused ? "▶ RESUME" : "⏸ PAUSE";
        
        if (!gamePaused) {
            loop();
        }
    }
}

// End game
function endGame() {
    gameOver = true;
    gameStarted = false;
    gamePaused = false;
    
    document.getElementById("playBtn").style.display = "block";
    document.getElementById("pauseBtn").style.display = "none";
    
    if (score > highscore) {
        highscore = score;
        document.getElementById("highscoreDisplay").innerText = highscore;
        localStorage.setItem('racerHighscore', highscore);
    }
    
    Swal.fire({
        title: 'Crash!',
        html: `Your score: <b>${score}</b><br>High score: <b>${highscore}</b>`,
        icon: 'info',
        confirmButtonText: 'Race Again',
        background: 'var(--card-bg)',
        color: 'var(--text-primary)',
        confirmButtonColor: 'var(--primary-color)'
    }).then(() => {
        startGame();
    });
}

// Event listeners
document.getElementById("playBtn").addEventListener("click", startGame);
document.getElementById("pauseBtn").addEventListener("click", togglePause);

document.addEventListener("keydown", function(e) {
    if (e.code === "ArrowLeft") {
        playerCar.moveLeft();
    } else if (e.code === "ArrowRight") {
        playerCar.moveRight();
    } else if (e.code === "Space") {
        e.preventDefault();
        if (!gameStarted && !gameOver) {
            startGame();
        } else if (gameStarted && !gameOver) {
            togglePause();
        }
    } else if (e.code === "Escape" && gameStarted && !gameOver) {
        togglePause();
    }
});

// Load highscore from localStorage
window.addEventListener('load', function() {
    const savedHighscore = localStorage.getItem('racerHighscore');
    if (savedHighscore) {
        highscore = parseInt(savedHighscore);
        document.getElementById("highscoreDisplay").innerText = highscore;
    }
    
    // Draw initial scene
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