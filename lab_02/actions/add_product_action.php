<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/admin/product.php');
}

// Sanitizing the inputs:
$cat      = (int)$_POST['product_cat'];
$brand    = (int)$_POST['product_brand'];
$title    = trim(strip_tags($_POST['product_title']));
$price    = (float)$_POST['product_price'];
$desc     = trim(strip_tags($_POST['product_desc']));
$keywords = trim(strip_tags($_POST['product_keywords']));

// Validate
if (empty($title) || empty($desc) || $cat <= 0 || $brand <= 0 || $price <= 0) {
    $_SESSION['error'] = 'Please fill in all fields correctly.';
    redirect('../views/admin/product.php');
}

// Handling image upload:
$filename = '';
if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === 0) {
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($_FILES['product_image']['type'], $allowed)) {
        $_SESSION['error'] = 'Invalid image type. Only JPG, PNG, GIF, WEBP allowed.';
        redirect('../views/admin/product.php');
    }
    $filename = uniqid() . '_' . basename($_FILES['product_image']['name']);
    move_uploaded_file($_FILES['product_image']['tmp_name'], __DIR__ . '/../images/products/' . $filename);
}

$controller = new ProductController();
if ($controller->addProduct($cat, $brand, $title, $price, $desc, $filename, $keywords)) {
    $_SESSION['success'] = 'Product added successfully.';
} else {
    $_SESSION['error'] = 'Failed to add product.';
}
redirect('../views/admin/product.php');
?>