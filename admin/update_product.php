<?php
session_start();
require_once __DIR__ . '/db.php';
if (!app_is_admin()) { http_response_code(403); exit('Not allowed'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $price = $_POST['price'];
    $mood = $_POST['mood'];
    $description = $_POST['description'] ?? '';
    
    // Handle image upload if a new image is provided
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = 'uploads/products/';
        $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', basename($_FILES['image']['name']));
        $uploadPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            $image = $fileName; // front-end prepends admin/uploads/products/
            $stmt = $pdo->prepare("UPDATE products SET title = ?, price = ?, mood = ?, description = ?, image = ? WHERE id = ?");
            $stmt->execute([$title, $price, $mood, $description, $image, $id]);
        } else {
            echo "Error uploading image";
            exit;
        }
    } else {
        $stmt = $pdo->prepare("UPDATE products SET title = ?, price = ?, mood = ?, description = ? WHERE id = ?");
        $stmt->execute([$title, $price, $mood, $description, $id]);
    }
    
    echo "Product updated successfully!";
}
?>