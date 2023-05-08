<?php
require_once 'functions.php';
session_start();

if(auth_is_logged_in()) redirect('index.php');

// checks the validity of the data
$form_data = (object)[
    'uname' => trim($_POST['uname'] ?? ''),
    'pword' => trim($_POST['pword'] ?? ''),
    'origin' => trim($_POST['origin']) ?? 'index.php'
];

if(!auth_password_verify($form_data->uname, $form_data->pword)){
    $_SESSION['errors'][] = 'login_error';
    redirect('auth_login_page.php');
}

// logs the user in
$user = get_user_by_username($form_data->uname);
$_SESSION['user'] = (object) [
    'id' => $user->id,
    'uname' => $user->uname,
    'role' => $user->role
];

$vote_id = $_SESSION['vote_id'] ?? '';

if ($vote_id != '') {
    redirect('vote_page.php');
}

redirect($form_data->origin);