<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['cat_id']) && !empty($_POST['cat_name'])) {
    $id = (int)$_POST['cat_id'];
    $name = trim(strip_tags($_POST['cat_name']));
    $controller = new ProductController();
    if ($controller->updateCategory($id, $name)) {
        $_SESSION['success'] = 'Category updated successfully.';
    } else {
        $_SESSION['error'] = 'Failed to update category.';
    }
}
redirect('../views/admin/category.php');
?>