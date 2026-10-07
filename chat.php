<?php
session_start();
require_once __DIR__ . '/includes/config.php';

/*
  MoodBuddy chatbot
  - Uses Hugging Face emotion model when HF_API_KEY is set (environment variable)
  - Falls back to a built-in keyword detector, so the chat always works
*/

$DEBUG    = filter_var(app_env('APP_DEBUG', 'false'), FILTER_VALIDATE_BOOLEAN);
$HF_TOKEN = app_env('HF_API_KEY', '');
$HF_MODEL = app_env('HF_MODEL', 'j-hartmann/emotion-english-distilroberta-base');

/** Call Hugging Face; returns ['label'=>..., 'score'=>...] or null on any failure */
function app_hf_emotion(string $text, string $token, string $model): ?array
{
    if ($token === '') return null;

    $ch = curl_init("https://router.huggingface.co/hf-inference/models/{$model}");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode(['inputs' => $text]),
        CURLOPT_HTTPHEADER     => ["Authorization: Bearer {$token}", "Content-Type: application/json"],
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT        => 20,
    ]);
    $resp = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($resp === false || $http >= 400) {
        error_log("HF emotion API failed (HTTP $http): " . substr((string)$resp, 0, 300));
        return null;
    }

    $decoded = json_decode($resp, true);
    $best = ['label' => null, 'score' => 0.0];
    $walk = function ($node) use (&$walk, &$best) {
        if (!is_array($node)) return;
        if (isset($node['label'], $node['score']) && is_numeric($node['score']) && $node['score'] > $best['score']) {
            $best = ['label' => $node['label'], 'score' => (float)$node['score']];
        }
        foreach ($node as $child) $walk($child);
    };
    $walk($decoded);

    return $best['label'] ? $best : null;
}

/** Simple keyword-based detector (English + Roman Urdu) used as a fallback */
function app_keyword_emotion(string $text): array
{
    $t = ' ' . strtolower($text) . ' ';
    $lexicon = [
        'joy'      => ['happy','good','great','awesome','amazing','love','fun','glad','khush','acha','achha','maza','best','fantastic','nice','smile'],
        'sadness'  => ['sad','down','lonely','cry','depressed','upset','hurt','udaas','udas','dukhi','bura','miss','tired','low','unhappy'],
        'anger'    => ['angry','mad','annoyed','hate','furious','irritated','gussa','ghussa','frustrated','pissed'],
        'fear'     => ['anxious','worried','stress','stressed','nervous','scared','afraid','tension','pareshan','parishan','fear','panic','exam'],
        'surprise' => ['excited','wow','surprised','cant wait','thrilled','pumped','josh'],
    ];
    $scores = [];
    foreach ($lexicon as $label => $words) {
        $scores[$label] = 0;
        foreach ($words as $w) $scores[$label] += substr_count($t, $w);
    }
    arsort($scores);
    $label = key($scores);
    $total = array_sum($scores);
    if ($total === 0) return ['label' => 'neutral', 'score' => 0.5];
    return ['label' => $label, 'score' => round($scores[$label] / $total, 3)];
}

/* ---------- AJAX handler ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');

    $payload = json_decode(file_get_contents('php://input'), true);
    if (!is_array($payload) || empty($payload['answers']) || !is_array($payload['answers'])) {
        echo json_encode(['error' => 'Invalid request. Please answer the questions first.']);
        exit;
    }

    $clean = array_map(fn($a) => trim(strip_tags((string)$a)), $payload['answers']);
    $text  = mb_substr(implode('. ', $clean), 0, 1500);

    if (strlen($text) < 2) {
        echo json_encode(['error' => 'Answers were empty. Please type a little more.']);
        exit;
    }

    $best = app_hf_emotion($text, $HF_TOKEN, $HF_MODEL) ?? app_keyword_emotion($text);

    $labelRaw   = strtolower((string)$best['label']);
    $confidence = round($best['score'], 3);

    // Map model labels to the moods used across the site (happy, sad, angry, anxious, excited, calm)
    $map = [
        'joy' => 'happy', 'happiness' => 'happy', 'love' => 'happy', 'optimism' => 'happy', 'positive' => 'happy',
        'sadness' => 'sad', 'sad' => 'sad', 'disappointment' => 'sad', 'negative' => 'sad',
        'anger' => 'angry', 'angry' => 'angry', 'disgust' => 'angry',
        'fear' => 'anxious', 'nervousness' => 'anxious',
        'surprise' => 'excited', 'excitement' => 'excited',
        'neutral' => 'calm',
    ];
    $mood = $map[$labelRaw] ?? 'calm';

    // Save to session for mood_result.php
    $_SESSION['mood'] = $mood;
    $_SESSION['mood_label'] = $labelRaw;
    $_SESSION['mood_confidence'] = $confidence;

    $replies = [
        'happy'   => "Lagta hai aap khush ho! Let's keep that energy going.",
        'sad'     => "Afsos, aap thoda down lag rahe ho. Main kuch acha dhoondta hoon aapke liye.",
        'angry'   => "Gussa mehsoos kar rahe ho? Deep breaths try karo, main help karta hoon.",
        'anxious' => "Thoda anxious lag rahe ho. Chalo kuch calming cheezein dekhte hain.",
        'excited' => "Waah! Aap excited lag rahe ho. Let's have some fun.",
        'calm'    => "Aap kaafi calm lag rahe ho. Chalo kuch relaxing recommendations dekhte hain.",
    ];

    $out = [
        'success'    => true,
        'mood'       => $mood,
        'label'      => $labelRaw,
        'confidence' => $confidence,
        'reply'      => $replies[$mood] ?? "I think you're feeling {$mood}.",
    ];
    echo json_encode($out, JSON_UNESCAPED_UNICODE);
    exit;
}

/* ---------- If not POST: render UI ---------- */
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>MoodBuddy — Chatbot</title>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
  *
  {
    box-sizing:border-box
}
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
            font-family: 'Exo 2', sans-serif;
            background: var(--light-bg);
            color: var(--text-primary);
            overflow-x: hidden;
            position: relative;
            /* cursor: none; */
            display: flex;
            align-items: center;
            justify-content: center;
        }
  .meta{
    font-size:12px;
    color:#475569;
    margin-top:6px
}
  .error{
    color:#b91c1c;background:#fee2e2;padding:8px;border-radius:8px;margin-bottom:10px}
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
    .card {
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
    .messages {
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

    .messages::-webkit-scrollbar {
      width: 6px;
    }

    .messages::-webkit-scrollbar-track {
      background: rgba(236, 72, 153, 0.1);
      border-radius: 10px;
    }

    .messages::-webkit-scrollbar-thumb {
      background: linear-gradient(135deg, #ec4899, #0ea5e9);
      border-radius: 10px;
    }

    /* Enhanced message styling with better animations and effects */
    .msg {
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

    .msg:hover {
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
    .input {
      display: flex;
      align-items: center;
      background: rgba(255, 255, 255, 0.98);
      padding: 24px 28px;
      border-top: 2px solid rgba(236, 72, 153, 0.2);
      backdrop-filter: blur(20px);
      position: relative;
      gap: 1vw;
    }

    .input::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 2px;
      background: linear-gradient(90deg, transparent, rgba(236, 72, 153, 0.4), transparent);
    }

    #inputField {
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

    #inputField:focus {
      border-color: #ec4899;
      box-shadow: 
        0 0 0 6px rgba(236, 72, 153, 0.2),
        0 12px 35px rgba(236, 72, 153, 0.25);
      background: rgba(255, 255, 255, 1);
      transform: translateY(-2px) scale(1.02);
    }

    #inputField::placeholder {
      color: rgba(148, 163, 184, 0.8);
      font-weight: 400;
    }
    .input button {
        background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 24px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            /* gap: 0.5rem; */
            transition: all 0.3s ease;
            font-size: 0.9rem;
            position: relative;
            overflow: hidden;
            width: 90px;
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
<div class="card" role="application" aria-label="MoodBuddy Chatbot">
  <div class="header">
  <img src="assets/img/chatbot-avator.jpg" alt="Bot Avatar">
  <span>Mood Buddy😄✨</span>
  </div>
  <div class="messages" id="messages" aria-live="polite"></div>

  <div class="input">
    <input id="inputField" placeholder="Type your answer and press Send" aria-label="Type your answer" />
    <button id="sendBtn">Send</button>
  </div>
</div>

<script>
(() => {
  const questions = [
    "How are you feeling today?",
    "Did you sleep well last night?",
    "How is your energy level right now?",
    "How would you describe your mood overall?",
    "Do you feel like talking to people or being alone?"
  ];

  let idx = 0;
  let answers = [];
  const messagesEl = document.getElementById('messages');
  const input = document.getElementById('inputField');
  const btn = document.getElementById('sendBtn');

  function append(text, who='bot', meta='') {
    const d = document.createElement('div');
    d.className = 'msg ' + (who==='bot' ? 'bot' : 'user');
    d.innerHTML = `<div>${escapeHtml(text)}</div>${meta?'<div class="meta">'+escapeHtml(meta)+'</div>':''}`;
    messagesEl.appendChild(d);
    messagesEl.scrollTop = messagesEl.scrollHeight;
  }
  function escapeHtml(s) {
    return String(s).replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;');
  }

  function ask() {
    if (idx < questions.length) {
      append(questions[idx], 'bot');
    } else {
      // send to server
      append('Analyzing your answers... 🔎', 'bot');
      sendAnswers();
    }
  }

  async function sendAnswers() {
    try {
      const res = await fetch(window.location.href, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ answers })
      });

      const json = await res.json();

      if (!res.ok || json.error) {
        const err = json.error || ('Server returned status ' + res.status);
        append('Error: ' + err, 'bot');
        // show raw if present
        if (json.raw_hf) {
          append('Raw HF (debug): ' + JSON.stringify(json.raw_hf).slice(0,300) + '...', 'bot');
        }
        return;
      }

      // success
      append(json.reply || ('I think you are feeling ' + json.mood), 'bot', `Detected: ${json.label} (confidence ${json.confidence})`);

      // redirect after short delay to mood_result.php
      setTimeout(() => {
        window.location.href = 'mood_result.php';
      }, 1800);

    } catch (err) {
      append('Network error: ' + err.message, 'bot');
      console.error(err);
    }
  }

  btn.addEventListener('click', sendAnswer);
  input.addEventListener('keydown', (e) => { if (e.key === 'Enter') sendAnswer(); });

  function sendAnswer() {
    const v = input.value.trim();
    if (!v) return;
    append(v, 'user');
    answers.push(v);
    input.value = '';
    idx++;
    setTimeout(ask, 400);
  }

  // start conversation
  append("Hey! I'll ask 5 quick questions to detect your mood. Ready? 😊", 'bot');
  setTimeout(ask, 900);
})();
</script>
</body>
</html>
