<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['brand_id']) && !empty($_POST['brand_name'])) {
    $id = (int)$_POST['brand_id'];
    $name = trim(strip_tags($_POST['brand_name']));
    $controller = new ProductController();
    if ($controller->updateBrand($id, $name)) {
        $_SESSION['success'] = 'Brand updated successfully.';
    } else {
        $_SESSION['error'] = 'Failed to update brand.';
    }
}
redirect('../views/admin/brand.php');
?>