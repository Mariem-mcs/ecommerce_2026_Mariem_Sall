<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/login.php');
}

$email = trim(strip_tags($_POST['email']));
$password = $_POST['password'];

if (empty($email) || empty($password)) {
    $_SESSION['error'] = 'Email and password are required.';
    redirect('../views/login.php');
}

$controller = new CustomerController();
$result = $controller->login($email, $password);

if ($result['success']) {
    $customer = $result['customer'];
    $_SESSION['customer_id'] = $customer['customer_id'];
    $_SESSION['customer_name'] = $customer['customer_name'];
    $_SESSION['customer_email'] = $customer['customer_email'];
    $_SESSION['user_role'] = $customer['user_role'];
    $_SESSION['success'] = 'Logged in successfully.';
    redirect('../index.php');
} else {
    $_SESSION['error'] = $result['error'];
    redirect('../views/login.php');
}
?>