<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['product_id']);
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'id'       => $product['id'],
                'title'    => $product['title'],
                'price'    => $product['price'],
                'image'    => $product['image'], // 👈 image bhi save karni zaroori hai
                'quantity' => 1
            ];
        }

        echo json_encode(['success' => true, 'cart' => $_SESSION['cart']]);
    } else {
        echo json_encode(['success' => false]);
    }
}
