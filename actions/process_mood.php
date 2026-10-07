<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $allowed = ['happy', 'sad', 'angry', 'anxious', 'excited', 'calm'];
    $mood = strtolower(trim($_POST['mood'] ?? ''));
    $_SESSION['mood'] = in_array($mood, $allowed, true) ? $mood : 'happy';
    $_SESSION['gender'] = $_POST['gender'] ?? '';

    // Redirect to result page (no resubmission issue)
    header("Location: ../mood_result.php");
    exit();
} else {
    // agar koi sidha access kare
    header("Location: ../homepage.php");
    exit();
}
