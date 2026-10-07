<?php
session_start();
header('Content-Type: application/json');

$allowed = ['happy', 'sad', 'angry', 'anxious', 'excited', 'calm'];
$mood = strtolower(trim($_POST['mood'] ?? ''));

if (in_array($mood, $allowed, true)) {
    $_SESSION['mood'] = $mood;
    echo json_encode(['success' => true, 'mood' => $mood]);
} else {
    echo json_encode(['success' => false, 'error' => 'Mood not received']);
}
