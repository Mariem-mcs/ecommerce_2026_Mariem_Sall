<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin(); // Kicks out non-admins

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['brand_name'])) {
    $name = trim(strip_tags($_POST['brand_name']));
    $controller = new ProductController();
    if ($controller->addBrand($name)) {
        $_SESSION['success'] = 'Brand added successfully.';
    } else {
        $_SESSION['error'] = 'Failed to add brand.';
    }
}
redirect('../views/admin/brand.php');
?>