<?php
require_once 'functions.php';
session_start();
if (!auth_is_logged_in()) redirect('auth_login_page.php');
if (!auth_is_admin()) redirect('index.php');
$form_data = (object) [
    'question' => $_POST['question'] ?? '',
    'options' => $_POST['options'] ?? [],
    'deadline' => $_POST['deadline'] ?? '',
    'isUniqueAnswer' => $_POST['isUniqueAnswer'] ?? '' == 'on'? true: false
];

$saved = true;

if ($form_data->question == '' || sizeof($form_data->options) < 2 || $form_data->deadline == '') {
    $saved = false;
} else {
    $options = [];
    foreach ($form_data->options as $option) {
        $optionObject = (object) [
            'value' => $option,
            'count' => 0,
            'percent' => 0
        ];
        $options[] = $optionObject;
    }

    date_default_timezone_set('Europe/Budapest');
    $date = date('Y-m-d H:i');
    
    $new_poll = (object) [
        'id' => uniqid(),
        'question' => $form_data->question,
        'options' => $options,
        'isUniqueAnswer' => $form_data->isUniqueAnswer,
        'createdAt' => $date,
        'deadline' => str_replace('T', ' ',$form_data->deadline),
        'votedBy' => [],
        'voteCount' => 0
    ];
    
    // store the poll
    $active_poll = get_active_poll();
    $active_poll[] = $new_poll;
    save_active_poll($active_poll);
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php if (!$saved): ?>
        <p>Please fill the form correctly.</p>
    <?php else: ?>
        <p>Poll Created</p>
    <?php endif ?>
    <a href="index.php">Click here to go back to Menu</a>
</body>
</html>