<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MoodBuddy - Your Mood Companion</title>
  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/css/fontawesome.min.css" />
  <link rel="stylesheet" href="assets/css/magnific-popup.min.css" />
  <link rel="stylesheet" href="assets/css/slick.min.css" />
  <!-- <link rel="stylesheet" href="assets/css/style.css" /> -->
  <!-- Updated to Orbitron font for futuristic 2027 theme -->
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    /* Complete 2027 futuristic theme with pink/sky blue colors and floating objects */
    :root {
            /* Updated to pink and sky blue theme with metallic shining effects */
            --primary-color: #06b6d4;
            --secondary-color: #ec4899;
            --primary-gradient: linear-gradient(135deg, #06b6d4 0%, #ec4899 100%);
            --metallic-shine: linear-gradient(45deg, #06b6d4, #0891b2, #ec4899, #db2777);
            --light-bg: #ffffff;
            --card-bg: rgba(255, 255, 255, 0.95);
            --text-primary: #1f2937;
            --text-secondary: #4b5563;
            --border-light: rgba(6, 182, 212, 0.2);
            --glow-pink: rgba(236, 72, 153, 0.4);
            --glow-blue: rgba(6, 182, 212, 0.4);
        }
    body {
      font-family: 'Orbitron', monospace;
      margin: 0;
      padding: 0;
      background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
      overflow-x: hidden;
      /* position: relative; */
    }

    
    /* Modern futuristic header with metallic effects */
    header {
      position: fixed;
      top: 5%;
      left: 0;
      width: 80%;
      height: 15vh;
      background: white;
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
      border-top-right-radius: 50px;
      border-bottom-right-radius: 50px;
      backdrop-filter: blur(20px);
      box-shadow: 0 8px 32px rgba(255, 105, 180, 0.2), 0 0 20px rgba(135, 206, 235, 0.1);
      border: 1px solid rgba(255, 105, 180, 0.2);
    }

    .logo {
            font-family: 'Orbitron', monospace;
            font-size: 1.5rem;
            font-weight: 900;
            background: var(--metallic-shine);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            transition: all 0.3s ease;
            cursor: pointer;
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        

    /* Enhanced hero section with original text colors preserved */
    .hero-layout1 {
      height: 90vh;
      align-items: center;
      position: relative;
      z-index: 2;
    }
    .row {
      padding-top: 150px;
    }

    .hero-content {
      position: relative;
      z-index: 3;
      /* padding-top: 50px; */
      
    }

    .hero-subtitle {
      font-family: 'Orbitron', monospace;
      font-weight: 800;
      font-size: 1.2rem;
      background: linear-gradient(45deg, #ff69b4, #87ceeb);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .hero-title {
      font-family: 'Orbitron', monospace;
      font-weight: 600;
      font-size: 2.5rem;
      margin: 20px 0;
      color: white;
    }

    .hero-text {
      font-family: 'Orbitron', monospace;
      font-weight: 200;
      line-height: 1.6;
      margin-bottom: 30px;
      color: #fff;
    }

    
    .hero-form2 {
      padding: 40px;
      margin-top: 20px;
      background: white;
      backdrop-filter: blur(20px);
      border-radius: 20px;
      border: 2px solid transparent;
      background-clip: padding-box;
      box-shadow: 
        0 20px 40px rgba(255, 105, 180, 0.1),
        0 0 30px rgba(135, 206, 235, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.8);
      position: relative;
      overflow: hidden;
      height: 400px;
      width: 450px;
      margin-left: 20%;
    }

   

    @keyframes gradientShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    .hero-form2 h3 {
      font-family: 'Orbitron', monospace;
      background: linear-gradient(45deg, #ff69b4, #87ceeb);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-weight: 500;
      font-size: 1.5rem;
      margin-bottom: 20px;
      text-shadow: 0 0 20px rgba(255, 105, 180, 0.2);
    }

    .hero-form2 label {
      font-family: 'Orbitron', monospace;
      background: linear-gradient(45deg, #ff69b4, #87ceeb);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-size: 1.5rem;
      font-weight: 500;
      margin-bottom: 10px;
      display: block;
    }

    .form-group {
      position: relative;
      margin-bottom: 20px;
    }

    .form-group i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      background: #334155;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-size: 1.2rem;
      z-index: 2;
    }

    .form-select {
      width: 100%;
      padding: 15px 15px 15px 50px;
      border: 2px solid rgba(255, 105, 180, 0.2);
      border-radius: 15px;
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(248, 250, 252, 0.8));
      backdrop-filter: blur(10px);
      font-family: 'Orbitron', monospace;
      font-weight: 500;
      color: #334155;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(255, 105, 180, 0.1);
    }

    .form-select:focus {
      outline: none;
      border-color: #ff69b4;
      box-shadow: 
        0 0 20px rgba(255, 105, 180, 0.3),
        0 0 40px rgba(135, 206, 235, 0.2);
      transform: translateY(-2px);
    }

    .form-select:hover {
      border-color: #87ceeb;
      box-shadow: 0 8px 25px rgba(135, 206, 235, 0.2);
      transform: translateY(-1px);
    }

    /* Modern 2027 button styling */
    .vs-btn.style4 {
      background: linear-gradient(45deg, #ff69b4, #87ceeb);
      border: none;
      padding: 15px 40px;
      border-radius: 50px;
      color: white;
      font-family: 'Orbitron', monospace;
      font-weight: 600;
      font-size: 1rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 
        0 10px 30px rgba(255, 105, 180, 0.3),
        0 0 20px rgba(135, 206, 235, 0.2);
      position: relative;
      overflow: hidden;
      width: 100%;
      margin-top: 20px;
    }

    .vs-btn.style4::before {
      content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
    }

    .vs-btn.style4:hover::before {
      left: 100%;
    }

    .vs-btn.style4:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px var(--glow-blue);
            filter: brightness(1.1);
    }

    .vs-btn.style4:active {
      transform: translateY(-1px);
    }

    /* Enhanced hero slide styling */
    .hero-slide {
      background-size: cover;
      background-position: center;
      height: 100vh;
      display: flex;
      align-items: center;
      /* justify-content: center; */
      position: relative;
      top: 0;
    }
    
    .hero-slide::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.4); /* yahan 0.4 se background dull hoga */
      z-index: 1;
    }
    
    .hero-slide .container {
      position: relative;
      z-index: 2;
    }

    /* Responsive design improvements */
    @media (max-width: 768px) {
      header {
        width: 90%;
        height: 12vh;
      }
      
      header img {
        height: 120px;
        width: 120px;
      }
      
      .hero-form2 {
        padding: 30px 20px;
        margin-top: 20px;
      }
      
      .floating-object {
        display: none;
      }
    }
    
  </style>
</head>
<body>

<?php if (isset($_GET['welcome'])): ?>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      Swal.fire({
        icon: 'success',
        title: 'Welcome <?php echo htmlspecialchars($_GET['welcome']); ?> 🎉',
        text: 'Please select your mood again!',
        confirmButtonColor: '#ff681a'
      });
    });
  </script>
  <?php endif; ?>
<header>
<div class="logo">MoodBuddy</div>
</header>

  <!-- Hero Section -->
  <section class="hero-layout1">
    <div class="vs-carousel hero-slider2" data-slide-show="1" data-fade="true">

      <!-- Slide 1 -->
      <div class="hero-slide hero-mask" style="background-image: url('assets/img/hero-bg-2.jpg');">
        <div class="container">
          <div class="row align-items-center justify-content-between">
            <div class="col-lg-6">
               <div class="hero-content">
                <span class="hero-subtitle">Welcome to MoodBuddy</span>
                <h1 class="hero-title">Track Your Mood, Discover Fun & Feel Better.</h1>
                <p class="hero-text">Get personalized memes, quotes, music, and more — all based on how you feel.</p>

              </div>
            </div>
            <div class="col-lg-6">
  <div class="hero-form2" style="background-image: url('assets/img/banner/map-bg.png');">
  <form method="POST" action="actions/process_mood.php">
      <div>
        <h3 class="mb-4">How are you feeling today?</h3>
        <div class="form-group">
          <i class="fas fa-smile"></i>
          <select class="form-select" name="mood" required>
            <option value="" selected disabled>Select Your Mood</option>
            <option value="happy">😊 Happy</option>
            <option value="sad">😢 Sad</option>
            <option value="angry">😠 Angry</option>
            <option value="anxious">😰 Anxious</option>
            <option value="excited">🤩 Excited</option>
          </select>
        </div>
      </div>
      <div>
        <label class="h3">Gender</label>
        <div class="form-group">
          <i class="fas fa-venus-mars"></i>
          <select class="form-select" name="gender" required>
            <option value="" selected disabled hidden>Select Gender</option>
            <option value="female">Female</option>
            <option value="male">Male</option>
            <option value="other">Other</option>
          </select>
        </div>
      </div>
      <div>
        <button class="vs-btn style4" type="submit">Start MoodBuddy</button>
      </div>
    </form>
  </div>
</div>

          </div>
        </div>
      </div>

      <!-- Slide 2 -->
      <div class="hero-slide hero-mask" style="background-image: url('assets/img/hero-bg-3.jpg');">
        <div class="container">
          <div class="row align-items-center justify-content-between">
            <div class="col-lg-6">
              <div class="hero-content">
                <span class="hero-subtitle">Welcome to MoodBuddy</span>
                <h1 class="hero-title">Happy or Sad, MoodBuddy Delivers the Perfect Vibe</h1>
                <p class="hero-text">Funny memes, relaxing music, inspiring quotes — all personalized for your mood.</p>
                
              </div>
            </div>
            <div class="col-lg-6">
  <div class="hero-form2" style="background-image: url('assets/img/banner/map-bg.png');">
  <form method="POST" action="actions/process_mood.php">
      <div>
        <h3 class="mb-4">How are you feeling today?</h3>
        <div class="form-group">
          <i class="fas fa-smile"></i>
          <select class="form-select" name="mood" required>
            <option value="" selected disabled>Select Your Mood</option>
            <option value="happy">😊 Happy</option>
            <option value="sad">😢 Sad</option>
            <option value="angry">😠 Angry</option>
            <option value="anxious">😰 Anxious</option>
            <option value="excited">🤩 Excited</option>
          </select>
        </div>
      </div>
      <div>
        <label class="h3">Gender</label>
        <div class="form-group">
          <i class="fas fa-venus-mars"></i>
          <select class="form-select" name="gender" required>
            <option value="" selected disabled hidden>Select Gender</option>
            <option value="female">Female</option>
            <option value="male">Male</option>
            <option value="other">Other</option>
          </select>
        </div>
      </div>
      <div>
        <button class="vs-btn style4" type="submit">Start MoodBuddy</button>
      </div>
    </form>
  </div>
</div>

          </div>
        </div>
      </div>

      <!-- Slide 3 -->
      <div class="hero-slide hero-mask" style="background-image: url('assets/img/hero-bg-5.jpg');">
        <div class="container">
          <div class="row align-items-center justify-content-between">
            <div class="col-lg-6">
              <div class="hero-content">
                <span class="hero-subtitle">Welcome to MoodBuddy</span>
                <h1 class="hero-title">Choose Your Mood to Discover What You Need</h1>
                <p class="hero-text">Let MoodBuddy guide your emotions with engaging content based on how you're feeling.</p>
              </div>
            </div>
            <div class="col-lg-6">
  <div class="hero-form2" style="background-image: url('assets/img/banner/map-bg.png');">
      <form method="POST" action="actions/process_mood.php">
       <div>
        <h3 class="mb-4">How are you feeling today?</h3>
        <div class="form-group">
          <i class="fas fa-smile"></i>
          <select class="form-select" name="mood" required>
            <option value="" selected disabled>Select Your Mood</option>
            <option value="happy">😊 Happy</option>
            <option value="sad">😢 Sad</option>
            <option value="angry">😠 Angry</option>
            <option value="anxious">😰 Anxious</option>
            <option value="excited">🤩 Excited</option>
          </select>
        </div>
      </div>
      <div>
        <label class="h3">Gender</label>
        <div class="form-group">
          <i class="fas fa-venus-mars"></i>
          <select class="form-select" name="gender" required>
            <option value="" selected disabled hidden>Select Gender</option>
            <option value="female">Female</option>
            <option value="male">Male</option>
            <option value="other">Other</option>
          </select>
        </div>
      </div>
      <div>
        <button class="vs-btn style4" type="submit">Start MoodBuddy</button>
      </div>
    </form>
  </div>
</div>

          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- Scripts -->
  <script src="assets/js/vendor/jquery-3.6.0.min.js"></script>
  <script src="assets/js/slick.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <script src="assets/js/jquery.magnific-popup.min.js"></script>
  <script src="assets/js/jquery-ui.min.js"></script>
  <script src="assets/js/circle-progress.min.js"></script>
  <script src="assets/js/imagesLoaded.js"></script>
  <script src="assets/js/isotope.js"></script>
  <script src="assets/js/wow.min.js"></script>
  <script src="assets/js/main.js"></script>
</body>

</html>
