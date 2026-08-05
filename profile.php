<?php
require_once 'db.php';
require_once 'theme.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en-ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	
    <title>My Profile - Carter's Bakery</title>

	<!-- icon for the page -->
	<link rel="icon" type="image/x-icon" href="icons/profile.png">
	
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
        <a href="profile.php" class="active">My Profile</a>
        <a href="logout.php">Logout</a>

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
        <section class="featured-section" style="max-width: 500px; margin: 30px auto; padding: 25px; background: white; border: 1px solid lightgrey; border-radius: 8px; text-align: left;">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</h2>
            <p style="margin-top: 10px;"><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <p><strong>Account Type:</strong> <?php echo ($_SESSION['is_admin'] == 1) ? 'Administrator' : 'Customer'; ?></p>

            <hr style="margin: 20px 0; border: 0; border-top: 1px solid lightgrey;">

            <h3>Account Features</h3>
            <ul>
                <li><a href="order.php">Place a Custom Bakery Order</a></li>
                <li><a href="cart.php">View Shopping Cart</a></li>
                <?php if ($_SESSION['is_admin'] == 1): ?>
                    <li><a href="admin.php" style="color: red; font-weight: bold;">Admin Dashboard</a></li>
					<li><a href="status.php" style="color: red; font-weight: bold;">Website Status</a></li>
                <?php endif; ?>
            </ul>

            <br>
            <a href="logout.php" class="btn" style="background: grey; text-decoration: none;">Logout</a>
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