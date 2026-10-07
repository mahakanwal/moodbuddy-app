<?php
session_start();
require_once __DIR__ . '/db.php';
if (!app_is_admin()) { http_response_code(403); exit('Not allowed'); }
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM songs WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $song = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($song);
}
?>