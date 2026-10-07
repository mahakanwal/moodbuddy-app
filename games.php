<?php include_once __DIR__ . '/includes/header.php'; ?>
<?php
// Mood-based games mapping
$moodGames = [
    'happy' => [
        [
            'title' => 'Flappy Bird',
            'description' => 'Navigate through pipes without hitting them',
            'image' => 'flappy.png',
            'link' => 'flappybird.php',
            'color' => 'primary'
        ],
        [
            'title' => 'Snake Game',
            'description' => 'Grow your snake by eating food without hitting walls',
            'image' => 'snake.jpeg',
            'link' => 'snake.php',
            'color' => 'secondary'
        ]
    ],
    'sad' => [
      [
        'title' => 'Bubble Shooter',
        'description' => 'Pop bubbles by matching colors',
        'image' => 'bubble.jpeg',
        'link' => 'bubble.php',
        'color' => 'primary'
    ],
    [
        'title' => 'Car Racing',
        'description' => 'Race against other cars without crashing',
        'image' => 'racing.jpeg',
        'link' => 'car_racing.php',
        'color' => 'secondary'
    ]
    ],
    'angry' => [
      [
        'title' => 'Pong',
        'description' => 'Classic paddle and ball game',
        'image' => 'pong.png',
        'link' => 'pong.php',
        'color' => 'primary'
    ],
    [
        'title' => 'Brick Breaker',
        'description' => 'Break all bricks with your ball',
        'image' => 'brick.jpeg',
        'link' => 'brick.php',
        'color' => 'secondary'
    ]
    ],
    'bored' => [
        [
            'title' => '2048 Puzzle',
            'description' => 'Combine tiles to reach 2048',
            'image' => 'puzzle.jpeg',
            'link' => '2048.php',
            'color' => 'primary'
        ],
        [
            'title' => 'Minesweeper',
            'description' => 'Find all mines without detonating any',
            'image' => 'mine.png',
            'link' => 'minesweeper.php',
            'color' => 'secondary'
        ]
    ],
];

// Get user mood (default: happy)
$userMood = strtolower($_SESSION['mood'] ?? 'happy');
$games = $moodGames[$userMood] ?? $moodGames['happy'];

// All games for the games page
$allGames = [];
foreach ($moodGames as $mood => $gameList) {
    foreach ($gameList as $game) {
        if (!in_array($game, $allGames)) {
            $allGames[] = $game;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Games - MoodBuddy</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- AOS Library CSS -->
  <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
  <style>
    body {
      background: linear-gradient(135deg, #0f1115, #1a1d25);
      font-family: 'Exo 2', sans-serif;
      color: #fff;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    main {
      flex: 1;
    }
    .main-content {
      max-width: 1200px;
      margin: 2rem auto;
      padding: 0 1rem;
    }
  
    .card-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 2rem;
      margin-bottom: 3rem;
    }
    .game-card {
  background: linear-gradient(145deg, rgba(20, 20, 20, 0.95), rgba(0, 0, 0, 0.85));
  backdrop-filter: blur(20px) saturate(150%);
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.7), 0 0 20px rgba(255, 255, 255, 0.05) inset;
  border: 1px solid rgba(255, 255, 255, 0.08);
  transition: transform 0.3s, box-shadow 0.3s;
  position: relative;
  z-index: 1;
  padding: 20px;
}

/* Glossy shiny overlay */
.game-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0));
  z-index: -1;
  border-radius: 20px;
  opacity: 0;
  transition: opacity 0.3s;
}

.game-card:hover::before {
  opacity: 1;
}

    .game-card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      /* padding: 20px; */
      border-radius: 20px;
    }
    .game-card .card-body {
      /* padding: 1.5rem; */
      padding-top:20px;
      position: relative;
    }
    .game-card h3 {
      font-size: 1.3rem;
      margin-bottom: 0.8rem;
      color: #fff;
      font-weight: 600;
    }
    .game-card p {
      font-size: 0.95rem;
      color: rgba(255, 255, 255, 0.8);
      margin-bottom: 1.5rem;
      line-height: 1.5;
    }
    .game-card a {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(90deg, #06b6d4, #3b82f6);
      color: #fff;
      padding: 0.7rem 1.5rem;
      border-radius: 12px;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.3s;
      position: relative;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
    }
    .game-card a:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(6, 182, 212, 0.5);
    }
    .game-card a i {
      margin-right: 8px;
    }
    .game-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 40px rgba(0, 0, 0, 0.7);
    }
    .mood-indicator {
      text-align: center;
      margin-bottom: 2rem;
      font-size: 1.1rem;
      color: #06b6d4;
      background: rgba(6, 182, 212, 0.1);
      padding: 0.8rem 1.5rem;
      border-radius: 50px;
      display: inline-block;
      /* margin-left: 50%; */
      transform: translateX(-50%);
      backdrop-filter: blur(5px);
      border: 1px solid rgba(6, 182, 212, 0.2);
    }
    .section-subtitle {
      font-size: 1.8rem;
      margin: 2.5rem 0 1.5rem;
      color: #fff;
      position: relative;
      padding-left: 1rem;
    }
    .section-subtitle::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      height: 100%;
      width: 4px;
      background: linear-gradient(to bottom, #06b6d4, #3b82f6);
      border-radius: 2px;
    }
    footer {
      background: rgba(17, 17, 17, 0.8);
      backdrop-filter: blur(10px);
      color: #fff;
      text-align: center;
      padding: 15px 0;
      margin-top: auto;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
      .card-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
      }
      .section-title {
        font-size: 2rem;
      }
    }
  </style>
</head>
<body>
  <div class="hero-section">
  <h2 class="hero-title" data-aos="fade-down"> Games Collection</h2>
  <p class="hero-subtitle">Discover personalized music curated for your current vibe</p>
  </div>
  <main class="main-content">
    
    <div class="mood-indicator" data-aos="fade-up" data-aos-delay="200">
      Recommended for your <b><?php echo ucfirst($userMood); ?></b> mood
    </div>
    <div class="card-grid">
      <?php foreach($games as $index => $game): ?>
        <div class="game-card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
          <img src="assets/img/<?php echo htmlspecialchars($game['image']); ?>" alt="<?php echo htmlspecialchars($game['title']); ?>">
          <div class="card-body">
            <h3><?php echo htmlspecialchars($game['title']); ?></h3>
            <p><?php echo htmlspecialchars($game['description']); ?></p>
            <a href="games/<?php echo htmlspecialchars($game['link']); ?>">
              <i class="fas fa-play"></i> Play Now
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <h3 class="section-subtitle" data-aos="fade-right" data-aos-delay="200">All Games</h3>
    <div class="card-grid">
      <?php foreach($allGames as $index => $game): ?>
        <div class="game-card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100 + 300; ?>">
          <img src="assets/img/<?php echo htmlspecialchars($game['image']); ?>" alt="<?php echo htmlspecialchars($game['title']); ?>">
          <div class="card-body">
            <h3><?php echo htmlspecialchars($game['title']); ?></h3>
            <p><?php echo htmlspecialchars($game['description']); ?></p>
            <a href="games/<?php echo htmlspecialchars($game['link']); ?>">
              <i class="fas fa-play"></i> Play Now
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </main>

  <?php include_once __DIR__ . '/includes/footer.php'; ?>

  <!-- AOS Library JS -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    // Initialize AOS
    AOS.init({
      duration: 800,
      easing: 'ease-in-out',
      once: true,
      offset: 100
    });
  </script>
</body>
</html>