<?php
// Database connection - values come from environment variables (see config.php / .env.example)
require_once __DIR__ . '/../includes/config.php';

try {
    $pdo = app_pdo();
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('DB connection failed: ' . $e->getMessage());
    http_response_code(500);
    die("Database connection failed. Please check DB_HOST / DB_NAME / DB_USER / DB_PASS settings.");
}
?>
