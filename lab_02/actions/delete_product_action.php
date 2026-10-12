<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $controller = new ProductController();
    if ($controller->deleteProduct($id)) {
        $_SESSION['success'] = 'Product deleted successfully.';
    } else {
        $_SESSION['error'] = 'Failed to delete product.';
    }
}
redirect('../views/admin/product.php');
?>