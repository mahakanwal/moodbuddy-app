<?php include_once __DIR__ . '/includes/header.php'; ?>
<?php

require_once __DIR__ . '/includes/db.php';

// Get mood from session or default to 'happy'
$mood = isset($_SESSION['mood']) ? strtolower($_SESSION['mood']) : 'happy';

// Fetch songs from database based on mood
$stmt = $pdo->prepare("SELECT * FROM songs WHERE mood = ?");
$stmt->execute([$mood]);
$songs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If no songs found, use sample data
if (empty($songs)) {
    $songs = [
        [
            'id' => 1,
            'title' => 'Happy Vibes',
            'artist' => 'Sunshine Band',
            'file_path' => 'happy-song.mp3',
            'mood' => 'happy'
        ],
        [
            'id' => 2,
            'title' => 'Joyful Melody', 
            'artist' => 'Rainbow Sounds',
            'file_path' => 'joyful-song.mp3',
            'mood' => 'happy'
        ],
        [
            'id' => 3,
            'title' => 'Upbeat Rhythm',
            'artist' => 'Positive Waves',
            'file_path' => 'upbeat-song.mp3', 
            'mood' => 'happy'
        ]
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Songs - MoodBuddy</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, rgba(6, 182, 212, 0.05) 0%, rgba(236, 72, 153, 0.05) 100%);
            padding: 4rem 0;
            text-align: center;
            position: relative;
        }

        /* Enhanced hero title with metallic shimmer */
        .hero-title {
            font-family: 'Orbitron', monospace;
            font-size: 3rem;
            font-weight: 900;
            background: var(--metallic-shine);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
            animation: titleGlow 3s ease-in-out infinite alternate, shimmer 4s ease infinite;
        }

        @keyframes titleGlow {
            0% { filter: drop-shadow(0 0 20px var(--glow-blue)); }
            100% { filter: drop-shadow(0 0 30px var(--glow-pink)); }
        }

        .hero-subtitle {
            font-size: 1.2rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
            font-weight: 500;
        }

        /* Main Content */
        .main-content {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        /* Enhanced section titles with metallic underline */
        .section-title {
            font-family: 'Orbitron', monospace;
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 3rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: var(--metallic-shine);
            background-size: 300% 300%;
            border-radius: 2px;
            box-shadow: 0 0 15px var(--glow-blue);
            animation: shimmer 2s ease infinite;
        }

        /* Card Grid */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
            width: 90%;
        }

        /* Audio player styling */
        .audio-wrapper {
            margin-top: 1.5rem;
            transition: all 0.3s ease;
        }

        .audio-player {
            width: 100%;
            border-radius: 10px;
            background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(236, 72, 153, 0.1));
            border: 1px solid var(--border-light);
        }

        /* Mood indicator */
        .mood-indicator {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            background: var(--primary-gradient);
            color: white;
            font-weight: 600;
            margin-bottom: 2rem;
        }

        /* Floating objects for background */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .floating-object {
            position: absolute;
            opacity: 0.3;
            animation: float 20s infinite linear;
            filter: drop-shadow(0 0 10px var(--glow-blue));
        }

        @keyframes float {
            0% { 
                transform: translateY(0px) rotate(0deg); 
                filter: drop-shadow(0 0 10px var(--glow-blue));
            }
            25% { 
                transform: translateY(-20px) rotate(90deg); 
                filter: drop-shadow(0 0 15px var(--glow-pink));
            }
            50% { 
                transform: translateY(0px) rotate(180deg); 
                filter: drop-shadow(0 0 20px var(--glow-blue));
            }
            75% { 
                transform: translateY(20px) rotate(270deg); 
                filter: drop-shadow(0 0 15px var(--glow-pink));
            }
            100% { 
                transform: translateY(0px) rotate(360deg); 
                filter: drop-shadow(0 0 10px var(--glow-blue));
            }
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .card-grid {
                grid-template-columns: 1fr;
            }
            
            .header-container {
                padding: 1rem;
            }
        }

        /* Scroll animations */
        .fade-in {
            opacity: 1;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .userInput {
      width: 50%;
      padding: 16px 24px;
      border-radius: 24px;
      border: 2px solid rgba(236, 72, 153, 0.25);
      font-family: 'Inter', sans-serif;
      outline: none;
      font-size: 15px;
      background: rgba(255, 255, 255, 0.95);
      transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
      font-weight: 400;
      margin-right: 2%;
    }

    .userInput:focus {
      border-color: #ec4899;
      box-shadow: 
        0 0 0 6px rgba(236, 72, 153, 0.2),
        0 12px 35px rgba(236, 72, 153, 0.25);
      background: rgba(255, 255, 255, 1);
      transform: translateY(-2px) scale(1.02);
    }

    .userInput::placeholder {
      color: rgba(148, 163, 184, 0.8);
      font-weight: 400;
    }
    </style>
</head>
<body>

    <!-- Hero Section -->
    <section class="hero-section">
        <h1 class="hero-title">Songs For Your <?= ucfirst($mood) ?> Mood</h1>
        <p class="hero-subtitle">Discover personalized music curated for your current vibe</p>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="mood-indicator">
            <i class="fas fa-music"></i> Recommended for your <?= ucfirst($mood) ?> mood
        </div>

        <!-- Songs Section -->
        <section class="fade-in">
            <h2 class="section-title">Mood Soundtrack</h2>
            <div class="card-grid">
                <?php foreach($songs as $song): ?>
                    <div class="futuristic-card">
                        <h3 class="card-title"><?= htmlspecialchars($song['title']) ?></h3>
                        <p class="card-artist"><?= htmlspecialchars($song['artist'] ?? 'Unknown Artist') ?></p>
                        <br>
                        <!-- Listen Now button -->
                        <button class="futuristic-btn" onclick="showPlayer(this, <?= $song['id'] ?>)">
                            <i class="fas fa-play"></i> Listen Now
                        </button>

                        <!-- Hidden Audio Player -->
                        <div class="audio-wrapper" id="player-<?= $song['id'] ?>" style="display:none;">
                            <audio controls class="audio-player" id="audio-<?= $song['id'] ?>">
                                <source src="admin/uploads/songs/<?= htmlspecialchars($song['file_path']) ?>" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
    <!-- YouTube Search Section -->
<section class="fade-in">
    <h2 class="section-title">Search Any Song</h2>
    <div class="search-container" style="text-align:center; margin-bottom:2rem;">
        <input type="text" class="userInput" id="youtubeSearch" placeholder="Search songs...">
        <button onclick="searchYouTube()" 
                class="futuristic-btn">
            <i class="fas fa-search"></i> Search
        </button>
    </div>
    <div id="youtubeResults" class="card-grid"></div>
</section>

<script>
    const API_KEY = <?= json_encode((string) app_env('YOUTUBE_API_KEY', '')) ?>; // set YOUTUBE_API_KEY in environment
    const resultsContainer = document.getElementById("youtubeResults");

    function searchYouTube() {
        const query = document.getElementById("youtubeSearch").value;
        if (!query) return;

        fetch(`https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&maxResults=6&q=${encodeURIComponent(query)}&key=${API_KEY}`)
            .then(res => res.json())
            .then(data => {
                resultsContainer.innerHTML = "";
                data.items.forEach(item => {
                    const videoId = item.id.videoId;
                    const title = item.snippet.title;
                    const channel = item.snippet.channelTitle;
                    const thumb = item.snippet.thumbnails.medium.url;

                    const card = document.createElement("div");
                    card.className = "futuristic-card";
                    card.innerHTML = `
                        <img src="${thumb}" alt="${title}" style="width:100%; border-radius:8px;">
                        <h3 class="card-title">${title}</h3>
                        <p class="card-artist">${channel}</p>
                        <br>
                        <button class="futuristic-btn" onclick="playYouTube('${videoId}', this)">
                            <i class="fas fa-play"></i> Play Now
                        </button>
                        <div class="audio-wrapper" style="display:none;">
                            <iframe width="100%" height="200" src="" frameborder="0" allowfullscreen></iframe>
                        </div>
                    `;
                    resultsContainer.appendChild(card);
                });
            })
            .catch(err => {
                console.error(err);
                resultsContainer.innerHTML = "<p>Something went wrong. Try again later.</p>";
            });
    }

    function playYouTube(videoId, btn) {
        // Hide all players
        document.querySelectorAll("#youtubeResults .audio-wrapper").forEach(wrapper => {
            wrapper.style.display = "none";
            wrapper.querySelector("iframe").src = "";
        });

        // Show selected video
        const wrapper = btn.nextElementSibling;
        wrapper.style.display = "block";
        wrapper.querySelector("iframe").src = `https://www.youtube.com/embed/${videoId}?autoplay=1`;

        // Change button text
        btn.innerHTML = '<i class="fas fa-pause"></i> Now Playing';
    }
</script>

    <?php include_once __DIR__ . '/includes/footer.php'; ?>
    <script>
        // Function to show audio player
        function showPlayer(button, songId) {
            // Hide all players first
            document.querySelectorAll('.audio-wrapper').forEach(player => {
                player.style.display = 'none';
            });
            
            // Stop all audio
            document.querySelectorAll('audio').forEach(audio => {
                audio.pause();
            });
            
            // Show the selected player
            const player = document.getElementById('player-' + songId);
            player.style.display = 'block';
            
            // Scroll to the player
            player.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            
            // Change button text
            button.innerHTML = '<i class="fas fa-pause"></i> Now Playing';
            
            // Play the audio
            const audio = document.getElementById('audio-' + songId);
            audio.play();
            
            // When audio ends, reset button
            audio.onended = function() {
                button.innerHTML = '<i class="fas fa-play"></i> Listen Now';
            };
        }

        // Scroll animation
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

        // Enhanced scroll effects
        window.addEventListener('scroll', () => {
            const header = document.querySelector('.futuristic-header');
            if (window.scrollY > 50) {
                header.style.background = 'rgba(255, 255, 255, 0.98)';
                header.style.boxShadow = '0 8px 30px rgba(6, 182, 212, 0.12)';
            } else {
                header.style.background = 'rgba(255, 255, 255, 0.95)';
                header.style.boxShadow = '0 4px 20px rgba(6, 182, 212, 0.08)';
            }
        });
    </script>
</body>
</html>