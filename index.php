
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MoodMate 🤖</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Inter:wght@300;400;500;600&display=swap');
    
    * { 
      box-sizing: border-box; 
      margin: 0;
      padding: 0;
    }
    
    body, html {
      height: 100vh;
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
      overflow: hidden;
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* Enhanced floating background objects with more variety and better animations */
    .floating-objects {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 1;
      overflow: hidden;
    }

    .floating-object {
      position: absolute;
      opacity: 0.08;
      animation: float-rotate 20s infinite linear;
      filter: blur(0.5px);
    }

    .floating-object:nth-child(1) {
      width: 120px;
      height: 120px;
      background: linear-gradient(45deg, #ec4899, #0ea5e9);
      border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
      top: 15%;
      left: 8%;
      animation-duration: 28s;
      box-shadow: 0 0 60px rgba(236, 72, 153, 0.4);
    }

    .floating-object:nth-child(2) {
      width: 180px;
      height: 180px;
      background: linear-gradient(135deg, #0ea5e9, #ec4899);
      border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
      top: 60%;
      right: 12%;
      animation-duration: 35s;
      animation-direction: reverse;
      box-shadow: 0 0 80px rgba(14, 165, 233, 0.4);
    }

    .floating-object:nth-child(3) {
      width: 90px;
      height: 90px;
      background: linear-gradient(90deg, #ec4899, #a855f7);
      border-radius: 40% 60% 60% 40% / 60% 40% 40% 60%;
      bottom: 25%;
      left: 15%;
      animation-duration: 42s;
      box-shadow: 0 0 50px rgba(168, 85, 247, 0.4);
    }

    .floating-object:nth-child(4) {
      width: 140px;
      height: 140px;
      background: linear-gradient(180deg, #0ea5e9, #06b6d4);
      border-radius: 70% 30% 30% 70% / 30% 70% 70% 30%;
      top: 25%;
      right: 35%;
      animation-duration: 32s;
      animation-direction: reverse;
      box-shadow: 0 0 70px rgba(6, 182, 212, 0.4);
    }

    .floating-object:nth-child(5) {
      width: 110px;
      height: 110px;
      background: linear-gradient(270deg, #ec4899, #f472b6);
      border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
      bottom: 35%;
      right: 8%;
      animation-duration: 38s;
      box-shadow: 0 0 55px rgba(244, 114, 182, 0.4);
    }

    .floating-object:nth-child(6) {
      width: 160px;
      height: 160px;
      background: linear-gradient(315deg, #a855f7, #ec4899);
      border-radius: 40% 60% 70% 30% / 40% 40% 60% 60%;
      top: 5%;
      left: 45%;
      animation-duration: 45s;
      box-shadow: 0 0 75px rgba(168, 85, 247, 0.3);
    }

    @keyframes float-rotate {
      0% {
        transform: rotate(0deg) translateX(60px) rotate(0deg) scale(1);
        opacity: 0.08;
      }
      25% {
        opacity: 0.15;
        transform: rotate(90deg) translateX(100px) rotate(-90deg) scale(1.3);
      }
      50% {
        opacity: 0.12;
        transform: rotate(180deg) translateX(80px) rotate(-180deg) scale(0.7);
      }
      75% {
        opacity: 0.18;
        transform: rotate(270deg) translateX(120px) rotate(-270deg) scale(1.1);
      }
      100% {
        transform: rotate(360deg) translateX(60px) rotate(-360deg) scale(1);
        opacity: 0.08;
      }
    }

    /* Improved loader with better positioning and effects */
    #loader {
      position: fixed;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 50%, #e2e8f0 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      z-index: 9999;
      overflow: hidden;
    }

    .loader-container {
      position: relative;
      display: flex;
      flex-direction: column;
      align-items: center;
      z-index: 2;
    }

    .loader-rings {
      position: relative;
      width: 220px;
      height: 220px;
      margin-bottom: 50px;
    }

    .ring {
      position: absolute;
      border: 4px solid transparent;
      border-radius: 50%;
      animation: spin 3s linear infinite;
    }

    .ring-1 {
      width: 220px;
      height: 220px;
      border-top: 4px solid #ec4899;
      border-right: 4px solid #ec4899;
      box-shadow: 0 0 30px rgba(236, 72, 153, 0.6);
      animation-duration: 2s;
    }

    .ring-2 {
      width: 180px;
      height: 180px;
      top: 20px;
      left: 20px;
      border-bottom: 4px solid #0ea5e9;
      border-left: 4px solid #0ea5e9;
      box-shadow: 0 0 30px rgba(14, 165, 233, 0.6);
      animation-duration: 3s;
      animation-direction: reverse;
    }

    .ring-3 {
      width: 140px;
      height: 140px;
      top: 40px;
      left: 40px;
      border-top: 4px solid #a855f7;
      border-bottom: 4px solid #a855f7;
      box-shadow: 0 0 25px rgba(168, 85, 247, 0.5);
      animation-duration: 1.5s;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .brain-center {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 70px;
      animation: pulse-glow 2s ease-in-out infinite;
      filter: drop-shadow(0 0 25px rgba(236, 72, 153, 0.7));
    }

    @keyframes pulse-glow {
      0%, 100% { 
        transform: translate(-50%, -50%) scale(1);
        filter: drop-shadow(0 0 25px rgba(236, 72, 153, 0.7));
      }
      50% { 
        transform: translate(-50%, -50%) scale(1.15);
        filter: drop-shadow(0 0 40px rgba(14, 165, 233, 0.9));
      }
    }

    .loading-text {
      font-family: 'Orbitron', monospace;
      font-size: 28px;
      font-weight: 700;
      background: linear-gradient(45deg, #ec4899, #0ea5e9, #ec4899);
      background-size: 200% 200%;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      animation: gradient-shift 3s ease-in-out infinite;
      text-align: center;
      margin-bottom: 30px;
      letter-spacing: 2px;
    }

    @keyframes gradient-shift {
      0%, 100% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
    }

    .progress-container {
      width: 350px;
      height: 6px;
      background: rgba(148, 163, 184, 0.2);
      border-radius: 15px;
      overflow: hidden;
      position: relative;
    }

    .progress-bar {
      height: 100%;
      background: linear-gradient(90deg, #ec4899, #0ea5e9);
      border-radius: 15px;
      width: 0%;
      animation: loading-progress 4s ease-in-out forwards;
      box-shadow: 0 0 15px rgba(236, 72, 153, 0.6);
    }

    @keyframes loading-progress {
      0% { width: 0%; }
      100% { width: 100%; }
    }

    /* Completely redesigned chatbot with perfect center positioning and enhanced styling */
    #chatbot {
      display: none;
      width: 450px;
      height: 600px;
      background: rgba(255, 255, 255, 0.98);
      backdrop-filter: blur(25px);
      border: 2px solid rgba(236, 72, 153, 0.25);
      border-radius: 28px;
      
      overflow: hidden;
      display: flex;
      flex-direction: column;
      z-index: 1000;
      animation: chatbotAppear 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
      position: relative;
    }

    @keyframes chatbotAppear {
      from {
        opacity: 0;
        transform: scale(0.9) translateY(-30px);
      }
      to {
        opacity: 1;
        transform: scale(1) translateY(0);
      }
    }

    /* Enhanced header with better gradients and animations */
    .header {
      background: linear-gradient(135deg, #ec4899 0%, #0ea5e9 100%);
      padding: 24px 28px;
      display: flex;
      align-items: center;
      gap: 18px;
      color: white;
      position: relative;
      overflow: hidden;
    }

    .header::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
      animation: shimmer 5s infinite;
    }

    @keyframes shimmer {
      0% { left: -100%; }
      100% { left: 100%; }
    }

    .header::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 3px;
      background: linear-gradient(90deg, rgba(255,255,255,0.6), transparent, rgba(255,255,255,0.6));
    }

    .header img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      border: 3px solid rgba(255, 255, 255, 0.95);
      box-shadow: 0 0 25px rgba(255, 255, 255, 0.5);
      animation: avatarGlow 3s ease-in-out infinite;
    }

    @keyframes avatarGlow {
      0%, 100% { 
        box-shadow: 0 0 25px rgba(255, 255, 255, 0.5);
        transform: scale(1);
      }
      50% { 
        box-shadow: 0 0 35px rgba(255, 255, 255, 0.8);
        transform: scale(1.05);
      }
    }

    .header span {
      font-family: 'Orbitron', monospace;
      font-weight: 700;
      font-size: 20px;
      text-shadow: 0 0 20px rgba(255, 255, 255, 0.7);
      letter-spacing: 1px;
    }

    /* Enhanced chat area with better background and spacing */
    .chat {
      padding: 32px;
      display: flex;
      flex-direction: column;
      gap: 20px;
      height: 400px;
      overflow-y: auto;
      background: 
        radial-gradient(circle at 25% 75%, rgba(236, 72, 153, 0.06) 0%, transparent 50%),
        radial-gradient(circle at 75% 25%, rgba(14, 165, 233, 0.06) 0%, transparent 50%),
        radial-gradient(circle at 50% 50%, rgba(168, 85, 247, 0.03) 0%, transparent 50%);
      /* Fixed message height compression by ensuring proper flex behavior */
      flex-shrink: 0;
      
    }

    .chat::-webkit-scrollbar {
      width: 6px;
    }

    .chat::-webkit-scrollbar-track {
      background: rgba(236, 72, 153, 0.1);
      border-radius: 10px;
    }

    .chat::-webkit-scrollbar-thumb {
      background: linear-gradient(135deg, #ec4899, #0ea5e9);
      border-radius: 10px;
    }

    /* Enhanced message styling with better animations and effects */
    .message {
      padding: 16px 24px;
      border-radius: 24px;
      max-width: 88%;
      font-size: 15px;
      line-height: 1.7;
      position: relative;
      animation: messageSlide 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
      transition: all 0.4s ease;
      font-weight: 400;
      /* Prevent message height compression */
      flex-shrink: 0;
      min-height: auto;
      word-wrap: break-word;
    }

    @keyframes messageSlide {
      from {
        opacity: 0;
        transform: translateY(20px) scale(0.95);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    .message:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
    }

    .bot {
      background: linear-gradient(135deg, rgba(255,255,255,0.98) 0%, rgba(248,250,252,0.98) 100%);
      color: #1e293b;
      align-self: flex-start;
      border: 2px solid rgba(236, 72, 153, 0.2);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
      position: relative;
      overflow: hidden;
    }

    .bot::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 2px;
      background: linear-gradient(90deg, transparent, rgba(236, 72, 153, 0.4), transparent);
    }

    .user {
      background: linear-gradient(135deg, #ec4899 0%, #0ea5e9 100%);
      color: white;
      align-self: flex-end;
      box-shadow: 0 8px 25px rgba(236, 72, 153, 0.5);
      position: relative;
      overflow: hidden;
      font-weight: 500;
    }

    .user::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
      animation: messageShimmer 3s infinite;
    }

    @keyframes messageShimmer {
      0% { left: -100%; }
      100% { left: 100%; }
    }

    /* Enhanced input area with better styling and effects */
    .input-area {
      display: flex;
      align-items: center;
      background: rgba(255, 255, 255, 0.98);
      padding: 24px 28px;
      border-top: 2px solid rgba(236, 72, 153, 0.2);
      backdrop-filter: blur(20px);
      position: relative;
    }

    .input-area::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 2px;
      background: linear-gradient(90deg, transparent, rgba(236, 72, 153, 0.4), transparent);
    }

    #userInput {
      width: 100%;
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
    }

    #userInput:focus {
      border-color: #ec4899;
      box-shadow: 
        0 0 0 6px rgba(236, 72, 153, 0.2),
        0 12px 35px rgba(236, 72, 153, 0.25);
      background: rgba(255, 255, 255, 1);
      transform: translateY(-2px) scale(1.02);
    }

    #userInput::placeholder {
      color: rgba(148, 163, 184, 0.8);
      font-weight: 400;
    }

    /* Skip button styling */
    .skip-button {
      position: absolute;
      top: 20px;
      right: 20px;
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(10px);
      border: 2px solid rgba(255, 255, 255, 0.5);
      color: white;
      padding: 8px 16px;
      border-radius: 20px;
      font-family: 'Inter', sans-serif;
      font-weight: 500;
      font-size: 14px;
      cursor: pointer;
      transition: all 0.3s ease;
      z-index: 1001;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .skip-button:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    }

    .skip-button:active {
      transform: translateY(0);
    }
  </style>
</head>
<body>

<!-- Enhanced floating background objects with more variety -->
<div class="floating-objects">
  <div class="floating-object"></div>
  <div class="floating-object"></div>
  <div class="floating-object"></div>
  <div class="floating-object"></div>
  <div class="floating-object"></div>
  <div class="floating-object"></div>
</div>

<!-- Enhanced loader -->
<div id="loader">
  <div class="loader-container">
    <div class="loader-rings">
      <div class="ring ring-1"></div>
      <div class="ring ring-2"></div>
      <div class="ring ring-3"></div>
      <div class="brain-center">😉</div>
    </div>
    <div class="loading-text">INITIALIZING MOOD MATRIX</div>
    <div class="progress-container">
      <div class="progress-bar"></div>
    </div>
  </div>
</div>

<!-- Redesigned chatbot with perfect center positioning -->
<div id="chatbot">
  
  <div class="header">
    <img src="assets/img/chatbot-avator.jpg" alt="Bot Avatar">
    <span>Mood Buddy😄✨</span>
  </div>

  <div class="chat" id="chatArea"></div>

  <div class="input-area">
    <input type="text" id="userInput" placeholder="Type your mood...">
  </div>
  <button class="skip-button" id="skipButton">Skip →</button>
</div>

<script>
  const messages = [
    "Hey Beautiful Peeps! 👋💖",
    "I'm Mahi and team — your mood buddy 😄✨",
    "Kya Mood Hai? 🤔", "Kya Feel Hai? 😌",
    "😊 Happy ho? Ao happiness ko aur barhayein! 🥳🎉", 
    "😢 Sad ho? Ao tumhari sadness bhagayen! 💪💫", 
    "😡 Angry ho? Ao tumhara gussa thanda karein! 🧊🫶", 
    "🤯 Stress mein ho? Relax karte hain! 🧘‍♀️🌈", 
    "🎵 Wanna tune into a vibe? Type 'yes' to begin 🔥"
  ];

  let current = 0;
  const chatArea = document.getElementById("chatArea");
  let skipClicked = false;

  function typeMessage(text, className, callback) {
    if (skipClicked) return;
    
    const msg = document.createElement('div');
    msg.className = 'message ' + className;
    chatArea.appendChild(msg);
    let i = 0;
    function typing() {
      if (skipClicked) return;
      
      if (i < text.length) {
        msg.textContent += text.charAt(i);
        i++;
        setTimeout(typing, 35);
      } else if (callback) callback();
      chatArea.scrollTop = chatArea.scrollHeight;
    }
    typing();
  }

  function showBotMessages() {
    if (skipClicked) return;
    
    if (current < messages.length) {
      typeMessage(messages[current], 'bot', () => {
        current++;
        setTimeout(showBotMessages, 600);
      });
    }
  }

  function handleUserInput(value) {
    typeMessage(value, 'user');
    if (value.trim().toLowerCase() === 'yes') {
      setTimeout(() => {
        window.location.href = "homepage.php";
      }, 1000);
    } else {
      setTimeout(() => {
        typeMessage("Say YES and let the mood magic begin! ✨ Just type YES and boom! 🎉", 'bot');
      }, 800);
    }
  }

  function skipToHome() {
    skipClicked = true;
    // Add a quick exit animation
    document.getElementById("chatbot").style.opacity = "0.8";
    document.getElementById("chatbot").style.transform = "translateY(20px)";
    
    setTimeout(() => {
      window.location.href = "homepage.php";
    }, 300);
  }

  document.getElementById("userInput").addEventListener("keydown", function (e) {
    if (e.key === "Enter") {
      const value = this.value.trim();
      if (value !== '') {
        handleUserInput(value);
        this.value = '';
      }
    }
  });

  document.getElementById("skipButton").addEventListener("click", skipToHome);

  window.onload = function () {
    setTimeout(() => {
      document.getElementById("loader").style.display = "none";
      document.getElementById("chatbot").style.display = "block";
      showBotMessages();
    }, 4500);
  };
</script>
</body>
</html>