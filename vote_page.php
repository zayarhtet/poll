<?php
session_start();
require_once "functions.php";

$vote_id = $_POST['vote_id'] ?? $_SESSION['vote_id'] ?? '';
$poll = get_poll_by_id($vote_id);
if (!$poll) redirect('index.php');

if(!auth_is_logged_in()) {
    $_SESSION['vote_id'] = $vote_id;
    redirect('auth_login_page.php');
}

unset($_SESSION['vote_id']);

// check if the user has already voted or not
$user = $_SESSION['user'];
if (has_user_voted($user->uname, $vote_id)) {
    $_SESSION['hasAlreadyVoted'] = true;
    redirect('index.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="margin-20" style="display:flex; justify-content:center;">
        <div>
            <h3><?= $poll->question ?></h3>
            <form action="query_voted.php" method="post">
                <p><?= $poll->isUniqueAnswer? "Please choose one of them.":"Please choose."?></p>
                <input type="hidden" name="voted_id" value="<?= $poll->id?>">
                <input type="hidden" name="isUniqueAnswer" value="<?= $poll->isUniqueAnswer ?>">
                <div class="margin-20">
                    <?php if ($poll->isUniqueAnswer): ?>                    
                        <?php foreach ($poll->options as $opt):?>
                            <input type="radio" id="<?=$opt->value?>" name="opt[]" value="<?= $opt->value?>">
                            <label for="<?=$opt->value?>"><?=$opt->value?></label><br>
                        <?php endforeach ?>
                    <?php else: ?>
                        <?php foreach ($poll->options as $opt): ?>
                            <input type="checkbox" name="opt[]" value="<?= $opt->value?>"> <?= $opt->value?> <br> 
                        <?php endforeach ?>
                    <?php endif ?>
                </div>
                <input type="submit" value="Submit">
            </form>
        </div>
    </div>
</body>
</html>
