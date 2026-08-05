<?php
require_once 'theme.php';
require_once 'db.php';

// help pages 
$pages = [
    'Admin' => 'help_admin.html',
    'Orders' => 'help_order.html',
    'Account' => 'help_account.html',
    'Theme' => 'help_theme.html',
    'Error' => 'help_error.html'
];

// getting selected page from URL, default to Admin
$current = isset($_GET['page']) && array_key_exists($_GET['page'], $pages) ? $_GET['page'] : 'Admin';
$file_to_load = $pages[$current];
?>
<!DOCTYPE html>
<html lang="en-ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help - Carter's Bakery</title>
    <link rel="stylesheet" href="<?php echo $themePath; ?>">
</head>
<body>

    <!-- top navigation bar -->
    <nav class="nav-bar">
        <a href="index.php">Home</a>
        <a href="menu.php">Menu</a>
        <a href="order.php">Custom Orders</a>
        <a href="about.php">About Us</a>
        <a href="contact.php">Contact</a>
        <a href="cart.php">Cart (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a>
		<a href="help.php" class="active">Help</a>

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

    <main style="display: flex; max-width: 900px; margin: 30px auto; min-height: 400px; background: white; border: 1px solid lightgrey; border-radius: 8px; overflow: hidden;">
        
        <!-- left sidebar -->
        <aside style="width: 220px; background: lightgrey; padding: 20px 0;">
            <h3 style="padding: 0 15px; margin-bottom: 15px;">Help Topics</h3>
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li><a href="help.php?page=Admin" style="display: block; padding: 10px 15px; text-decoration: none; font-weight: <?php echo ($current === 'Admin') ? 'bold' : 'normal'; ?>;">Admin</a></li>
                <li><a href="help.php?page=Orders" style="display: block; padding: 10px 15px; text-decoration: none; font-weight: <?php echo ($current === 'Orders') ? 'bold' : 'normal'; ?>;">Orders</a></li>
                <li><a href="help.php?page=Account" style="display: block; padding: 10px 15px; text-decoration: none; font-weight: <?php echo ($current === 'Account') ? 'bold' : 'normal'; ?>;">Account</a></li>
                <li><a href="help.php?page=Theme" style="display: block; padding: 10px 15px; text-decoration: none; font-weight: <?php echo ($current === 'Theme') ? 'bold' : 'normal'; ?>;">Theme</a></li>
                <li><a href="help.php?page=Error" style="display: block; padding: 10px 15px; text-decoration: none; font-weight: <?php echo ($current === 'Error') ? 'bold' : 'normal'; ?>;">Error</a></li>
            </ul>
        </aside>

        <!-- right content area displaying hte html pages within the page -->
        <section style="flex: 1; padding: 25px;">
            <?php 
                if (file_exists($file_to_load)) {
                    include $file_to_load;
                } else {
                    echo "<h3>Page Not Found</h3><p>The requested help topic is not available.</p>";
                }
            ?>
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