<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/cartController.php'; // Lowercase

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['qty'])) {
    $ip = get_ip();
    $cart = new cartController(); 
    foreach ($_POST['qty'] as $index => $qty) {
        $product_id = (int)$_POST['update'][$index];
        $new_qty = (int)$qty;
        if ($new_qty >= 1) {
            $cart->updateQty($product_id, $new_qty, $ip);
        }
    }
    $_SESSION['success'] = 'Cart updated.';
}
redirect('../views/cart.php');
?>