<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// this handles the switching of themes
if (isset($_POST['set_theme'])) {
    $allowed_themes = array('style.css', 'dark.css', 'birthday.css');
    if (in_array($_POST['set_theme'], $allowed_themes)) {
        $_SESSION['active_theme'] = $_POST['set_theme'];
    }
    
    $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
    header("Location: " . $redirect);
    exit();
}

$selectedTheme = isset($_SESSION['active_theme']) ? $_SESSION['active_theme'] : 'style.css';
$themePath = "css/" . $selectedTheme;
?>