<?php
// start session for user state tracking
session_start();

$pageTitle = "Home";
$pageDesc = "Welcome to Carter's Bakery. Order delicious cakes and cookies.";
?>
<!DOCTYPE html>
<html lang="en-ca">
<head>
	<meta charset="UTF-8">
    <meta name="author" content="Carter Dyck">
    <meta name="description" content="<?php echo $pageDesc; ?>">
    <meta name="keywords" content="bakery, desserts, cakes, cookies, order online, kingsville bakery">
	
    <!-- meta tag for scaling -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?php echo $pageTitle; ?> - Carter's Bakery</title>

	<!-- icon for the page -->
	<link rel="icon" type="image/x-icon" href="icons/home.png">

    <?php require_once 'theme.php'; ?>
	<!-- dynamic stylesheet from css folder -->
	<link rel="stylesheet" href="<?php echo $themePath; ?>">

    <!-- external javascript file -->
    <script src="main.js" defer></script>
</head>
<body>

    <!-- top navigation bar -->
    <nav class="nav-bar">
        <a href="index.php" class="active">Home</a>
        <a href="menu.php">Menu</a>
        <a href="order.php">Custom Orders</a>
        <a href="about.php">About Us</a>
        <a href="contact.php">Contact</a>
        <a href="cart.php">Cart (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a>
		<a href="help.php">Help</a>

		<!-- dynamic login/profile links based on session -->
	    <?php if (isset($_SESSION['user_id'])): ?>
	        <a href="profile.php">My Profile</a>
	        <a href="logout.php">Logout</a>
	    <?php else: ?>
	        <a href="login.php">Login</a>
	    <?php endif; ?>

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

    <!-- hero/main banner section -->
    <header class="hero">
        <h1>Welcome to Carter's Bakery</h1>
        <p>Freshly baked cakes and cookies waiting for you to eat them.</p>
        <a href="menu.php" class="btn">Browse Our Menu</a>
    </header>

    <!-- main body content -->
    <main>
        <section class="featured-section">
            <h2>Popular Treats</h2>
            <p>Check out some of our customer favorites, always baked fresh!</p>
            
            <!-- placeholder cards for treats -->
            <div class="treat-grid">
                <div class="treat-card">
                    <h3>Custom Cakes</h3>
                    <p>Great for birthdays, weddings, and special events.</p>
                </div>
                <div class="treat-card">
                    <h3>Gourmet Cookies</h3>
                    <p>A wide range of delicious classic and original cookie flavours.</p>
                </div>
            </div>
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