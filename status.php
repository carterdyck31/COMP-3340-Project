<?php
require_once 'theme.php';
require_once 'db.php';

// making sure only admins can get here
if (!isset($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
    echo "<script>
            alert('Access Denied: You do not have administrator permissions to view this page.');
            window.location.href = 'profile.php';
          </script>";
    exit();
}

// checking DB connection
$db_status = ($conn && !$conn->connect_error) ? "Online" : "Offline";

// checking database table records
$treat_count = 0;
if ($db_status === "Online") {
    $res = $conn->query("SELECT COUNT(*) AS total FROM treats");
    if ($res) { $treat_count = $res->fetch_assoc()['total']; }
}

// checking main files
$files_to_check = ['index.php', 'menu.php', 'order.php', 'cart.php', 'login.php', 'register.php', 'admin.php', 'db.php'];
$missing_files = [];
foreach ($files_to_check as $file) {
    if (!file_exists($file)) { $missing_files[] = $file; }
}
$files_status = empty($missing_files) ? "Online" : "Warning (Missing: " . implode(', ', $missing_files) . ")";

// overall system status
$overall_status = ($db_status === "Online" && empty($missing_files)) ? "All Systems Operational" : "System Issues Detected";
?>
<!DOCTYPE html>
<html lang="en-ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Status - Carter's Bakery</title>
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
        <a href="profile.php">My Profile</a>
		<a href="logout.php">Logout</a>
        
		<!-- admin exclusive links -->
		<?php if ($_SESSION['is_admin'] == 1): ?>
                <a href="admin.php" style="color:red">Admin</a>
				<a href="status.php" style="color:red" class="active">Status</a>
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

    <main style="max-width: 600px; margin: 40px auto; padding: 20px; background: white; border: 1px solid lightgrey; border-radius: 8px;">
        <h2>Website Monitoring & Status</h2>
        
        <p style="font-weight: bold; font-size: 1.1em; color: <?php echo ($overall_status === 'All Systems Operational') ? 'green' : 'red'; ?>;">
            Status: <?php echo $overall_status; ?>
        </p>

        <hr style="margin: 15px 0;">

        <ul style="list-style: none; padding: 0; line-height: 2em;">
            <li><strong>Database:</strong> <span style="color: <?php echo ($db_status === 'Online') ? 'green' : 'red'; ?>;"><?php echo $db_status; ?></span></li>
            <li><strong>Catalogue Items (Treats):</strong> <?php echo $treat_count; ?> records online</li>
            <li><strong>Core Files & Services:</strong> <span style="color: <?php echo empty($missing_files) ? 'green' : 'red'; ?>;"><?php echo $files_status; ?></span></li>
            <li><strong>PHP Session Engine:</strong> <span style="color: green;">Online</span></li>
        </ul>
    </main>

    <!-- page footer -->
    <footer>
        <p>Author: Carter Dyck<br>
        Contact: cd@uwindsor.ca<br>
        Copyright 2026 Carter's Bakery</p>
    </footer>

</body>
</html>