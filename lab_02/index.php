<?php
$base = '';
require_once __DIR__ . '/views/layout/header.php';
?>

<?php if (isset($_SESSION['success'])): ?>
    <p class="success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <p class="error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
<?php endif; ?>

<?php if (is_admin()): ?>
    <!--  The admin dashboard -->
    <div class="page-header">
        <i class="fa fa-dashboard"></i>
        <div>
            <h1>Admin Dashboard</h1>
            <p>Hello <strong><?= htmlspecialchars($_SESSION['customer_name']) ?></strong>, you have full control of the store.</p>
        </div>
    </div>

    <div class="card-grid">
        <a href="views/admin/brand.php" class="card">
            <div class="card-icon"><i class="fa fa-tags"></i></div>
            <h3>Manage Brands</h3>
            <p>Add, edit, or delete product brands.</p>
        </a>
        <a href="views/admin/category.php" class="card">
            <div class="card-icon"><i class="fa fa-folder-open"></i></div>
            <h3>Manage Categories</h3>
            <p>Organise products into categories.</p>
        </a>
        <a href="views/admin/product.php" class="card">
            <div class="card-icon"><i class="fa fa-cube"></i></div>
            <h3>Manage Products</h3>
            <p>Add, edit, or delete products.</p>
        </a>
        <a href="views/admin_register.php" class="card">
            <div class="card-icon"><i class="fa fa-user-plus"></i></div>
            <h3>Create Another Admin</h3>
            <p>Register a new administrator with the secret code.</p>
        </a>
    </div>

<?php else: ?>
    <!-- The shop view for guests and customers -->
    <?php
    require_once __DIR__ . '/controllers/ProductController.php';
    $controller = new ProductController();

    $products = [];
    $pageTitle = 'Featured Products';

    if (isset($_GET['cat'])) {
        $products = $controller->getProductsByCategory((int)$_GET['cat']);
        $cat = $controller->getCategoryById((int)$_GET['cat']);
        $pageTitle = 'Category: ' . ($cat ? $cat['cat_name'] : 'Unknown');
    } elseif (isset($_GET['brand'])) {
        $products = $controller->getProductsByBrand((int)$_GET['brand']);
        $brand = $controller->getBrandById((int)$_GET['brand']);
        $pageTitle = 'Brand: ' . ($brand ? $brand['brand_name'] : 'Unknown');
    } else {
        $products = $controller->getFeaturedProducts(6);
    }
    if (!is_array($products)) $products = [];
    ?>

    <div class="page-with-sidebar">
        <?php include __DIR__ . '/views/layout/sidebar.php'; ?>

        <main>
            <div class="page-header">
                <i class="fa fa-shopping-bag"></i>
                <div>
                    <h1><?= htmlspecialchars($pageTitle) ?></h1>
                    <p><?= count($products) ?> product<?= count($products) == 1 ? '' : 's' ?> available</p>
                </div>
            </div>

            <?php if (empty($products)): ?>
                <div class="panel">
                    <div class="empty-state">
                        <i class="fa fa-search"></i>
                        <p>No products found.</p>
                        <small>Try a different category or brand.</small>
                    </div>
                </div>
            <?php else: ?>
                <div class="product-grid">
                    <?php foreach ($products as $p): ?>
                        <a href="views/single_product.php?pro_id=<?= $p['product_id'] ?>" class="product-card">
                            <?php if (!empty($p['product_image'])): ?>
                                <img src="images/products/<?= htmlspecialchars($p['product_image']) ?>" alt="">
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
        </main>
    </div>

<?php endif; ?>

<?php require_once __DIR__ . '/views/layout/footer.php'; ?>