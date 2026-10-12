<?php
require_once __DIR__ . '/../../core/core.php';
require_once __DIR__ . '/../../controllers/ProductController.php';
require_admin();

// For the header's link paths:
$base = '../../';   

$controller = new ProductController();
$editBrand = null;

// Loading the brand if it is in the edit mode:
if (isset($_GET['edit_id'])) {
    $editBrand = $controller->getBrandById((int)$_GET['edit_id']);
}

// Fetching all the brands for the table:
$brands = $controller->getAllBrands();
if (!is_array($brands)) {
    $brands = [];
}
include __DIR__ . '/../layout/header.php';
?>

<!-- Page header -->
<div class="page-header">
    <i class="fa fa-tags"></i>
    <div>
        <h1>Manage Brands</h1>
        <p>Add, edit, or remove the brands in your store.</p>
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
        <i class="fa <?= $editBrand ? 'fa-pencil' : 'fa-plus-circle' ?>"></i>
        <?= $editBrand ? 'Edit Brand' : 'Add New Brand' ?>
    </h3>
    <form action="<?= $base ?>actions/<?= $editBrand ? 'update_brand_action.php' : 'add_brand_action.php' ?>" method="POST" class="form-inline">
        <?php if ($editBrand): ?>
            <input type="hidden" name="brand_id" value="<?= $editBrand['brand_id'] ?>">
        <?php endif; ?>
        <input type="text" name="brand_name"
               value="<?= $editBrand ? htmlspecialchars($editBrand['brand_name']) : '' ?>"
               placeholder="Enter brand name..." required>
        <button type="submit" class="btn">
            <i class="fa <?= $editBrand ? 'fa-save' : 'fa-plus' ?>"></i>
            <?= $editBrand ? 'Update Brand' : 'Add Brand' ?>
        </button>
        <?php if ($editBrand): ?>
            <a href="brand.php" class="btn btn-secondary"><i class="fa fa-times"></i> Cancel</a>
        <?php endif; ?>
    </form>
</div>

<!-- All Brands List Panel -->
<div class="panel">
    <h3><i class="fa fa-list"></i> All Brands</h3>

    <?php if (empty($brands)): ?>
        <div class="empty-state">
            <i class="fa fa-tags"></i>
            <p>No brands yet.</p>
            <small>Add your first one using the form above.</small>
        </div>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:80px;">ID</th>
                    <th>Brand Name</th>
                    <th style="width:180px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($brands as $brand): ?>
                <tr>
                    <td>#<?= $brand['brand_id'] ?></td>
                    <td><?= htmlspecialchars($brand['brand_name']) ?></td>
                    <td>
                        <a href="brand.php?edit_id=<?= $brand['brand_id'] ?>" class="action-link action-edit">
                            <i class="fa fa-pencil"></i> Edit
                        </a>
                        <a href="<?= $base ?>actions/delete_brand_action.php?id=<?= $brand['brand_id'] ?>"
                           onclick="return confirm('Delete this brand?');" class="action-link action-delete">
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