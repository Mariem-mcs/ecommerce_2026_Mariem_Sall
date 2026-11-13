<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/cartController.php';

$base = '../';

if (!isset($_GET['order_id'])) {
    redirect('../index.php');
}

$cart = new cartController();
$order = $cart->getOrderById((int)$_GET['order_id']);

if (!$order) {
    redirect('../index.php');
}

include __DIR__ . '/layout/header.php';
?>
<div class="panel" style="text-align: center; max-width: 600px; margin: 40px auto;">
    <i class="fa fa-check-circle" style="font-size: 60px; color: #10b981; margin-bottom: 20px;"></i>
    <h2 style="color: #1f2937; margin-top: 0;">Payment Successful!</h2>
    <p style="color: #6b7280;">Thank you for your purchase. Your order has been placed.</p>
    
    <hr style="margin: 30px 0; border: none; border-top: 1px dashed #d1d5db;">

    <div style="text-align: left;">
        <p><strong>Invoice No:</strong> #<?= $order['invoice_no'] ?></p>
        <p><strong>Date:</strong> <?= $order['order_date'] ?></p>
        <p><strong>Status:</strong> <span style="color: #10b981; font-weight: bold;"><?= ucfirst($order['order_status']) ?></span></p>
        
        <h4 style="margin-top: 20px;">Items Ordered:</h4>
        <ul style="padding-left: 20px;">
            <?php foreach ($order['items'] as $item): ?>
                <li><?= $item['qty'] ?>x <?= htmlspecialchars($item['product_title']) ?> (GHS <?= number_format($item['product_price'], 2) ?>)</li>
            <?php endforeach; ?>
        </ul>
    </div>

    <a href="<?= $base ?>index.php" class="btn" style="margin-top: 30px;">Continue Shopping</a>
</div>
<?php include __DIR__ . '/layout/footer.php'; ?>