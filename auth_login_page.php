<?php
session_start();
require_once 'functions.php';
require_once 'pages.php';

$errors = $_SESSION['errors'] ?? [];
$_SESSION['errors'] = [];

$origin = $_POST['origin'] ?? $_SESSION['origin'] ?? 'index.php';
unset($_SESSION['origin']);

$kept_data = $_SESSION['kept_data'] ?? null;
$_SESSION['kept_data'] = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <?php page_errors($errors) ?>
    <?php page_login($origin) ?>
    <p>Haven't registered yet? You can register <a href="auth_register_page.php">here</a>.</p>
</body>
</html>