<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Snake Game - MoodBuddy</title>
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
}

#gameCanvas {
    display: block;
    background: #0f172a;
}

.controls {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    gap: 10px;
}

select, button {
    padding: 10px 15px;
    border-radius: 25px;
    border: none;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-family: 'Exo 2', sans-serif;
}

select { 
    background: rgba(30, 41, 59, 0.8);
    color: var(--text-primary);
    border: 1px solid var(--border-light);
    flex: 1;
}

select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 10px var(--glow-blue);
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
}

/* Prevent vertical scrolling */
html, body {
    height: 100%;
    overflow: hidden;
}
</style>
</head>
<body>

<div class="futuristic-card">
    <h3 class="card-title">SNAKE GAME</h3>

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
        <select id="difficultySelect">
            <option value="150">Easy</option>
            <option value="100" selected>Medium</option>
            <option value="50">Hard</option>
        </select>
        <button id="playBtn" class="futuristic-btn">
            ▶ PLAY NOW
        </button>
    </div>

    <div class="game-container">
        <canvas id="gameCanvas" width="400" height="400"></canvas>
    </div>

    <div class="instructions">
        Use arrow keys to control the snake. Collect the red food to grow and earn points!
    </div>
</div>

<script>
const canvas = document.getElementById("gameCanvas");
const ctx = canvas.getContext("2d");
const box = 20;
let snake = [], food, dir, gameInterval, score = 0, highscore = 0;
let speed = parseInt(document.getElementById("difficultySelect").value);
let gameRunning = false;

// Set fixed canvas size
canvas.width = 400;
canvas.height = 400;

// Spawn Food
function spawnFood() {
    // Make sure food doesn't spawn on snake
    let foodOnSnake;
    do {
        food = {
            x: Math.floor(Math.random() * (canvas.width / box)) * box,
            y: Math.floor(Math.random() * (canvas.height / box)) * box
        };
        
        // Check if food is on snake
        foodOnSnake = snake.some(segment => segment.x === food.x && segment.y === food.y);
    } while (foodOnSnake);
}

// Draw Snake & Food with better graphics
function drawSnake() {
    // Draw snake body
    snake.forEach((s, index) => {
        if (index === 0) {
            // Snake head
            ctx.fillStyle = "#06b6d4";
            ctx.fillRect(s.x, s.y, box, box);
            
            // Eyes
            ctx.fillStyle = "#000";
            if (dir === "RIGHT") {
                ctx.fillRect(s.x + box - 5, s.y + 5, 3, 3);
                ctx.fillRect(s.x + box - 5, s.y + box - 8, 3, 3);
            } else if (dir === "LEFT") {
                ctx.fillRect(s.x + 2, s.y + 5, 3, 3);
                ctx.fillRect(s.x + 2, s.y + box - 8, 3, 3);
            } else if (dir === "UP") {
                ctx.fillRect(s.x + 5, s.y + 2, 3, 3);
                ctx.fillRect(s.x + box - 8, s.y + 2, 3, 3);
            } else if (dir === "DOWN") {
                ctx.fillRect(s.x + 5, s.y + box - 5, 3, 3);
                ctx.fillRect(s.x + box - 8, s.y + box - 5, 3, 3);
            }
        } else {
            // Snake body with gradient
            const gradient = ctx.createLinearGradient(s.x, s.y, s.x + box, s.y + box);
            gradient.addColorStop(0, "#10b981");
            gradient.addColorStop(1, "#0ea5e9");
            ctx.fillStyle = gradient;
            ctx.fillRect(s.x, s.y, box, box);
        }
        
        // Border
        ctx.strokeStyle = "#000";
        ctx.lineWidth = 1;
        ctx.strokeRect(s.x, s.y, box, box);
    });
}

function drawFood() {
    // Draw food with glow effect
    ctx.fillStyle = "#ef4444";
    ctx.beginPath();
    ctx.arc(food.x + box/2, food.y + box/2, box/2 - 2, 0, Math.PI * 2);
    ctx.fill();
    
    // Inner highlight
    ctx.fillStyle = "#f87171";
    ctx.beginPath();
    ctx.arc(food.x + box/2 - 2, food.y + box/2 - 2, box/4, 0, Math.PI * 2);
    ctx.fill();
}

// Reset Game with Countdown
function resetGame() {
    snake = [{x: box * 5, y: box * 5}];
    dir = "RIGHT";
    spawnFood();
    clearInterval(gameInterval);
    score = 0;
    document.getElementById("scoreDisplay").innerText = score;
    speed = parseInt(document.getElementById("difficultySelect").value);

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
        // ✅ countdown ke baad hi game start hoga
        gameRunning = true;
        gameInterval = setInterval(gameLoop, speed);
    });
}


// Check collision with self
function checkSelfCollision(head) {
    // Check if head collides with any part of the body
    // Skip the first segment (head itself)
    for (let i = 1; i < snake.length; i++) {
        if (snake[i].x === head.x && snake[i].y === head.y) {
            return true;
        }
    }
    return false;
}

// Game Over
function gameOver() {
    gameRunning = false;
    clearInterval(gameInterval);

    let newRecord = false;
    if (score > highscore) {
        highscore = score;
        document.getElementById("highscoreDisplay").innerText = highscore;
        newRecord = true;
        
        // Save highscore to localStorage
        localStorage.setItem('snakeHighscore', highscore);
    }

    let msg = `Your score: ${score}`;
    if (newRecord) msg += "\n🎉 New High Score!";

    Swal.fire({
        icon: 'info',
        title: 'GAME OVER',
        text: msg,
        confirmButtonText: 'Play Again',
        background: 'var(--card-bg)',
        color: 'var(--text-primary)',
        confirmButtonColor: 'var(--primary-color)'
    }).then(() => resetGame());
}

function gameLoop() {
    if (!gameRunning) return;
    
    // Clear canvas
    ctx.fillStyle = '#0f172a';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    
    // Grid
    ctx.strokeStyle = 'rgba(255, 255, 255, 0.05)';
    ctx.lineWidth = 0.5;
    for (let x = 0; x <= canvas.width; x += box) {
        ctx.beginPath();
        ctx.moveTo(x, 0);
        ctx.lineTo(x, canvas.height);
        ctx.stroke();
    }
    for (let y = 0; y <= canvas.height; y += box) {
        ctx.beginPath();
        ctx.moveTo(0, y);
        ctx.lineTo(canvas.width, y);
        ctx.stroke();
    }
    
    let head = {x: snake[0].x, y: snake[0].y};
    if (dir === "LEFT") head.x -= box;
    if (dir === "RIGHT") head.x += box;
    if (dir === "UP") head.y -= box;
    if (dir === "DOWN") head.y += box;

    // ✅ Wall collision check (no wrap-around)
    if (head.x < 0 || head.x >= canvas.width || head.y < 0 || head.y >= canvas.height) {
        gameOver();
        return;
    }

    // Self collision
    if (checkSelfCollision(head)) {
        gameOver();
        return;
    }

    snake.unshift(head);

    // Eat food
    if (head.x === food.x && head.y === food.y) {
        spawnFood();
        score++;
        document.getElementById("scoreDisplay").innerText = score;
    } else {
        snake.pop();
    }

    drawSnake();
    drawFood();
}


document.addEventListener("keydown", function(event) {
    if (["ArrowLeft", "ArrowRight", "ArrowUp", "ArrowDown", " "].includes(event.key)) {
        event.preventDefault(); // 👈 stop page scrolling
    }

    if (event.key === "ArrowLeft" && dir !== "RIGHT") {
        dir = "LEFT";
    } else if (event.key === "ArrowUp" && dir !== "DOWN") {
        dir = "UP";
    } else if (event.key === "ArrowRight" && dir !== "LEFT") {
        dir = "RIGHT";
    } else if (event.key === "ArrowDown" && dir !== "UP") {
        dir = "DOWN";
    } else if (event.key === " " && !gameRunning) {
        startGame();
    }
});

// Play button
document.getElementById("playBtn").addEventListener("click", resetGame);

// Difficulty change
document.getElementById("difficultySelect").addEventListener("change", function() {
    speed = parseInt(this.value);
    if (gameRunning) {
        clearInterval(gameInterval);
        gameInterval = setInterval(gameLoop, speed);
    }
});

// Load highscore from localStorage
window.addEventListener('load', function() {
    const savedHighscore = localStorage.getItem('snakeHighscore');
    if (savedHighscore) {
        highscore = parseInt(savedHighscore);
        document.getElementById("highscoreDisplay").innerText = highscore;
    }
});
</script>

</body>
</html>