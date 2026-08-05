<?php
// start session to access saved cart items
session_start();

// connect to database to fetch treat details
require_once 'db.php';

$pageTitle = "Shopping Cart";
$pageDesc = "View items in your shopping cart and check out.";

// initialize cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// handle clearing the cart
if (isset($_POST['action']) && $_POST['action'] === 'clear') {
    $_SESSION['cart'] = array();
    header("Location: cart.php");
    exit();
}

// handle adding an item from menu.php or order.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // handling custom orders from order.php
    if (isset($_POST['custom_price'])) {
        $item_name = "Custom Order";
        $option_label = htmlspecialchars($_POST['option']);
        
        // include their custom message if provided
        if (!empty($_POST['custom_text'])) {
            $option_label .= " - Note: " . htmlspecialchars($_POST['custom_text']);
        }

        $final_price = floatval($_POST['custom_price']);

        $_SESSION['cart'][] = array(
            'id' => 0,
            'name' => $item_name,
            'option' => $option_label,
            'price' => $final_price
        );

        header("Location: cart.php");
        exit();
    }

    // handling standard menu items from menu.php
    if (isset($_POST['treat_id'])) {
        $treat_id = intval($_POST['treat_id']);
        $selected_option = isset($_POST['option']) ? $_POST['option'] : 'standard';

        // query treat details from database
        $sql = "SELECT name, reg_price FROM treats WHERE treat_id = " . $treat_id;
        $result = $conn->query($sql);

        if ($result && $row = $result->fetch_assoc()) {
            $item_name = $row['name'];
            $base_price = floatval($row['reg_price']);
            $extra_cost = 0.00;
            $option_label = "";

            // calculate option extras based on selection
            if ($selected_option === '8-inch') {
                $extra_cost = 10.00;
                $option_label = "8 inch";
            } elseif ($selected_option === '6-inch') {
                $option_label = "6 inch";
            } elseif ($selected_option === '18-pack') {
                $extra_cost = 5.00;
                $option_label = "1.5 dozen (18 cookies)";
            } elseif ($selected_option === '12-pack') {
                $option_label = "1 dozen (12 cookies)";
            } else {
                $option_label = "Standard";
            }

            $final_price = $base_price + $extra_cost;

            // add item to cart array
            $_SESSION['cart'][] = array(
                'id' => $treat_id,
                'name' => $item_name,
                'option' => $option_label,
                'price' => $final_price
            );
        }

        // redirect to prevent form re-submission on refresh
        header("Location: cart.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en-ca">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Carter Dyck">
    <meta name="description" content="<?php echo $pageDesc; ?>">
    <meta name="keywords" content="bakery, shopping cart, checkout, order desserts">
    <!-- meta tag for scaling -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?php echo $pageTitle; ?> - Carter's Bakery</title>

	<!-- icon for the page -->
	<link rel="icon" type="image/x-icon" href="icons/cart.png">

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
        <a href="contact.php">Contact</a>
        <a href="cart.php" class="active">Cart (<?php echo count($_SESSION['cart']); ?>)</a>
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
            <h1>Your Shopping Cart</h1>
            
            <?php if (empty($_SESSION['cart'])): ?>
                <p style="margin: 20px 0;">Your cart is currently empty!</p>
                <a href="menu.php" class="btn">Browse Menu</a>
            <?php else: ?>
                <table style="width: 100%; max-width: 600px; margin: 20px auto; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid darkslategrey;">
                            <th style="padding: 10px;">Item</th>
                            <th style="padding: 10px;">Option</th>
                            <th style="padding: 10px;">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0.00;
                        foreach ($_SESSION['cart'] as $item): 
                            $total += $item['price'];
                        ?>
                            <tr style="border-bottom: 1px solid lightgrey;">
                                <td style="padding: 10px;"><?php echo htmlspecialchars($item['name']); ?></td>
                                <td style="padding: 10px;"><?php echo htmlspecialchars($item['option']); ?></td>
                                <td style="padding: 10px;">$<?php echo number_format($item['price'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" style="padding: 10px; text-align: right;"><strong>Total:</strong></td>
                            <td style="padding: 10px;"><strong>$<?php echo number_format($total, 2); ?></strong></td>
                        </tr>
                    </tfoot>
                </table>

                <div style="margin-top: 20px; display: flex; gap: 10px; justify-content: center;">
                    <!-- clear cart button -->
                    <form action="cart.php" method="POST">
                        <input type="hidden" name="action" value="clear">
                        <button type="submit" class="btn" style="background-color: dimgrey;">Clear Cart</button>
                    </form>

                    <!-- checkout placeholder button -->
                    <button type="button" class="btn" onclick="alert('You cannot actually buy anything here, I don\'t want your credit card information')">Proceed to Checkout</button>
                </div>
            <?php endif; ?>
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