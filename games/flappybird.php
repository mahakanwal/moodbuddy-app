<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flappy Bird - MoodBuddy</title>
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
            background: linear-gradient(to bottom, #06b6d4, #ec4899);
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
        
        /* Bird animation */
        @keyframes fly {
            0% { transform: translateY(0) rotate(0); }
            50% { transform: translateY(-5px) rotate(-5deg); }
            100% { transform: translateY(0) rotate(0); }
        }
        
        .bird {
            animation: fly 0.5s infinite;
        }
    </style>
</head>
<body>

<div class="futuristic-card">
    <h3 class="card-title">FLAPPY BIRD</h3>

    <div class="score-display">
        <div class="score-item">
            <div class="score-label">SCORE</div>
            <div id="scoreDisplay" class="score-value">0</div>
        </div>
        <div class="score-item">
            <div class="score-label">HIGHSCORE</div>
            <div id="highscoreDisplay" class="score-value">0</div>
        </div>
    </div>

    <div class="controls">
        <button id="playBtn" class="futuristic-btn">
            ▶ PLAY NOW
        </button>
    </div>

    <div class="game-container">
        <canvas id="gameCanvas" width="400" height="500"></canvas>
    </div>

    <div class="instructions">
        Click or press SPACE to flap. Avoid the pipes and don't hit the ground!
    </div>
</div>

<script>
const canvas = document.getElementById("gameCanvas");
const ctx = canvas.getContext("2d");

// Set canvas size
canvas.width = 400;
canvas.height = 500;

// Game variables
let frames = 0;
let score = 0;
let highscore = 0;
let gameOver = false;
let gameStarted = false;

// Bird properties
const bird = {
    x: 50,
    y: canvas.height / 2,
    width: 34,
    height: 24,
    gravity: 0.5,
    velocity: 0,
    jump: 8,
    
    draw: function() {
        // Draw bird body
        ctx.fillStyle = "#06b6d4";
        ctx.beginPath();
        ctx.arc(this.x, this.y, 15, 0, Math.PI * 2);
        ctx.fill();
        
        // Draw bird eye
        ctx.fillStyle = "white";
        ctx.beginPath();
        ctx.arc(this.x + 8, this.y - 5, 5, 0, Math.PI * 2);
        ctx.fill();
        
        ctx.fillStyle = "black";
        ctx.beginPath();
        ctx.arc(this.x + 9, this.y - 5, 2, 0, Math.PI * 2);
        ctx.fill();
        
        // Draw bird beak
        ctx.fillStyle = "#ff8c00";
        ctx.beginPath();
        ctx.moveTo(this.x + 15, this.y);
        ctx.lineTo(this.x + 30, this.y);
        ctx.lineTo(this.x + 15, this.y + 5);
        ctx.fill();
        
        // Draw bird wing
        ctx.fillStyle = "#0891b2";
        ctx.beginPath();
        ctx.ellipse(this.x - 5, this.y + 5, 10, 7, 0, 0, Math.PI * 2);
        ctx.fill();
    },
    
    update: function() {
        if (gameStarted) {
            this.velocity += this.gravity;
            this.y += this.velocity;
            
            // Prevent bird from going above the canvas
            if (this.y < 0) {
                this.y = 0;
                this.velocity = 0;
            }
            
            // Game over if bird hits the ground
            if (this.y + this.height >= canvas.height - 80) {
                this.y = canvas.height - 80 - this.height;
                if (!gameOver) {
                    endGame();
                }
            }
            
            // Rotate bird based on velocity
            let rotation = this.velocity * 0.5;
            if (rotation > 20) rotation = 20;
            if (rotation < -20) rotation = -20;
        }
    },
    
    flap: function() {
        if (gameStarted) {
            this.velocity = -this.jump;
        }
    },
    
    reset: function() {
        this.y = canvas.height / 2;
        this.velocity = 0;
    }
};

// Pipes properties
const pipes = {
    position: [],
    gap: 150,
    maxYPos: -100,
    dx: 2,
    
    draw: function() {
        for (let i = 0; i < this.position.length; i++) {
            let p = this.position[i];
            
            // Top pipe
            ctx.fillStyle = "#ec4899";
            ctx.fillRect(p.x, p.y, p.width, p.height);
            
            // Pipe cap
            ctx.fillStyle = "#db2777";
            ctx.fillRect(p.x - 3, p.y + p.height - 20, p.width + 6, 20);
            
            // Bottom pipe
            ctx.fillStyle = "#06b6d4";
            ctx.fillRect(p.x, p.y + p.height + this.gap, p.width, canvas.height);
            
            // Bottom pipe cap
            ctx.fillStyle = "#0891b2";
            ctx.fillRect(p.x - 3, p.y + p.height + this.gap, p.width + 6, 20);
        }
    },
    
    update: function() {
        if (gameStarted && !gameOver) {
            if (frames % 100 === 0) {
                this.position.push({
                    x: canvas.width,
                    y: this.maxYPos * (Math.random() + 1),
                    width: 50,
                    height: 250
                });
            }
            
            for (let i = 0; i < this.position.length; i++) {
                let p = this.position[i];
                
                // Move pipes to the left
                p.x -= this.dx;
                
                // If pipes move out of the canvas, remove them and add score
                if (p.x + p.width <= 0) {
                    this.position.shift();
                    score++;
                    document.getElementById("scoreDisplay").innerText = score;
                }
                
                // Collision detection with top pipe
                if (bird.x + bird.width > p.x && 
                    bird.x < p.x + p.width && 
                    bird.y < p.y + p.height) {
                    endGame();
                }
                
                // Collision detection with bottom pipe
                if (bird.x + bird.width > p.x && 
                    bird.x < p.x + p.width && 
                    bird.y + bird.height > p.y + p.height + this.gap) {
                    endGame();
                }
            }
        }
    },
    
    reset: function() {
        this.position = [];
    }
};

// Draw background
function drawBackground() {
    // Draw sky gradient
    const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
    gradient.addColorStop(0, "#06b6d4");
    gradient.addColorStop(1, "#ec4899");
    ctx.fillStyle = gradient;
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    
    // Draw clouds
    ctx.fillStyle = "rgba(255, 255, 255, 0.7)";
    ctx.beginPath();
    ctx.arc(100, 80, 30, 0, Math.PI * 2);
    ctx.arc(130, 70, 35, 0, Math.PI * 2);
    ctx.arc(160, 85, 25, 0, Math.PI * 2);
    ctx.fill();
    
    ctx.beginPath();
    ctx.arc(300, 120, 25, 0, Math.PI * 2);
    ctx.arc(330, 110, 30, 0, Math.PI * 2);
    ctx.arc(360, 125, 20, 0, Math.PI * 2);
    ctx.fill();
    
    // Draw ground
    ctx.fillStyle = "#a855f7";
    ctx.fillRect(0, canvas.height - 80, canvas.width, 80);
    
    // Draw grass
    ctx.fillStyle = "#10b981";
    ctx.fillRect(0, canvas.height - 80, canvas.width, 10);
}

// Game loop
function loop() {
    // Clear canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    // Draw background
    drawBackground();
    
    // Draw pipes
    pipes.draw();
    
    // Draw bird
    bird.draw();
    
    // Update game state
    bird.update();
    pipes.update();
    
    frames++;
    
    if (!gameOver) {
        requestAnimationFrame(loop);
    }
}

// Start game with 5 sec countdown
function startGame() {
    if (!gameStarted) {
        gameStarted = true;
        gameOver = false;
        score = 0;
        document.getElementById("scoreDisplay").innerText = score;
        bird.reset();
        pipes.reset();
        frames = 0;

        let countdown = 5;
        Swal.fire({
            title: 'Get Ready!',
            html: `<b>${countdown}</b> seconds`,
            timer: 5000,
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
            loop(); // 👈 countdown ke baad game start
        });
    }
}

// End game
function endGame() {
    gameOver = true;
    gameStarted = false;
    
    if (score > highscore) {
        highscore = score;
        document.getElementById("highscoreDisplay").innerText = highscore;
        localStorage.setItem('flappyHighscore', highscore);
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
        startGame();
    });
}

// Event listeners
document.getElementById("playBtn").addEventListener("click", startGame);

canvas.addEventListener("click", function() {
    if (gameStarted) {
        bird.flap();
    } else if (!gameOver) {
        startGame();
    }
});

document.addEventListener("keydown", function(e) {
    if (e.code === "Space") {
        e.preventDefault(); // Prevent spacebar from scrolling
        if (gameStarted) {
            bird.flap();
        } else if (!gameOver) {
            startGame();
        }
    }
});

// Load highscore from localStorage
window.addEventListener('load', function() {
    const savedHighscore = localStorage.getItem('flappyHighscore');
    if (savedHighscore) {
        highscore = parseInt(savedHighscore);
        document.getElementById("highscoreDisplay").innerText = highscore;
    }
    
    // Draw initial state
    drawBackground();
    bird.draw();
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