<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['cat_name'])) {
    $name = trim(strip_tags($_POST['cat_name']));
    $controller = new ProductController();
    if ($controller->addCategory($name)) {
        $_SESSION['success'] = 'Category added successfully.';
    } else {
        $_SESSION['error'] = 'Failed to add category.';
    }
}
redirect('../views/admin/category.php');
?>