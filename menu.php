<?php
// start session for user state tracking
session_start();

// include database connection
require_once 'db.php';

$pageTitle = "Menu";
$pageDesc = "Browse our full selection of delicious bakery treats, cakes, and pastries.";

// fetch all treats from the database
$sql = "SELECT * FROM treats";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en-ca">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Carter Dyck">
    <meta name="description" content="<?php echo $pageDesc; ?>">
    <meta name="keywords" content="bakery menu, cakes, pastries, cookies, desserts">
    <!-- meta tag for scaling -->
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
        <a href="menu.php" class="active">Menu</a>
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

    <!-- main content container -->
    <main>
        <section class="featured-section">
            <h1>Our Bakery Menu</h1>
            <p>Explore our fresh handcrafted treats made daily!</p>
            
            <!-- treat grid dynamically generated from database -->
            <div class="treat-grid">
                <?php
                if ($result && $result->num_rows > 0) {
                    // loop through each row in treats table
                    while($row = $result->fetch_assoc()) {
                        ?>
                        <div class="treat-card">
						    <!-- display treat image if image_url is set -->
						    <?php if (!empty($row['image_url'])): ?>
						        <img src="<?php echo htmlspecialchars($row['image_url']); ?>" 
						             alt="<?php echo htmlspecialchars($row['name']); ?>" 
						             style="width: 100%; max-height: 180px; object-fit: cover; border-radius: 5px; margin-bottom: 10px;">
						    <?php endif; ?>
						
						    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
						    <p><?php echo htmlspecialchars($row['description']); ?></p>
						    <p><strong>Price:</strong> $<?php echo number_format($row['reg_price'], 2); ?></p>
						    
						    <!-- dynamic options selection form based on category -->
						    <form action="cart.php" method="POST" style="margin-top: 10px;">
						        <input type="hidden" name="treat_id" value="<?php echo $row['treat_id']; ?>">
						        
						        <label for="option-<?php echo $row['treat_id']; ?>">Option:</label>
						        <select id="option-<?php echo $row['treat_id']; ?>" name="option">
						            <?php if (strtolower($row['category']) === 'cake' || strtolower($row['category']) === 'cakes'): ?>
						                <!-- cake options -->
						                <option value="6-inch">6 inch (Standard)</option>
						                <option value="8-inch">8 inch (+ $10.00)</option>
						            <?php else: ?>
						                <!-- cookie options -->
						                <option value="12-pack">12 cookies</option>
						                <option value="18-pack">18 cookies (+ $5.00)</option>
						            <?php endif; ?>
						        </select>
						        <br><br>
						        <button type="submit" class="btn">Add to Cart</button>
						    </form>
						</div>
                        <?php
                    }
                } else {
                    echo "<p>No treats found in the menu yet!</p>";
                }
                ?>
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