<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/cartController.php';
require_login(); 

$base = '../';
$ip = get_ip();
$cart = new cartController();
$items = $cart->getCartItems($ip);
$total = $cart->getCartTotal($ip);

if (empty($items)) {
    redirect('../index.php');
}

include __DIR__ . '/layout/header.php';
?>

<div class="page-header">
    <i class="fa fa-credit-card"></i>
    <div>
        <h1>Secure Checkout</h1>
        <p>Complete your payment to place the order.</p>
    </div>
</div>

<div class="panel">
    <h3><i class="fa fa-shopping-basket"></i> Order Summary</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['product_title']) ?></td>
                    <td><?= $item['qty'] ?></td>
                    <td>GHS <?= number_format($item['product_price'] * $item['qty'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" style="text-align: right;">Total:</th>
                <th>GHS <?= number_format($total, 2) ?></th>
            </tr>
        </tfoot>
    </table>

    <div style="text-align: center; margin-top: 30px;">
        <button onclick="payWithPaystack()" class="btn" style="background: #10b981; padding: 15px 40px; font-size: 18px;">
            <i class="fa fa-lock"></i> Pay GHS <?= number_format($total, 2) ?> Now
        </button>
    </div>
</div>

<!-- Paystack Inline JS -->
<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
  function payWithPaystack() {
    var handler = PaystackPop.setup({
      key: 'pk_test_6621a0259e0ff7700a2266bc737608dbac750395', 
      email: '<?= htmlspecialchars($_SESSION["customer_email"]) ?>',
      amount: <?= $total * 100 ?>, 
      currency: 'GHS',
      callback: function(response) {
        // Using window.location.href to force the redirect
        window.location.href = '../actions/process_payment_action.php?ref=' + response.reference;
      },
      onClose: function() {
        alert('Window closed. Payment not completed.');
      }
    });
    handler.openIframe();
  }
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>