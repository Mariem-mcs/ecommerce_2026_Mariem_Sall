<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/cartController.php';

$base = '../';
$ip = get_ip();
$cartController = new cartController();
$items = $cartController->getCartItems($ip);

include __DIR__ . '/layout/header.php';
?>
<div class="page-header">
    <i class="fa fa-shopping-cart"></i>
    <div>
        <h1>Your Shopping Cart</h1>
        <p>Review your items before checkout.</p>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<div class="panel">
    <?php if (empty($items)): ?>
        <div class="empty-state">
            <i class="fa fa-shopping-basket"></i>
            <p>Your cart is empty.</p>
            <a href="<?= $base ?>index.php" class="btn" style="margin-top:15px;">Start Shopping</a>
        </div>
    <?php else: ?>
        <form id="updateForm" action="<?= $base ?>actions/update_qty_action.php" method="POST"></form>
        <form id="removeForm" action="<?= $base ?>actions/remove_from_cart_action.php" method="POST"></form>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 60px;">Remove</th>
                    <th>Product</th>
                    <th style="width: 120px;">Price</th>
                    <th style="width: 120px;">Quantity</th>
                    <th style="width: 120px;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $grandTotal = 0; ?>
                <?php foreach ($items as $item): ?>
                    <?php $lineTotal = $item['product_price'] * $item['qty']; $grandTotal += $lineTotal; ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="remove[]" value="<?= $item['p_id'] ?>" form="removeForm">
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <?php if (!empty($item['product_image'])): ?>
                                    <img src="<?= $base ?>images/products/<?= htmlspecialchars($item['product_image']) ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                                <?php endif; ?>
                                <span style="font-weight: 600;"><?= htmlspecialchars($item['product_title']) ?></span>
                            </div>
                        </td>
                        <td>GHS <?= number_format($item['product_price'], 2) ?></td>
                        <td>
                            <input type="number" name="qty[]" value="<?= $item['qty'] ?>" min="1" form="updateForm" style="width: 70px; padding: 8px; border: 1px solid #d1d5db; border-radius: 5px;">
                            <input type="hidden" name="update[]" value="<?= $item['p_id'] ?>" form="updateForm">
                        </td>
                        <td style="font-weight: bold;">GHS <?= number_format($lineTotal, 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right; font-weight: bold; font-size: 16px;">Grand Total:</td>
                    <td style="font-weight: bold; font-size: 18px; color: #2563eb;">GHS <?= number_format($grandTotal, 2) ?></td>
                </tr>
            </tfoot>
        </table>

        <div style="margin-top: 25px; display: flex; gap: 10px; flex-wrap: wrap;">
            <button type="submit" form="updateForm" class="btn btn-secondary"><i class="fa fa-refresh"></i> Update Quantities</button>
            <button type="submit" form="removeForm" class="btn btn-danger" onclick="return confirm('Are you sure you want to remove these items?');"><i class="fa fa-trash"></i> Remove Selected</button>
            <a href="<?= $base ?>index.php" class="btn"><i class="fa fa-arrow-left"></i> Continue Shopping</a>
            
            <!-- Checkout button: non-functional for now-->
            <a href="<?= $base ?>views/checkout.php" class="btn" style="margin-left: auto; background: #10b981;"><i class="fa fa-check-circle"></i> Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>