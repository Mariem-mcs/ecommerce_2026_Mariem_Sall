<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name      = trim(strip_tags($_POST['name']));
    $email     = trim(strip_tags($_POST['email']));
    $password  = $_POST['password'];
    $country   = trim(strip_tags($_POST['country']));
    $city      = trim(strip_tags($_POST['city']));
    $contact   = trim(strip_tags($_POST['contact']));
    $secret    = trim($_POST['secret_code']);

    if ($secret !== ADMIN_SECRET_CODE) {
        $error = 'Invalid secret code. Access denied.';
    } elseif (empty($name) || empty($email) || empty($password)) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } else {
        $controller = new CustomerController();
        $result = $controller->register([
            'name'      => $name,
            'email'     => $email,
            'password'  => $password,
            'country'   => $country,
            'city'      => $city,
            'contact'   => $contact,
            'user_role' => 1    
        ]);
        if ($result['success']) {
            $success = 'Admin account created! You can now <a href="login.php">log in</a>.';
        } else {
            $error = $result['error'];
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Registration</title>
</head>
<body>
    <h2>Admin Registration (Restricted)</h2>

    <?php if ($error): ?><p style="color:red;"><?= $error ?></p><?php endif; ?>
    <?php if ($success): ?><p style="color:green;"><?= $success ?></p><?php endif; ?>

    <form method="POST">
        <label>Full Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Country:</label><br>
        <input type="text" name="country" required><br><br>

        <label>City:</label><br>
        <input type="text" name="city" required><br><br>

        <label>Contact:</label><br>
        <input type="text" name="contact" required><br><br>

        <label><strong>Secret Admin Code:</strong></label><br>
        <input type="password" name="secret_code" required><br><br>

        <button type="submit">Create Admin</button>
    </form>
</body>
</html>