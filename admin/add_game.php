<?php
require_once __DIR__ . '/db.php';
require_once 'auth_check.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = $_POST['name'];
  $description = $_POST['description'];
  $mood = $_POST['mood'];
  $url = $_POST['url'];
  
  // Handle file upload
  $image = '';
  if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $target_dir = "uploads/games/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0775, true);
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    $image = $target_file;
  }
  
  $stmt = $pdo->prepare("INSERT INTO games (name, description, mood, url, image) VALUES (?, ?, ?, ?, ?)");
  $stmt->execute([$name, $description, $mood, $url, $image]);
  
  header('Location: dashboard.php?success=game_added');
  exit();
}
?>
<!-- Similar structure to add_product.php but with game-specific fields -->