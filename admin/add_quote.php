<?php
require_once __DIR__ . '/db.php';
require_once 'auth_check.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
  $text = $_POST['text'];
  $author = $_POST['author'];
  $mood = $_POST['mood'];
  
  $stmt = $pdo->prepare("INSERT INTO quotes (text, author, mood) VALUES (?, ?, ?)");
  $stmt->execute([$text, $author, $mood]);
  
  header('Location: dashboard.php?success=quote_added');
  exit();
}
?>
<!-- Similar structure to add_product.php but with quote-specific fields -->