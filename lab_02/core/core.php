<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
date_default_timezone_set('Africa/Accra');

// Secret code for creating admin accounts:
define('ADMIN_SECRET_CODE', 'ShoppnAdmin2026!');
require_once __DIR__ . '/db_class.php';

// The helper functions:
function get_ip() {
    return $_SERVER['REMOTE_ADDR'];
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function is_logged_in() {
    return isset($_SESSION['customer_id']);
}

function is_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1;
}

function require_login() {
    if (!is_logged_in()) {
        $_SESSION['error'] = 'You must log in first.';
        redirect('../views/login.php');
    }
}

function require_admin() {
    if (!is_admin()) {
        $_SESSION['error'] = 'Access denied.';
        redirect('../index.php');
    }
}
?>