<?php
require_once 'db.php';
require_once 'theme.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $full_name = trim($_POST['full_name']);
    $password = $_POST['password'];

    // simple hash for password security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password, email, full_name) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $hashed_password, $email, $full_name);

    if ($stmt->execute()) {
        $message = "Registration successful! You can now <a href='login.php'>Login here</a>.";
    } else {
        $message = "Error: Username or email already exists.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en-ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	
    <title>Register - Carter's Bakery</title>

	<!-- icon for the page -->
	<link rel="icon" type="image/x-icon" href="icons/login.png">
	
    <link rel="stylesheet" href="<?php echo $themePath; ?>">
</head>
<body>

    <nav class="nav-bar">
        <a href="index.php">Home</a>
        <a href="menu.php">Menu</a>
        <a href="order.php">Custom Orders</a>
        <a href="about.php">About Us</a>
        <a href="contact.php">Contact</a>
		<a href="cart.php">Cart (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a>
		<a href="help.php">Help</a>
        <a href="login.php">Login</a>

		<!-- admin exclusive links -->
		<?php if ($_SESSION['is_admin'] == 1): ?>
                <a href="admin.php" style="color:red">Admin</a>
				<a href="status.php" style="color:red">Status</a>
        <?php endif; ?>

		<!-- theme switcher -->
		<form action="theme.php" method="POST" style="display: inline-flex; align-items: center; margin-left: 15px; padding: 10px 0;">
	        <label for="theme-select" style="color: white; font-weight: bold; margin-right: 6px;">Theme:</label>
	        <select id="theme-select" name="set_theme" onchange="this.form.submit()" style="padding: 4px 8px; border-radius: 4px; border: none; cursor: pointer;">
	            <option value="style.css" <?php echo ($selectedTheme === 'style.css') ? 'selected' : ''; ?>>Classic</option>
	            <option value="dark.css" <?php echo ($selectedTheme === 'dark.css') ? 'selected' : ''; ?>>Dark</option>
	            <option value="birthday.css" <?php echo ($selectedTheme === 'birthday.css') ? 'selected' : ''; ?>>Birthday</option>
	        </select>
	    </form>
    </nav>

    <main>
        <section class="featured-section" style="max-width: 400px; margin: 30px auto; padding: 20px; background: white; border: 1px solid lightgrey; border-radius: 8px;">
            <h2>Create an Account</h2>
            
            <?php if ($message): ?>
                <p style="color: darkgreen; margin-bottom: 15px;"><?php echo $message; ?></p>
            <?php endif; ?>

            <form action="register.php" method="POST" style="text-align: left;">
                <label><strong>Full Name (make one up):</strong></label><br>
                <input type="text" name="full_name" style="width: 100%; padding: 8px; margin: 6px 0 12px 0;" required><br>

                <label><strong>Email (don't use your actual email please):</strong></label><br>
                <input type="email" name="email" style="width: 100%; padding: 8px; margin: 6px 0 12px 0;" required><br>

                <label><strong>Username:</strong></label><br>
                <input type="text" name="username" style="width: 100%; padding: 8px; margin: 6px 0 12px 0;" required><br>

                <label><strong>Password:</strong></label><br>
                <input type="password" name="password" style="width: 100%; padding: 8px; margin: 6px 0 15px 0;" required><br>

                <button type="submit" class="btn" style="width: 100%;">Register</button>
            </form>
            
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </section>
    </main>

    <!-- page footer -->
    <footer>
        <p>Author: Carter Dyck<br>
        Contact: cd@uwindsor.ca<br>
        Copyright 2026 Carter's Bakery</p>
    </footer>

</body>
</html>