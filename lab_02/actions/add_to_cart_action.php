<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/cartController.php'; // Lowercase

if (isset($_GET['add_cart'])) {
    $product_id = (int)$_GET['add_cart'];
    $ip = get_ip();
    
    $cart = new cartController(); 
    if ($cart->isInCart($product_id, $ip)) {
        $_SESSION['error'] = 'This item is already in your cart. Go to cart to update quantity.';
    } else {
        if ($cart->addToCart($product_id, 1, $ip)) {
            $_SESSION['success'] = 'Item added to cart!';
        } else {
            $_SESSION['error'] = 'Failed to add item.';
        }
    }

    $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../index.php';
    header("Location: $redirect");
    exit();
} else {
    redirect('../index.php');
}
?>