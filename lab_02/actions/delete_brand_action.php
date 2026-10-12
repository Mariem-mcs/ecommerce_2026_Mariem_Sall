<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $controller = new ProductController();
    if ($controller->deleteBrand($id)) {
        $_SESSION['success'] = 'Brand deleted successfully.';
    } else {
        $_SESSION['error'] = 'Failed to delete brand.';
    }
}
redirect('../views/admin/brand.php');
?>