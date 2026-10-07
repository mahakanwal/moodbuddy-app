<?php $allowNoMood = true; // webcam is where the mood gets detected
include_once __DIR__ . '/includes/header.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Mood Detection - MoodBuddy</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #8a2be2;
      --secondary: #ff69b4;
      --accent: #9370db;
      --light: #f8f6ff;
      --dark: #2d1a4c;
      --success: #5cb85c;
      --warning: #f0ad4e;
      --danger: #d9534f;
      --text: #333333;
      --bg-light: #f8f8ff;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, var(--light) 0%, #e4e8f5 100%);
      color: var(--text);
      line-height: 1.6;
      min-height: 100vh;
      padding: 0;
    }

  

    /* Main Content */
    .main-content {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 3rem 0;
    }

    .page-title {
      text-align: center;
      margin-bottom: 2rem;
    }

    .page-title h1 {
      font-size: 2.5rem;
      color: var(--dark);
      margin-bottom: 0.5rem;
      background: linear-gradient(to right, var(--primary), var(--secondary));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .page-title p {
      font-size: 1.2rem;
      color: #666;
      max-width: 600px;
    }

    .detection-container {
      display: flex;
      flex-wrap: wrap;
      gap: 3rem;
      justify-content: center;
      align-items: flex-start;
      width: 100%;
      max-width: 1000px;
    }

    .camera-section {
      flex: 1;
      min-width: 300px;
      background: white;
      border-radius: 20px;
      padding: 2rem;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      border: 1px solid rgba(138, 43, 226, 0.1);
    }

    .camera-title {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 1.5rem;
      color: var(--primary);
    }

    .camera-title i {
      font-size: 1.5rem;
    }

    .video-container {
      position: relative;
      width: 100%;
      border-radius: 15px;
      overflow: hidden;
      margin-bottom: 1.5rem;
      border: 2px solid var(--light);
    }

    #video {
      width: 100%;
      height: auto;
      display: block;
      background: #000;
      border-radius: 15px;
    }

    .camera-controls {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      margin-top: 1.5rem;
    }

    .btn {
      padding: 0.8rem 1.5rem;
      border-radius: 10px;
      border: none;
      cursor: pointer;
      font-weight: 500;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-family: 'Poppins', sans-serif;
    }

    .btn-primary {
      background: linear-gradient(to right, var(--primary), var(--accent));
      color: white;
    }

    .btn-primary:hover {
      background: linear-gradient(to right, var(--secondary), var(--primary));
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-secondary {
      background: var(--light);
      color: var(--dark);
      border: 1px solid #ddd;
    }

    .btn-secondary:hover {
      background: #eee;
    }

    .btn:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
    }

    .btn:disabled:hover {
      transform: none;
      box-shadow: none;
    }

    .result-section {
      flex: 1;
      min-width: 300px;
      background: white;
      border-radius: 20px;
      padding: 2rem;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      border: 1px solid rgba(138, 43, 226, 0.1);
    }

    .result-title {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 1.5rem;
      color: var(--primary);
    }

    .result-title i {
      font-size: 1.5rem;
    }

    #result {
      min-height: 150px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 1.5rem;
      border-radius: 15px;
      background: var(--light);
      margin-bottom: 1.5rem;
      border: 1px dashed var(--accent);
    }

    .mood-display {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }

    .confidence {
      font-size: 1rem;
      color: #666;
    }

    .mood-info {
      background: var(--light);
      padding: 1.5rem;
      border-radius: 15px;
      margin-top: 1.5rem;
      border-left: 4px solid var(--primary);
    }

    .mood-info h3 {
      color: var(--dark);
      margin-bottom: 1rem;
    }

    .mood-info p {
      color: #666;
      line-height: 1.6;
    }

    /* Mood-specific colors */
    .mood-happy {
      color: #5cb85c;
    }

    .mood-sad {
      color: #5bc0de;
    }

    .mood-angry {
      color: #d9534f;
    }

    .mood-calm {
      color: #5cb85c;
    }

    .mood-excited {
      color: #f0ad4e;
    }

    .mood-neutral {
      color: #9370db;
    }

    .mood-anxious {
      color: #e67e22;
    }

    #result .futuristic-btn,
    #result .result-cta {
      display: inline-block;
      margin-top: 1rem;
      padding: 0.6rem 1.4rem;
      border-radius: 30px;
      background: linear-gradient(135deg, #ff1493, #00bfff);
      color: #fff;
      text-decoration: none;
      font-weight: 600;
    }

    /* Mood Suggestions */
    .mood-suggestions {
      margin-top: 2rem;
      padding: 1.5rem;
      background: white;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .suggestion-title {
      color: var(--dark);
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .suggestion-title i {
      color: var(--primary);
    }

    .suggestion-list {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .suggestion-item {
      flex: 1;
      min-width: 150px;
      padding: 1rem;
      background: var(--light);
      border-radius: 10px;
      text-align: center;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .suggestion-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .suggestion-icon {
      font-size: 2rem;
      margin-bottom: 0.5rem;
      color: var(--primary);
    }

    /* Footer */
    footer {
      background: var(--dark);
      color: white;
      padding: 2rem 0;
      text-align: center;
      margin-top: 3rem;
    }

    .footer-content {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 1rem;
    }

    .footer-logo {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 1.5rem;
      font-weight: 700;
    }

    .social-links {
      display: flex;
      gap: 1rem;
    }

    .social-links a {
      color: white;
      font-size: 1.5rem;
      transition: all 0.3s ease;
    }

    .social-links a:hover {
      color: var(--secondary);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .header-content {
        flex-direction: column;
        gap: 1rem;
      }
      
      nav ul {
        gap: 1rem;
      }
      
      .detection-container {
        flex-direction: column;
      }
      
      .camera-section, .result-section {
        width: 100%;
      }
      
      .camera-controls {
        justify-content: center;
      }
      
      .suggestion-list {
        flex-direction: column;
      }
    }

    @media (max-width: 480px) {
      .page-title h1 {
        font-size: 2rem;
      }
      
      .page-title p {
        font-size: 1rem;
      }
      
      .camera-controls {
        flex-direction: column;
      }
      
      .btn {
        width: 100%;
        justify-content: center;
      }
    }

    /* Loading animation */
    .loader {
      display: inline-block;
      width: 30px;
      height: 30px;
      border: 3px solid rgba(138, 43, 226, 0.3);
      border-radius: 50%;
      border-top-color: var(--primary);
      animation: spin 1s ease-in-out infinite;
      margin-bottom: 1rem;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    /* Mood emoji display */
    .mood-emoji {
      font-size: 3rem;
      margin-bottom: 1rem;
    }
  </style>
</head>
<body>

  <main class="container main-content">
    <div class="page-title">
      <h1>Mood Detection</h1>
      <p>Let MoodBuddy analyze your current mood and suggest activities to match it</p>
    </div>

    <div class="detection-container">
      <div class="camera-section">
        <div class="camera-title">
          <i class="fas fa-camera"></i>
          <h2>Webcam Capture</h2>
        </div>
        
        <div class="video-container">
          <video id="video" autoplay playsinline></video>
        </div>
        
        <canvas id="canvas" width="320" height="240" style="display:none"></canvas>
        
        <div class="camera-controls">
          <button id="openBtn" class="btn btn-primary">
            <i class="fas fa-camera"></i> Open Webcam
          </button>
          <button id="snapBtn" class="btn btn-primary" disabled>
            <i class="fas fa-camera-retro"></i> Capture & Detect Mood
          </button>
          <button id="stopBtn" class="btn btn-secondary" disabled>
            <i class="fas fa-stop"></i> Stop Webcam
          </button>
        </div>
      </div>

      <div class="result-section">
        <div class="result-title">
          <i class="fas fa-chart-line"></i>
          <h2>Detection Results</h2>
        </div>
        
        <div id="result">
          <p>Your mood detection results will appear here</p>
        </div>
        
        <div class="mood-info">
          <h3>How It Works</h3>
          <p>MoodBuddy uses advanced facial recognition to analyze your facial expressions and determine your current mood. Simply allow camera access, capture your image, and we'll detect whether you're happy, sad, calm, excited, or something else!</p>
        </div>
        
        <div class="mood-suggestions">
          <div class="suggestion-title">
            <i class="fas fa-lightbulb"></i>
            <h3>Mood-Based Suggestions</h3>
          </div>
          <div class="suggestion-list">
            <div class="suggestion-item">
              <div class="suggestion-icon">
                <i class="fas fa-headphones"></i>
              </div>
              <p>Music Therapy</p>
            </div>
            <div class="suggestion-item">
              <div class="suggestion-icon">
                <i class="fas fa-book"></i>
              </div>
              <p>Mindful Reading</p>
            </div>
            <div class="suggestion-item">
              <div class="suggestion-icon">
                <i class="fas fa-spa"></i>
              </div>
              <p>Breathing Exercises</p>
            </div>
            <div class="suggestion-item">
              <div class="suggestion-icon">
                <i class="fas fa-gamepad"></i>
              </div>
              <p>Relaxing Games</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  
  <?php include_once __DIR__ . '/includes/footer.php'; ?>
  <!-- Face expression detection runs fully in the browser (no server / Python needed) -->
  <script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api@1.7.13/dist/face-api.js"></script>
  <script>
    const openBtn = document.getElementById('openBtn');
    const snapBtn = document.getElementById('snapBtn');
    const stopBtn = document.getElementById('stopBtn');
    const video = document.getElementById('video');
    const result = document.getElementById('result');
    let stream = null;
    let modelsReady = false;

    const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api@1.7.13/model/';

    // face-api expressions -> moods used across MoodBuddy
    const expressionToMood = {
      happy: 'Happy',
      sad: 'Sad',
      angry: 'Angry',
      disgusted: 'Angry',
      fearful: 'Anxious',
      surprised: 'Excited',
      neutral: 'Calm'
    };

    // Mood emoji mapping
    const moodEmojis = {
      'Happy': '😊',
      'Sad': '😔',
      'Calm': '😌',
      'Excited': '🤩',
      'Angry': '😠',
      'Anxious': '😟'
    };

    async function loadModels() {
      if (modelsReady) return;
      await Promise.all([
        faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
        faceapi.nets.faceExpressionNet.loadFromUri(MODEL_URL)
      ]);
      modelsReady = true;
    }

    openBtn.onclick = async () => {
      try {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
          throw new Error('Camera is not supported in this browser (HTTPS is required).');
        }
        result.innerHTML = '<div class="loader"></div><p>Initializing webcam and AI model...</p>';
        openBtn.disabled = true;
        stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
        video.srcObject = stream;
        await loadModels();
        snapBtn.disabled = false;
        stopBtn.disabled = false;
        result.innerHTML = '<p style="color: var(--success)">Webcam active. Look at the camera and click "Capture & Detect Mood".</p>';
      } catch (err) {
        openBtn.disabled = false;
        result.innerHTML = `<p style="color: var(--danger)">Could not start detection: ${err.message}</p>`;
      }
    };

    function stopCamera() {
      if (stream) {
        stream.getTracks().forEach(t => t.stop());
        stream = null;
        video.srcObject = null;
      }
      snapBtn.disabled = true;
      stopBtn.disabled = true;
      openBtn.disabled = false;
    }

    stopBtn.onclick = () => {
      stopCamera();
      result.innerHTML = '<p>Webcam stopped. Click "Open Webcam" to start again.</p>';
    };

    snapBtn.onclick = async () => {
      result.innerHTML = '<div class="loader"></div><p>Analyzing your mood...</p>';
      snapBtn.disabled = true;

      try {
        const detection = await faceapi
          .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({ inputSize: 320, scoreThreshold: 0.5 }))
          .withFaceExpressions();

        if (!detection) {
          result.innerHTML = '<p style="color: var(--danger)">No face detected. Please face the camera in good light and try again.</p>';
          snapBtn.disabled = false;
          return;
        }

        // Pick the strongest expression
        const [expression, score] = Object.entries(detection.expressions).sort((a, b) => b[1] - a[1])[0];
        const mood = expressionToMood[expression] || 'Calm';
        const confidence = (score * 100).toFixed(1);

        // Save mood in the PHP session so the rest of the site can use it
        const fd = new FormData();
        fd.append('mood', mood.toLowerCase());
        const resp = await fetch('actions/save_mood.php', { method: 'POST', body: fd });
        const saved = await resp.json();
        if (!saved.success) throw new Error(saved.error || 'Could not save mood');

        result.innerHTML = `
          <div class="mood-emoji">${moodEmojis[mood] || ''}</div>
          <div class="mood-display mood-${mood.toLowerCase()}">${mood}</div>
          <div class="confidence">Confidence: ${confidence}%</div>
          <p style="margin-top: 1rem;">Based on your facial expression analysis</p>
          <a href="mood_result.php" class="result-cta">See My Recommendations</a>
        `;
        stopCamera();
      } catch (err) {
        result.innerHTML = `<p style="color: var(--danger)">Error: ${err.message}</p>`;
        snapBtn.disabled = false;
      }
    };
  </script>
</body>
</html>