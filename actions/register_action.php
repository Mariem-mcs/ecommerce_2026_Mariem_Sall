<?php
// Turn on error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Use __DIR__ for reliable paths
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/register.php');
}

// Sanitize inputs
$name = trim(strip_tags($_POST['name']));
$email = trim(strip_tags($_POST['email']));
$password = $_POST['password'];
$country = trim(strip_tags($_POST['country']));
$city = trim(strip_tags($_POST['city']));
$contact = trim(strip_tags($_POST['contact']));

// Validate
if (empty($name) || empty($email) || empty($password) || empty($country) || empty($city) || empty($contact)) {
    $_SESSION['error'] = 'All fields are required.';
    redirect('../views/register.php');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Invalid email format.';
    redirect('../views/register.php');
}
if (strlen($password) < 8) {
    $_SESSION['error'] = 'Password must be at least 8 characters.';
    redirect('../views/register.php');
}

// Instantiate the Controller (Capital C)
$controller = new CustomerController();

$result = $controller->register([
    'name' => $name,
    'email' => $email,
    'password' => $password,
    'country' => $country,
    'city' => $city,
    'contact' => $contact
]);

if ($result['success']) {
    // Auto-login after registration
    $loginResult = $controller->login($email, $password);
    
    if ($loginResult['success']) {
        $customer = $loginResult['customer'];
        $_SESSION['customer_id'] = $customer['customer_id'];
        $_SESSION['customer_name'] = $customer['customer_name'];
        $_SESSION['customer_email'] = $customer['customer_email'];
        $_SESSION['user_role'] = $customer['user_role'];
        $_SESSION['success'] = 'Registration successful! Welcome.';
        
        // Redirect to home page
        redirect('../index.php');
    } else {
        // If auto-login fails, just go to login page
        $_SESSION['success'] = 'Registration successful! Please log in.';
        redirect('../views/login.php');
    }
} else {
    $_SESSION['error'] = $result['error'];
    redirect('../views/register.php');
}
?>