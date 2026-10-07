<?php
session_start();
require_once __DIR__ . '/db.php';
if (!app_is_admin()) { http_response_code(403); exit('Not allowed'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $singer = $_POST['singer'];
    $mood = $_POST['mood'];
    $file_path = $_POST['file_path'];
    $description = $_POST['description'] ?? '';
    
    $stmt = $pdo->prepare("UPDATE songs SET title = ?, singer = ?, mood = ?, file_path = ?, description = ? WHERE id = ?");
    
    if ($stmt->execute([$title, $singer, $mood, $file_path, $description, $id])) {
        echo "Song updated successfully!";
    } else {
        echo "Error updating song: " . implode(", ", $stmt->errorInfo());
    }
}
?>