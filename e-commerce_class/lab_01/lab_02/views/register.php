<?php
require_once __DIR__ . '/../core/core.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h2>Create an Account</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <form id="register-form" action="../actions/register_action.php" method="POST">
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

        <label>Contact Number:</label><br>
        <input type="text" name="contact" required><br><br>

        <button type="submit">Register</button>
    </form>
    
    <p>Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>