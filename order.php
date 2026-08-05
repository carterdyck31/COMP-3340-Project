<?php
// start session to keep navigation and cart synced
session_start();

$pageTitle = "Custom Orders";
$pageDesc = "Get an instant price quote for custom cakes and custom cookies at Carter's Bakery.";
?>
<!DOCTYPE html>
<html lang="en-ca">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Carter Dyck">
    <meta name="description" content="<?php echo $pageDesc; ?>">
    <meta name="keywords" content="custom cake quote, custom cookies, bakery custom order, carter's bakery">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?php echo $pageTitle; ?> - Carter's Bakery</title>

	<!-- icon for the page -->
	<link rel="icon" type="image/x-icon" href="icons/menu.png">

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
        <a href="order.php" class="active">Custom Orders</a>
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

    <!-- main content container -->
    <main>
        <section class="featured-section">
            <h1>Custom Order Quote Calculator</h1>
            <p>Select your treat type and options below to get an instant quote!</p>

            <form action="cart.php" method="POST" style="max-width: 500px; margin: 20px auto; text-align: left; background: white; padding: 25px; border: 1px solid lightgrey; border-radius: 8px;">
                
                <!-- treat type selection -->
                <label for="treat-type"><strong>Treat Type:</strong></label><br>
                <select id="treat-type" name="treat_type" style="width: 100%; padding: 8px; margin: 8px 0 15px 0;">
                    <option value="cake">Custom Cake</option>
                    <option value="cookies">Custom Cookies</option>
                </select>

                <!-- cake sizes (shown when cake is selected) -->
                <div id="cake-options-group">
                    <label for="cake-size"><strong>Cake Size:</strong></label><br>
                    <select id="cake-size" name="cake_size" style="width: 100%; padding: 8px; margin: 8px 0 15px 0;">
                        <option value="6-inch">6 inch ($30.00)</option>
                        <option value="8-inch">8 inch ($45.00)</option>
                    </select>
                </div>

                <!-- cookie amounts (shown when cookies is selected) -->
                <div id="cookie-options-group" style="display: none;">
                    <label for="cookie-amount"><strong>Quantity:</strong></label><br>
                    <select id="cookie-amount" name="cookie_amount" style="width: 100%; padding: 8px; margin: 8px 0 15px 0;">
                        <option value="1-dozen">1 Dozen (12 cookies) ($20.00)</option>
                        <option value="2-dozen">1.5 Dozen (18 cookies) ($25.00)</option>
                    </select>
                </div>

                <!-- special Instructions Text Area -->
                <label for="custom-text"><strong>Special Instructions:</strong></label><br>
                <textarea id="custom-text" name="custom_text" rows="4" style="width: 100%; padding: 8px; margin: 8px 0 15px 0;" placeholder="Explain what kind of cake or cookies you want (flavors, design, colors, written message, etc.)..." required></textarea>

                <!-- hidden inputs to submit values -->
                <input type="hidden" name="treat_id" value="0">
                <input type="hidden" id="calculated-price-input" name="custom_price" value="30.00">
                <input type="hidden" id="final-option-input" name="option" value="Custom Cake - 6 inch">

                <!-- real time price display -->
                <div style="background: snow; padding: 15px; border-radius: 5px; text-align: center; margin-bottom: 20px;">
                    <h3 style="color: darkslategrey;">Estimated Quote: $<span id="quote-price">30.00</span></h3>
                </div>

                <button type="submit" class="btn" style="width: 100%; text-align: center;">Add Custom Order to Cart</button>
            </form>
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