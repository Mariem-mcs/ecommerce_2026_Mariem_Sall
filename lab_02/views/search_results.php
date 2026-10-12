<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$base = '../';
$controller = new ProductController();
$products = [];
$query = '';
if (isset($_GET['user_query']) && trim($_GET['user_query']) !== '') {
    $query = trim($_GET['user_query']);
    $products = $controller->searchProducts($query);
}
if (!is_array($products)) $products = [];

include __DIR__ . '/layout/header.php';
?>

<div class="page-header">
    <i class="fa fa-search"></i>
    <div>
        <h1>Search Results</h1>
        <p>
            <?php if ($query): ?>
                <?= count($products) ?> result<?= count($products) == 1 ? '' : 's' ?> for "<strong><?= htmlspecialchars($query) ?></strong>"
            <?php else: ?>
                Enter a search term to find products.
            <?php endif; ?>
        </p>
    </div>
</div>

<?php if (empty($products)): ?>
    <div class="panel">
        <div class="empty-state">
            <i class="fa fa-search"></i>
            <p>No products found.</p>
            <small>Try a different search term.</small>
        </div>
    </div>
<?php else: ?>
    <div class="product-grid">
        <?php foreach ($products as $p): ?>
            <a href="<?= $base ?>views/single_product.php?pro_id=<?= $p['product_id'] ?>" class="product-card">
                <?php if (!empty($p['product_image'])): ?>
                    <img src="<?= $base ?>images/products/<?= htmlspecialchars($p['product_image']) ?>" alt="">
                <?php else: ?>
                    <div class="no-image"><i class="fa fa-image"></i></div>
                <?php endif; ?>
                <div class="info">
                    <p class="title"><?= htmlspecialchars($p['product_title']) ?></p>
                    <p class="price">GHS <?= number_format($p['product_price'], 2) ?></p>
                    <p class="meta">
                        <?= htmlspecialchars($p['brand_name'] ?? '—') ?> · 
                        <?= htmlspecialchars($p['cat_name'] ?? '—') ?>
                    </p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/layout/footer.php'; ?>