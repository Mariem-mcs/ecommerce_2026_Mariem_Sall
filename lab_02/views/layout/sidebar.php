<?php
require_once __DIR__ . '/../../core/core.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

$controller = new ProductController();
$categories = $controller->getAllCategories();
$brands = $controller->getAllBrands();

if (!is_array($categories)) $categories = [];
if (!is_array($brands)) $brands = [];
?>
<aside class="sidebar">
    <div class="sidebar-block">
        <h4><i class="fa fa-folder-open"></i> Categories</h4>
        <ul>
            <?php if (empty($categories)): ?>
                <li><em>No categories yet</em></li>
            <?php else: ?>
                <?php foreach ($categories as $c): ?>
                    <li><a href="<?= $base ?>index.php?cat=<?= $c['cat_id'] ?>">
                        <?= htmlspecialchars($c['cat_name']) ?>
                    </a></li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>

    <div class="sidebar-block">
        <h4><i class="fa fa-tags"></i> Brands</h4>
        <ul>
            <?php if (empty($brands)): ?>
                <li><em>No brands yet</em></li>
            <?php else: ?>
                <?php foreach ($brands as $b): ?>
                    <li><a href="<?= $base ?>index.php?brand=<?= $b['brand_id'] ?>">
                        <?= htmlspecialchars($b['brand_name']) ?>
                    </a></li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</aside>