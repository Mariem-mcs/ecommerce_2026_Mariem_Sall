<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/admin/product.php');
}

$id       = (int)$_POST['product_id'];
$cat      = (int)$_POST['product_cat'];
$brand    = (int)$_POST['product_brand'];
$title    = trim(strip_tags($_POST['product_title']));
$price    = (float)$_POST['product_price'];
$desc     = trim(strip_tags($_POST['product_desc']));
$keywords = trim(strip_tags($_POST['product_keywords']));

// Keep the existing image if no new one is uploaded
$filename = $_POST['existing_image'] ?? '';

if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === 0) {
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($_FILES['product_image']['type'], $allowed)) {
        $_SESSION['error'] = 'Invalid image type.';
        redirect('../views/admin/product.php?edit_id=' . $id);
    }
    $filename = uniqid() . '_' . basename($_FILES['product_image']['name']);
    move_uploaded_file($_FILES['product_image']['tmp_name'], __DIR__ . '/../images/products/' . $filename);
}

$controller = new ProductController();
if ($controller->updateProduct($id, $cat, $brand, $title, $price, $desc, $filename, $keywords)) {
    $_SESSION['success'] = 'Product updated successfully.';
} else {
    $_SESSION['error'] = 'Failed to update product.';
}
redirect('../views/admin/product.php');
?>