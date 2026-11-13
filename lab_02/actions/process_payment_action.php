<?php
// Turn on error reporting so we can see what's wrong
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/cartController.php';

if (!isset($_GET['ref']) || !is_logged_in()) {
    redirect('../index.php');
}

$reference = $_GET['ref'];
$secret_key = 'sk_test_a709e5bb5f005aa80feb7d746edbd6d7c734fce3'; // YOUR SECRET KEY

// Check if cURL is installed on the server
if (!function_exists('curl_init')) {
    die("<h2 style='color:red'>Error: cURL is not installed on this server. Please contact your lecturer or hosting provider.</h2>");
}

// Verify payment with Paystack API
$url = 'https://api.paystack.co/transaction/verify/' . $reference;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $secret_key
]);
// For testing on servers with SSL issues:
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

$response = curl_exec($ch);

// Checking for cURL errors:
if ($response === false) {
    die("<h2 style='color:red'>cURL Error: " . curl_error($ch) . "</h2>");
}
$result = json_decode($response, true);
// Checking if payment was successful:
if ($result && $result['status'] == true && $result['data']['status'] == 'success') {
    $amount_paid = $result['data']['amount'] / 100; // Convert back from pesewas
    $customer_id = $_SESSION['customer_id'];
    $ip = get_ip();
    $cart = new cartController();
    $order_id = $cart->processPayment($customer_id, $ip, $reference, $amount_paid);

    if ($order_id) {
        redirect('../views/payment_success.php?order_id=' . $order_id);
    } else {
        $_SESSION['error'] = 'Payment successful, but failed to record order in database. Check if orders tables exist.';
        redirect('../views/cart.php');
    }
} else {
    $error_msg = isset($result['message']) ? $result['message'] : 'Unknown error';
    $_SESSION['error'] = 'Payment verification failed: ' . $error_msg;
    redirect('../views/cart.php');
}
?>