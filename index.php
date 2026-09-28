<?php
require_once __DIR__ . '/core/core.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - E-commerce Store</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        nav { display: flex; justify-content: space-between; align-items: center; background: #f4f4f4; padding: 10px 20px; }
        .nav-links a { margin-left: 15px; text-decoration: none; color: #333; font-weight: bold; }
        .nav-links a:hover { color: #007bff; }
        .error { color: red; background: #ffe6e6; padding: 10px; border-radius: 5px; }
        .success { color: green; background: #e6ffe6; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>

    <nav>
        <h2>My E-commerce Store</h2>
        <div class="nav-links">
            <?php if (is_logged_in()): ?>
                <span>Welcome, <strong><?= htmlspecialchars($_SESSION['customer_name']) ?></strong></span>
                
                <?php if (is_admin()): ?>
                    <a href="views/admin/brand.php">Admin Panel</a>
                <?php endif; ?>
                
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="views/register.php">Register</a>
                <a href="views/login.php">Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <hr>

    <!-- Display Success Message -->
    <?php if (isset($_SESSION['success'])): ?>
        <p class="success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <!-- Display Error Message -->
    <?php if (isset($_SESSION['error'])): ?>
        <p class="error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <h3>Welcome to the Home Page!</h3>
    <p>This is the entry point of the application.</p>

</body>
</html>