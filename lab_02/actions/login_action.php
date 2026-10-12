<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/login.php');
}

// Sanitizing the inputs:
$email    = trim(strip_tags($_POST['email']));
$password = $_POST['password'];

if (empty($email) || empty($password)) {
    $_SESSION['error'] = 'Email and password are required.';
    redirect('../views/login.php');
}

// Instantiating the Controller:
$controller = new CustomerController();
$result = $controller->login($email, $password);

if ($result['success']) {
    $customer = $result['customer'];
    $_SESSION['customer_id']    = $customer['customer_id'];
    $_SESSION['customer_name']  = $customer['customer_name'];
    $_SESSION['customer_email'] = $customer['customer_email'];
    $_SESSION['user_role']      = $customer['user_role'];
    $_SESSION['success']        = 'Logged in successfully.';

    // Redirecting based on role, 1 = Admin, 2 = Customer or user:
    if ($customer['user_role'] == 1) {
        redirect('../views/admin/brand.php');   // Admin dashboard
    } else {
        redirect('../index.php');               // Customer home page
    }
} else {
    $_SESSION['error'] = $result['error'];
    redirect('../views/login.php');
}
?>