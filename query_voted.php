<?php
require_once 'functions.php';
session_start();

if(!auth_is_logged_in()) redirect('auth_login_page.php');

$user = $_SESSION['user'];

$form_data = (object) [
    'vote_id' => $_POST['voted_id'] ?? '',
    'choosen' => $_POST['opt'] ?? [],
    'isUniqueAnswer' => $_POST['isUniqueAnswer'] ?? '',
    'options' => array_column(get_poll_by_id($_POST['voted_id'])->options, 'value')
];

$saved = true;

if(empty($form_data->choosen)||!array_in_array($form_data->choosen, $form_data->options)) {
    $saved = false;
} else {
    $active_polls = get_active_poll();
    foreach ($active_polls as $poll) {
        if ($poll->id != $form_data->vote_id) continue;
        
        $poll->voteCount += sizeof($form_data->choosen);
        foreach ($poll->options as $option) {
            foreach ($form_data->choosen as $votedOption) {
                if ($option->value == $votedOption)
                    $option->count += 1;
                $option->percent = round(($option->count/$poll->voteCount)*100);
            }
        }
        $poll->votedBy[] = $user->uname;
        break;
    }
    save_active_poll($active_polls);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voted</title>
</head>
<body>
    <?php if ($saved): ?>
        <h1>Your vote has been successfully recorded. Thank you for voting.</h1>
    <?php else: ?>
        <h1> Unfortunately, there was an error occurring with your vote data. </h1>
    <?php endif ?>
    <a href="index.php">Click here to go back to Menu</a>
</body>
</html>
