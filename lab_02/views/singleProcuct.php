<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$base = '../';

// Require a valid product ID
if (!isset($_GET['pro_id']) || !is_numeric($_GET['pro_id'])) {
    $_SESSION['error'] = 'Invalid product.';
    redirect('../index.php');
}

$controller = new ProductController();
$product = $controller->getProductById((int)$_GET['pro_id']);

if (!$product) {
    $_SESSION['error'] = 'Product not found.';
    redirect('../index.php');
}

include __DIR__ . '/layout/header.php';
?>

<div class="page-header">
    <i class="fa fa-cube"></i>
    <div>
        <h1><?= htmlspecialchars($product['product_title']) ?></h1>
        <p><?= htmlspecialchars($product['cat_name'] ?? 'Uncategorised') ?> · 
           <?= htmlspecialchars($product['brand_name'] ?? 'No brand') ?></p>
    </div>
</div>

<div class="panel" style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
    <div>
        <?php if (!empty($product['product_image'])): ?>
            <img src="<?= $base ?>images/products/<?= htmlspecialchars($product['product_image']) ?>"
                 alt="" style="width:100%; border-radius:10px;">
        <?php else: ?>
            <div class="no-image" style="height:300px; border-radius:10px;">
                <i class="fa fa-image"></i>
            </div>
        <?php endif; ?>
    </div>

    <div>
        <h2 style="color:#2563eb; font-size: 28px; margin: 0 0 15px 0;">
            GHS <?= number_format($product['product_price'], 2) ?>
        </h2>

        <p style="color:#6b7280; margin-bottom: 20px;">
            <?= nl2br(htmlspecialchars($product['product_desc'])) ?>
        </p>

        <?php if (!empty($product['product_keywords'])): ?>
            <p style="font-size: 13px; color:#9ca3af;">
                <strong>Keywords:</strong> <?= htmlspecialchars($product['product_keywords']) ?>
            </p>
        <?php endif; ?>

        <hr style="margin: 20px 0; border: none; border-top: 1px solid #e5e7eb;">

        <a href="<?= $base ?>index.php" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Back to Shop
        </a>
    </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>