<?php
require_once __DIR__ . '/../core/core.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login to Your Account</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success'])): ?>
        <p style="color: green;"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <form id="login-form" action="../actions/login_action.php" method="POST">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
    
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</body>
</html>