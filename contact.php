<?php
// start session to keep navigation and cart synced
session_start();

$pageTitle = "Contact Us";
$pageDesc = "Get in touch with Carter's Bakery for inquiries, store hours, and location details.";

$messageSent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $messageSent = true;
}
?>
<!DOCTYPE html>
<html lang="en-ca">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Carter Dyck">
    <meta name="description" content="<?php echo $pageDesc; ?>">
    <meta name="keywords" content="contact carter's bakery, bakery address, windsor bakery phone, bakery hours">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?php echo $pageTitle; ?> - Carter's Bakery</title>

	<!-- icon for the page -->
	<link rel="icon" type="image/x-icon" href="icons/contact.png">

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
        <a href="about.php">About Us</a>
        <a href="contact.php" class="active">Contact</a>
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
            <h1>Contact Us</h1>
            <p>Have questions about an order or dietary options? Send us a message below!</p>

            <?php if ($messageSent): ?>
                <div style="background: lightgreen; border: 1px solid green; padding: 15px; max-width: 500px; margin: 20px auto; border-radius: 5px;">
                    <p style="color: #137333; margin: 0;"><strong>Thank you!</strong> Your message has been sent. We'll get back to you within 2 business days.</p>
                </div>
            <?php endif; ?>

            <div style="display: flex; flex-wrap: wrap; gap: 30px; justify-content: center; max-width: 800px; margin: 20px auto; text-align: left;">
                
                <!-- contact Form -->
                <form action="contact.php" method="POST" style="flex: 1; min-width: 280px; background: white; padding: 20px; border: 1px solid lightgrey; border-radius: 8px;">
                    <label for="contact-name"><strong>Name:</strong></label><br>
                    <input type="text" id="contact-name" name="name" style="width: 100%; padding: 8px; margin: 6px 0 12px 0;" required><br>

                    <label for="contact-email"><strong>Email:</strong></label><br>
                    <input type="email" id="contact-email" name="email" style="width: 100%; padding: 8px; margin: 6px 0 12px 0;" required><br>

                    <label for="contact-msg"><strong>Message:</strong></label><br>
                    <textarea id="contact-msg" name="message" rows="4" style="width: 100%; padding: 8px; margin: 6px 0 15px 0;"required></textarea><br>

                    <button type="submit" class="btn" style="width: 100%;">Send Message</button>
                </form>

                <!-- bakery info box -->
                <div style="flex: 1; min-width: 250px; background: snow; padding: 20px; border: 1px solid lightgrey; border-radius: 8px;">
                    <h3>Store Information</h3>
                    <p><strong>Address:</strong> 123 Somewhere Ave, Kingsville, ON</p>
                    <p><strong>Phone:</strong> (012) 345-6789</p>
                    <p><strong>Email:</strong> cd@uwindsor.ca (not a real email address)</p>

                    <h4 style="margin-top: 20px;">Bakery Hours</h4>
                    <p style="margin: 4px 0;">Monday – Saturday: 8:00 AM – 6:00 PM</p>
                    <p style="margin: 4px 0;">Sunday: Closed</p>
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