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

$message = "";
$error = "";

// handling user status (enable/disable)
if (isset($_POST['toggle_user'])) {
    $target_user_id = intval($_POST['user_id']);
    $new_status = intval($_POST['new_status']);

    $stmt = $conn->prepare("UPDATE users SET is_active = ? WHERE user_id = ?");
    $stmt->bind_param("ii", $new_status, $target_user_id);
    if ($stmt->execute()) {
        $message = "User status updated successfully!";
    } else {
        $error = "Failed to update user status.";
    }
    $stmt->close();
}

// handling treat actions (add/edit/delete)
if (isset($_POST['add_treat'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['reg_price']);
    $image = trim($_POST['image_url']);
    $category = trim($_POST['category']);

    $stmt = $conn->prepare("INSERT INTO treats (name, description, category, reg_price, image_url) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssds", $name, $description, $category, $price, $image);
    if ($stmt->execute()) {
        $message = "New treat added successfully!";
    } else {
        $error = "Error adding treat: " . $conn->error;
    }
    $stmt->close();
}

if (isset($_POST['delete_treat'])) {
    $treat_id = intval($_POST['treat_id']);
    $stmt = $conn->prepare("DELETE FROM treats WHERE treat_id = ?");
    $stmt->bind_param("i", $treat_id);
    if ($stmt->execute()) {
        $message = "Treat deleted successfully!";
    } else {
        $error = "Failed to delete treat.";
    }
    $stmt->close();
}

if (isset($_POST['update_treat'])) {
    $treat_id = intval($_POST['treat_id']);
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $price = floatval($_POST['reg_price']);

    $stmt = $conn->prepare("UPDATE treats SET name = ?, description = ?, category = ?, reg_price = ? WHERE treat_id = ?");
    $stmt->bind_param("sssdi", $name, $description, $category, $price, $treat_id);
    if ($stmt->execute()) {
        $message = "Treat updated successfully!";
    } else {
        $error = "Failed to update treat.";
    }
    $stmt->close();
}

// getting all treats
$treats = $conn->query("SELECT * FROM treats ORDER BY treat_id DESC");

// getting all users
$users = $conn->query("SELECT user_id, username, email, full_name, is_admin, is_active FROM users ORDER BY user_id DESC");
?>
<!DOCTYPE html>
<html lang="en-ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Carter's Bakery</title>
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
		<a href="help.php">Help</a>
        <a href="profile.php">My Profile</a>
		<a href="logout.php">Logout</a>
        <!-- admin exclusive links -->
		<?php if ($_SESSION['is_admin'] == 1): ?>
                <a href="admin.php" style="color:red" class="active">Admin</a>
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

    <main style="max-width: 1000px; margin: 30px auto; padding: 0 20px;">
        <h2 style="text-align: center; margin-bottom: 20px;">Admin Dashboard</h2>

        <?php if ($message): ?>
            <p style="color: green; font-weight: bold; text-align: center; margin-bottom: 15px;"><?php echo $message; ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p style="color: red; font-weight: bold; text-align: center; margin-bottom: 15px;"><?php echo $error; ?></p>
        <?php endif; ?>

        <!-- adding a new product -->
        <section style="background: white; border: 1px solid lightgrey; border-radius: 8px; padding: 20px; margin-bottom: 30px;">
            <h3>Add New Treat Item</h3>
            <form action="admin.php" method="POST" style="margin-top: 15px; display: grid; gap: 10px;">
                <div>
                    <label><strong>Item Name:</strong></label><br>
                    <input type="text" name="name" style="width: 100%; padding: 8px;" required>
                </div>
				<div>
			        <label><strong>Category:</strong></label><br>
			        <select name="category" style="width: 100%; padding: 8px;" required>
			            <option value="Cake">Cake</option>
			            <option value="Cookies">Cookies</option>
			        </select>
			    </div>
                <div>
                    <label><strong>Description:</strong></label><br>
                    <textarea name="description" rows="2" style="width: 100%; padding: 8px;" required></textarea>
                </div>
                <div style="display: flex; gap: 15px;">
                    <div style="flex: 1;">
                        <label><strong>Price ($):</strong></label><br>
                        <input type="number" step="0.01" name="reg_price" style="width: 100%; padding: 8px;" required>
                    </div>
                    <div style="flex: 1;">
                        <label><strong>Image Filename:</strong></label><br>
                        <input type="text" name="image_url" placeholder="Enter a valid web link to an image that could be used" style="width: 100%; padding: 8px;" required>
                    </div>
                </div>
                <button type="submit" name="add_treat" class="btn" style="margin-top: 10px; width: 200px;">+ Add Product</button>
            </form>
        </section>

        <!-- editing/deleting products -->
        <section style="background: white; border: 1px solid lightgrey; border-radius: 8px; padding: 20px; margin-bottom: 30px;">
            <h3>Manage Products / Menu Catalogue</h3>
            <table style="width: 100%; border-collapse: collapse; margin-top: 15px; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid lightgrey;">
                        <th style="padding: 8px;">ID</th>
                        <th style="padding: 8px;">Name</th>
						<th style="padding: 8px;">Description</th>
                        <th style="padding: 8px;">Category</th>
                        <th style="padding: 8px;">Price ($)</th>
                        <th style="padding: 8px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($treat = $treats->fetch_assoc()): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <form action="admin.php" method="POST">
                            	<input type="hidden" name="treat_id" value="<?php echo $treat['treat_id']; ?>">
                                <td style="padding: 8px;"><?php echo $treat['treat_id']; ?></td>
                                <td style="padding: 8px;"><input type="text" name="name" value="<?php echo htmlspecialchars($treat['name']); ?>" style="padding: 4px; width: 100%;"></td>
                                <td style="padding: 8px;"><input type="text" name="description" value="<?php echo htmlspecialchars($treat['description']); ?>" style="padding: 4px; width: 100%;"></td>
								<td style="padding: 8px;">
								    <select name="category" style="padding: 4px;">
								        <option value="Cake" <?php echo ($treat['category'] === 'Cake') ? 'selected' : ''; ?>>Cake</option>
								        <option value="Cookies" <?php echo ($treat['category'] === 'Cookies') ? 'selected' : ''; ?>>Cookies</option>
								    </select>
								</td>
                                <td style="padding: 8px;"><input type="number" step="0.01" name="reg_price" value="<?php echo $treat['reg_price']; ?>" style="padding: 4px; width: 70px;"></td>
                                <td style="padding: 8px; white-space: nowrap;">
                                    <button type="submit" name="update_treat" class="btn" style="padding: 4px 8px; font-size: 0.85em;">Save</button>
                                    <button type="submit" name="delete_treat" onclick="return confirm('Delete this treat?')" style="background: red; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 0.85em;">Delete</button>
                                </td>
                            </form>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <!-- user account administration -->
        <section style="background: white; border: 1px solid lightgrey; border-radius: 8px; padding: 20px;">
            <h3>User Account Administration</h3>
            <table style="width: 100%; border-collapse: collapse; margin-top: 15px; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid lightgrey;">
                        <th style="padding: 8px;">User ID</th>
                        <th style="padding: 8px;">Username</th>
                        <th style="padding: 8px;">Full Name</th>
                        <th style="padding: 8px;">Email</th>
                        <th style="padding: 8px;">Role</th>
                        <th style="padding: 8px;">Status</th>
                        <th style="padding: 8px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $users->fetch_assoc()): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 8px;"><?php echo $user['user_id']; ?></td>
                            <td style="padding: 8px;"><strong><?php echo htmlspecialchars($user['username']); ?></strong></td>
                            <td style="padding: 8px;"><?php echo htmlspecialchars($user['full_name']); ?></td>
                            <td style="padding: 8px;"><?php echo htmlspecialchars($user['email']); ?></td>
                            <td style="padding: 8px;"><?php echo ($user['is_admin'] == 1) ? 'Admin' : 'Customer'; ?></td>
                            <td style="padding: 8px;">
                                <span style="color: <?php echo ($user['is_active'] == 1) ? 'green' : 'red'; ?>; font-weight: bold;">
                                    <?php echo ($user['is_active'] == 1) ? 'Active' : 'Disabled'; ?>
                                </span>
                            </td>
                            <td style="padding: 8px;">
                                <?php if ($user['user_id'] != $_SESSION['user_id']): ?>
                                    <form action="admin.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                        <input type="hidden" name="new_status" value="<?php echo ($user['is_active'] == 1) ? 0 : 1; ?>">
                                        <button type="submit" name="toggle_user" style="background: <?php echo ($user['is_active'] == 1) ? 'darkorange' : 'green'; ?>;
											color: white; border: none; padding: 4px 10px; border-radius: 4px; cursor: pointer;">
                                            <?php echo ($user['is_active'] == 1) ? 'Disable' : 'Enable'; ?>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <small>(You)</small>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
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