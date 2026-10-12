<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/register.php');
}

$name     = trim(strip_tags($_POST['name']));
$email    = trim(strip_tags($_POST['email']));
$password = $_POST['password'];
$country  = trim(strip_tags($_POST['country']));
$city     = trim(strip_tags($_POST['city']));
$contact  = trim(strip_tags($_POST['contact']));

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

$controller = new CustomerController();

$result = $controller->register([
    'name'      => $name,
    'email'     => $email,
    'password'  => $password,
    'country'   => $country,
    'city'      => $city,
    'contact'   => $contact,
    'user_role' => 2    // ALWAYS a customer. Admins are created separately.
]);

if ($result['success']) {
    $_SESSION['success'] = 'Registration successful! Please log in.';
    redirect('../views/login.php');
} else {
    $_SESSION['error'] = $result['error'];
    redirect('../views/register.php');
}
?>