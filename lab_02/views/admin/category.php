<?php
require_once __DIR__ . '/../../core/core.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

require_admin();
// For the header's link paths:
$base = '../../';   

$controller = new ProductController();
$editCategory = null;
// Loading the category if it is in edit mode:
if (isset($_GET['edit_id'])) {
    $editCategory = $controller->getCategoryById((int)$_GET['edit_id']);
}

// Fetching all categories for the table:
$categories = $controller->getAllCategories();
if (!is_array($categories)) {
    $categories = [];
}
include __DIR__ . '/../layout/header.php';
?>

<!-- Page header -->
<div class="page-header">
    <i class="fa fa-folder-open"></i>
    <div>
        <h1>Manage Categories</h1>
        <p>Organise your products into categories.</p>
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
        <i class="fa <?= $editCategory ? 'fa-pencil' : 'fa-plus-circle' ?>"></i>
        <?= $editCategory ? 'Edit Category' : 'Add New Category' ?>
    </h3>
    <form action="<?= $base ?>actions/<?= $editCategory ? 'update_category_action.php' : 'add_category_action.php' ?>" method="POST" class="form-inline">
        <?php if ($editCategory): ?>
            <input type="hidden" name="cat_id" value="<?= $editCategory['cat_id'] ?>">
        <?php endif; ?>
        <input type="text" name="cat_name"
               value="<?= $editCategory ? htmlspecialchars($editCategory['cat_name']) : '' ?>"
               placeholder="Enter category name..." required>
        <button type="submit" class="btn">
            <i class="fa <?= $editCategory ? 'fa-save' : 'fa-plus' ?>"></i>
            <?= $editCategory ? 'Update Category' : 'Add Category' ?>
        </button>
        <?php if ($editCategory): ?>
            <a href="category.php" class="btn btn-secondary"><i class="fa fa-times"></i> Cancel</a>
        <?php endif; ?>
    </form>
</div>

<!-- All Categories List Panel -->
<div class="panel">
    <h3><i class="fa fa-list"></i> All Categories</h3>

    <?php if (empty($categories)): ?>
        <div class="empty-state">
            <i class="fa fa-folder-open"></i>
            <p>No categories yet.</p>
            <small>Add your first one using the form above.</small>
        </div>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:80px;">ID</th>
                    <th>Category Name</th>
                    <th style="width:180px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                <tr>
                    <td>#<?= $cat['cat_id'] ?></td>
                    <td><?= htmlspecialchars($cat['cat_name']) ?></td>
                    <td>
                        <a href="category.php?edit_id=<?= $cat['cat_id'] ?>" class="action-link action-edit">
                            <i class="fa fa-pencil"></i> Edit
                        </a>
                        <a href="<?= $base ?>actions/delete_category_action.php?id=<?= $cat['cat_id'] ?>"
                           onclick="return confirm('Delete this category?');" class="action-link action-delete">
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