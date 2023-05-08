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

$has_registered = $_SESSION['hasRegistered'] ?? false;
unset($_SESSION['hasRegistered']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <?php page_errors($errors) ?>
    <!-- <?php page_login($origin) ?> -->
    <?php if ($has_registered): ?>
        <p>The new account has been registered. You can now login.</p>
    <?php endif ?>
    <?php page_register($origin, $kept_data) ?>
    <p>Login <a href="auth_login_page.php">here</a></p>
</body>
</html>