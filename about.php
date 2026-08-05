<?php
// start session to keep navigation and cart synced
session_start();

$pageTitle = "About Us";
$pageDesc = "Learn more about Carter's Bakery and our passion for cakes and cookies.";
?>
<!DOCTYPE html>
<html lang="en-ca">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Carter Dyck">
    <meta name="description" content="<?php echo $pageDesc; ?>">
    <meta name="keywords" content="Carter's bakery, bakery, cakes, cookies">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?php echo $pageTitle; ?> - Carter's Bakery</title>

	<!-- icon for the page -->
	<link rel="icon" type="image/x-icon" href="icons/info.png">

    <?php require_once 'theme.php'; ?>
	<!-- dynamic stylesheet from css folder -->
	<link rel="stylesheet" href="<?php echo $themePath; ?>">

    <!-- external javascript file -->
    <script src="main.js" defer></script>
</head>
<body>

    <!-- top navigation bar -->
    <nav class="nav-bar">
        <a href="index.php">Home</a>
        <a href="menu.php">Menu</a>
        <a href="order.php">Custom Orders</a>
        <a href="about.php" class="active">About Us</a>
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

    <!-- main content container -->
    <main>
        <section class="featured-section" style="max-width: 700px; margin: 30px auto; text-align: left; padding: 20px;">
            <h1>About Carter's Bakery</h1>
            
            <h2>Our Story & Business Model</h2>
            <p style="margin-bottom: 15px;">
                Founded in Kingsville, Ontario, <strong>Carter's Bakery</strong> is a (not real) bakery dedicated to baking high-quality, fresh cakes and gourmet cookies.
				Our mission is to make tasty stuff, and enable customers to easily browse our menu or order custom desserts directly through our website.
            </p>

            <p style="margin-bottom: 15px;">
                We serve customers celebrating any special occasion like birthdays, weddings, and anniversaries, as well as local businesses seeking catered dessert platters for corporate events.
				By operating on an order-ahead model, we ensure every treat is baked fresh using premium local ingredients.
            </p>

            <h2>Why Choose Us?</h2>
            <ul style="margin-left: 20px;">
                <li><strong>Fresh Ingredients:</strong> Baked daily with zero preservatives.</li>
                <li><strong>Customization:</strong> Tailored sizes, flavors, and designs for every occasion.</li>
                <li><strong>Convenient Ordering:</strong> Real-time custom quote calculator and online checkout.</li>
            </ul>
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