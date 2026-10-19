<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/cartController.php'; // Lowercase

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove'])) {
    $ip = get_ip();
    $cart = new cartController(); 
    foreach ($_POST['remove'] as $product_id) {
        $cart->removeFromCart((int)$product_id, $ip);
    }
    $_SESSION['success'] = 'Items removed from cart.';
}
redirect('../views/cart.php');
?>