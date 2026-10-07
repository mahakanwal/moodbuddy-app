<?php include_once __DIR__ . '/includes/header.php'; ?>
    <!-- Hero Section -->
    <section class="hero-section">
        <h1 class="hero-title">Your <?= ucfirst($mood) ?> Mood Journey</h1>
        <p class="hero-subtitle">Discover personalized recommendations crafted by AI for your current vibe</p>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Products Section -->
        <section class="fade-in">
        <h2 class="section-title">Curated Products</h2>
        
        <div class="carousel-container">
            <div class="carousel-track" id="carouselTrack">
                <?php foreach($products as $product): ?>
                    <div class="futuristic-card">
                        <img src="admin/uploads/products/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['title']) ?>" class="card-image">
                        <h3 class="card-title"><?= htmlspecialchars($product['title']) ?></h3>
                        <div class="card-price">$<?= isset($product['price']) ? $product['price'] : '99.99' ?></div>
                        <div class="card-hover-overlay">
                            <a href="single_product.php?id=<?= $product['id'] ?>" class="futuristic-btn">
                                <i class="fas fa-eye"></i> Details
                            </a>
                            <button class="futuristic-btn-secondary" onclick="addToCart(<?= $product['id'] ?>)">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="carousel-nav">
                <button class="carousel-btn" id="prevBtn">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="carousel-btn" id="nextBtn">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            
            <div class="carousel-indicators" id="carouselIndicators">
                <!-- Indicators will be added by JavaScript -->
            </div>
        </div>
        </section>
        <!-- Songs Section -->
        <section class="fade-in">
            <h2 class="section-title">Mood Soundtrack</h2>
            <div class="card-grid">
            <?php foreach($songs as $song): ?>
    <div class="futuristic-card">
        <h3 class="card-title"><?= htmlspecialchars($song['title']) ?></h3>

        <!-- Listen Now button -->
        <button class="futuristic-btn" onclick="showPlayer(this)"> Listen Now</button>

        <!-- Hidden Audio Player -->
        <div class="audio-wrapper" style="display:none; margin-top:10px;">
            <audio controls class="audio-player">
                <source src="admin/uploads/songs/<?= rawurlencode($song['file_path']) ?>" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio>
        </div>
    </div>
<?php endforeach; ?>

            </div>
        </section>
        <?php
// Mood comes from the session (set by home.php / chat.php / webcam.php) - header.php already loaded it
$mood   = $mood ?? ($_SESSION['mood'] ?? 'happy');
$gender = $gender ?? ($_SESSION['gender'] ?? '');

$quotes = [];
$cacheKey = 'ai_quotes_' . strtolower($mood);

// Cache quotes per mood for this session (saves API calls and makes the page faster)
if (!empty($_SESSION[$cacheKey]) && is_array($_SESSION[$cacheKey])) {
    $quotes = $_SESSION[$cacheKey];
} else {
    $api_key = app_env('GEMINI_API_KEY');
    if ($api_key) {
        $model = app_env('GEMINI_MODEL', 'gemini-2.5-flash');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent";

        $prompt = "Give me 6 short, unique and modern inspirational quotes for someone feeling $mood. "
                . "Return each quote on a new line, without numbering, bullets or extra text.";

        $data = ["contents" => [["parts" => [["text" => $prompt]]]]];

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($data),
            CURLOPT_HTTPHEADER     => ["Content-Type: application/json", "x-goog-api-key: $api_key"],
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT        => 12,
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode((string)$response, true);
        $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
        foreach (preg_split("/\r\n|\n|\r/", trim($text)) as $line) {
            $line = trim(preg_replace('/^\s*(\d+[.)]|[-*•])\s*/u', '', $line), " \t\"*");
            if ($line !== '') $quotes[] = $line;
        }
        $quotes = array_slice($quotes, 0, 6);
    }

    // Fallback 1: quotes saved by admin in the database for this mood
    if (empty($quotes)) {
        try {
            $qs = $pdo->prepare("SELECT text, author FROM quotes WHERE mood = ? ORDER BY RAND() LIMIT 6");
            $qs->execute([$mood]);
            foreach ($qs->fetchAll(PDO::FETCH_ASSOC) as $q) {
                $quotes[] = $q['text'] . (!empty($q['author']) ? ' - ' . $q['author'] : '');
            }
        } catch (Throwable $e) { /* ignore */ }
    }

    // Fallback 2: built-in quotes so the section is never empty
    if (empty($quotes)) {
        $quotes = [
            "Every day may not be good, but there is something good in every day.",
            "Feelings are visitors. Let them come and go.",
            "Small steps every day add up to big changes.",
            "You are allowed to be both a masterpiece and a work in progress.",
            "Breathe. You are doing better than you think.",
            "This moment is not your whole story.",
        ];
    } else {
        $_SESSION[$cacheKey] = $quotes;
    }
}
?>

<!-- Quotes Section -->
<section class="fade-in">
    <h2 class="section-title">Inspirational Wisdom</h2>
    <div class="card-grid">
        <?php foreach ($quotes as $quote): ?>
            <div class="futuristic-card">
                <p class="quote-text"><?= htmlspecialchars($quote) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php
// Mood -> Subreddit mapping
$subs = [
    'happy'   => 'wholesomememes',
    'sad'     => 'wholesomememes',
    'angry'   => 'funny',
    'anxious' => 'wholesomememes',
    'excited' => 'memes',
    'calm'    => 'wholesomememes',
];

$subreddit = $subs[strtolower($mood)] ?? 'memes';
$memes = [];

// Remote memes (with a short timeout so a slow API never blocks the page)
$ctx = stream_context_create(['http' => ['timeout' => 6, 'header' => "User-Agent: MoodBuddy/1.0\r\n"]]);
$response = @file_get_contents("https://meme-api.com/gimme/$subreddit/6", false, $ctx);
$data = json_decode((string)$response, true);
if (!empty($data['memes'])) {
    foreach ($data['memes'] as $m) {
        if (!empty($m['nsfw'])) continue; // keep it safe
        $memes[] = ['image' => $m['url']];
    }
}

// Fallback: memes uploaded by admin for this mood
if (empty($memes)) {
    try {
        $ms = $pdo->prepare("SELECT image FROM memes WHERE mood = ? ORDER BY RAND() LIMIT 6");
        $ms->execute([$mood]);
        foreach ($ms->fetchAll(PDO::FETCH_ASSOC) as $m) {
            $memes[] = ['image' => 'admin/' . $m['image']];
        }
    } catch (Throwable $e) { /* ignore */ }
}
?>

<!-- Memes Section -->
<section class="fade-in">
    <h2 class="section-title">Mood Boosters</h2>
    <div class="card-grid">
        <?php if (!empty($memes)): ?>
            <?php foreach($memes as $meme): ?>
                <div class="futuristic-card">
                    <img src="<?= htmlspecialchars($meme['image']) ?>" alt="Mood Meme" class="card-image" loading="lazy">
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No memes found. Try again later.</p>
        <?php endif; ?>
    </div>
</section>
<?php



// Mood-based games mapping (5 moods × 2 games)
$moodGames = [
    'happy' => [
        [
            'title' => 'Flappy Bird',
            'embed' => '<iframe src="games/flappybird.php" width="500" height="800" style="border:none;"></iframe>'
        ],
        [
            'title' => 'Car Racing',
            'embed' => '<iframe src="games/car_racing.php" width="500" height="800" style="border:none;"></iframe>'
        ],
        [
            'title' => 'Snake Game',
            'embed' => '<iframe src="games/snake.php" width="500" height="800" style="border:none;"></iframe>'
        ],
        [
            'title' => 'Bubble Shooter',
            'embed' => '<iframe src="games/bubble.php" width="500" height="800" style="border:none;"></iframe>'
        ],
        
    ],
    'sad' => [
        [
            'title' => 'Car Racing',
            'embed' => '<iframe src="games/car_racing.php" width="500" height="800" style="border:none;"></iframe>'
        ],
        [
            'title' => 'Flappy Bird',
            'embed' => '<iframe src="games/flappybird.php" width="500" height="800" style="border:none;"></iframe>'
        ]
    ],
    'angry' => [
        [
            'title' => 'Snake Game',
            'embed' => '<iframe src="games/snake.php" width="500" height="800" style="border:none;"></iframe>'
        ],
        [
            'title' => 'Flappy Bird',
            'embed' => '<iframe src="games/flappybird.php" width="500" height="800" style="border:none;"></iframe>'
        ]
    ],
    'bored' => [
        [
            'title' => 'Snake Game',
            'embed' => '<iframe src="games/snake.php" width="500" height="800" style="border:none;"></iframe>'
        ],
        [
            'title' => 'Flappy Bird',
            'embed' => '<iframe src="games/flappybird.php" width="500" height="800" style="border:none;"></iframe>'
        ]
    ],
    'romantic' => [
        [
            'title' => 'Snake Game',
            'embed' => '<iframe src="games/snake.php" width="500" height="800" style="border:none;"></iframe>'
        ],
        [
            'title' => 'Brick Breaker',
            'embed' => '<iframe src="games/flappybird.php" width="500" height="800" style="border:none;"></iframe>'
        ]
    ]
];

// Get user mood (default: happy)
$userMood = strtolower($_SESSION['mood'] ?? 'happy');
$games = $moodGames[$userMood] ?? $moodGames['happy'];
?>

<section class="fade-in">
    <h2 class="section-title">Interactive Experiences</h2>
    <div class="card-grid" style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
        <?php foreach(array_slice($games, 0, 4) as $game): ?>
            <div class="futuristic-card" style="flex: 1; min-width: 400px; max-width: 500px;">
                <h3 class="card-title"><?= $game['title'] ?></h3>
                <div class="game-container">
                    <?= $game['embed'] ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>



    </main>
    <?php include_once __DIR__ . '/includes/footer.php'; ?>


</body>
</html>
