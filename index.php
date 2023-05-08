<?php
require_once 'pages.php';
require_once 'functions.php';

session_start();

unset($_SESSION['vote_id']);

$has_already_voted = $_SESSION['hasAlreadyVoted'] ?? false;
unset($_SESSION['hasAlreadyVoted']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POLL</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="margin-20">
        <?php if (auth_is_logged_in()): ?>
            <?php page_profile($_SESSION['user']) ?>
            <?php if (auth_is_admin()): ?>
                <form action="create_poll_page.php" method="post" style="padding:5px;">
                    <input type="submit" value="Create Poll">
                </form>
            <?php endif ?>
            <form action="query_logout.php" method="post" style="padding: 5px;">
                <input type="hidden" name="origin" value="index.php">
                <input type="submit" value="Logout">
            </form>
        <?php else: ?>
            <form action="auth_login_page.php" method="post" style="padding: 5px;">
                <input type="hidden" name="origin" value="index.php">
                <input type="submit" value="Login">
            </form>
            <form action="auth_register_page.php" method="post" style="padding: 5px;">
                <input type="hidden" name="origin" value="index.php">
                <input type="submit" value="Register">
            </form>
        <?php endif ?>
        <h1>2022 FIFA World Cup Audience Voting</h1>
        <p>Please vote the audience award.</p>
        <hr>
        <div>
            <h2>Recent Polls</h2>
            <?php if ($has_already_voted): ?>
                <h3 style="color: red">You have already voted for that poll.</h3>
            <?php endif ?>
            <?php recent_poll_list() ?>
        </div>
        <hr>
        <div>
            <h2>Past Polls</h2>
            <?php past_poll_list() ?>
        </div>
    </div>
</body>
</html>