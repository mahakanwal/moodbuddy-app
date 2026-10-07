<?php
// Admin-only guard. Login sets $_SESSION['logged_in'] and $_SESSION['role'] (see admin/index.php)
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../includes/config.php';

if (empty($_SESSION['logged_in'])) {
    header('Location: index.php');
    exit();
}
if (!app_is_admin()) {
    http_response_code(403);
    header('Location: ../mood_result.php');
    exit();
}

// Session timeout (30 minutes)
$inactive = 1800;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactive)) {
    session_unset();
    session_destroy();
    header('Location: index.php?timeout=1');
    exit();
}
$_SESSION['last_activity'] = time();
?>
