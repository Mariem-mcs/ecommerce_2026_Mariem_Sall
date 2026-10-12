<?php
require_once __DIR__ . '/../../core/core.php';
require_once __DIR__ . '/../../controllers/ProductController.php';
require_admin();

$base = '../../';

$controller = new ProductController();
$editProduct = null;

// Loading the product if it is in edit mode:
if (isset($_GET['edit_id'])) {
    $editProduct = $controller->getProductById((int)$_GET['edit_id']);
}

// Fetching all data for the tables and dropdowns:
$products   = $controller->getAllProducts();
$categories = $controller->getAllCategories();
$brands     = $controller->getAllBrands();

if (!is_array($products))   $products = [];
if (!is_array($categories)) $categories = [];
if (!is_array($brands))     $brands = [];

include __DIR__ . '/../layout/header.php';
?>

<!-- Page header -->
<div class="page-header">
    <i class="fa fa-cube"></i>
    <div>
        <h1>Manage Products</h1>
        <p>Add, edit, or remove the products in your store.</p>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<!-- Add/Edit Form Panel -->
<div class="panel">
    <h3>
        <i class="fa <?= $editProduct ? 'fa-pencil' : 'fa-plus-circle' ?>"></i>
        <?= $editProduct ? 'Edit Product' : 'Add New Product' ?>
    </h3>

    <form action="<?= $base ?>actions/<?= $editProduct ? 'update_product_action.php' : 'add_product_action.php' ?>"
          method="POST" enctype="multipart/form-data">

        <?php if ($editProduct): ?>
            <input type="hidden" name="product_id" value="<?= $editProduct['product_id'] ?>">
            <input type="hidden" name="existing_image" value="<?= htmlspecialchars($editProduct['product_image']) ?>">
        <?php endif; ?>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div>
                <label>Product Title:</label><br>
                <input type="text" name="product_title" style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px;"
                       value="<?= $editProduct ? htmlspecialchars($editProduct['product_title']) : '' ?>" required>
            </div>
            <div>
                <label>Price (GHS):</label><br>
                <input type="number" step="0.01" name="product_price" style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px;"
                       value="<?= $editProduct ? htmlspecialchars($editProduct['product_price']) : '' ?>" required>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div>
                <label>Category:</label><br>
                <select name="product_cat" style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px;" required>
                    <option value="">-- Select Category --</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['cat_id'] ?>" <?= ($editProduct && $editProduct['product_cat'] == $c['cat_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['cat_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>Brand:</label><br>
                <select name="product_brand" style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px;" required>
                    <option value="">-- Select Brand --</option>
                    <?php foreach ($brands as $b): ?>
                        <option value="<?= $b['brand_id'] ?>" <?= ($editProduct && $editProduct['product_brand'] == $b['brand_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($b['brand_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div style="margin-bottom: 15px;">
            <label>Description:</label><br>
            <textarea name="product_desc" rows="4" style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px;" required><?= $editProduct ? htmlspecialchars($editProduct['product_desc']) : '' ?></textarea>
        </div>

        <div style="margin-bottom: 15px;">
            <label>Keywords (for search):</label><br>
            <input type="text" name="product_keywords" style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px;"
                   value="<?= $editProduct ? htmlspecialchars($editProduct['product_keywords']) : '' ?>"
                   placeholder="e.g., phone, smartphone, mobile">
        </div>

        <div style="margin-bottom: 15px;">
            <label>Product Image:</label><br>
            <input type="file" name="product_image" accept="image/*" <?= $editProduct ? '' : 'required' ?>>
            <?php if ($editProduct && !empty($editProduct['product_image'])): ?>
                <div style="margin-top: 10px;">
                    <p style="color:#6b7280; font-size:13px;">Current image:</p>
                    <img src="<?= $base ?>images/products/<?= htmlspecialchars($editProduct['product_image']) ?>"
                         style="max-width:150px; border-radius:6px;" alt="current">
                </div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn">
            <i class="fa <?= $editProduct ? 'fa-save' : 'fa-plus' ?>"></i>
            <?= $editProduct ? 'Update Product' : 'Add Product' ?>
        </button>
        <?php if ($editProduct): ?>
            <a href="product.php" class="btn btn-secondary"><i class="fa fa-times"></i> Cancel</a>
        <?php endif; ?>
    </form>
</div>

<!-- All Products List Panel -->
<div class="panel">
    <h3><i class="fa fa-list"></i> All Products</h3>

    <?php if (empty($products)): ?>
        <div class="empty-state">
            <i class="fa fa-cube"></i>
            <p>No products yet.</p>
            <small>Add your first one using the form above.</small>
        </div>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:70px;">Image</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th style="width:100px;">Price</th>
                    <th style="width:180px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td>
                        <?php if (!empty($p['product_image'])): ?>
                            <img src="<?= $base ?>images/products/<?= htmlspecialchars($p['product_image']) ?>"
                                 style="width:50px; height:50px; object-fit:cover; border-radius:6px;" alt="">
                        <?php else: ?>
                            <span style="color:#d1d5db;">—</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($p['product_title']) ?></td>
                    <td><?= htmlspecialchars($p['cat_name'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($p['brand_name'] ?? 'N/A') ?></td>
                    <td>GHS <?= number_format($p['product_price'], 2) ?></td>
                    <td>
                        <a href="product.php?edit_id=<?= $p['product_id'] ?>" class="action-link action-edit">
                            <i class="fa fa-pencil"></i> Edit
                        </a>
                        <a href="<?= $base ?>actions/delete_product_action.php?id=<?= $p['product_id'] ?>"
                           onclick="return confirm('Delete this product?');" class="action-link action-delete">
                            <i class="fa fa-trash"></i> Delete
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>