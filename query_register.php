<?php
session_start();
require_once 'functions.php';

$form_data = (object)[
    'uname' => trim($_POST['uname'] ?? ''),
    'pword1' => trim($_POST['pword1'] ?? ''),
    'pword2' => trim($_POST['pword2'] ?? ''),
    'email' => trim($_POST['email'] ?? '')
];

$errors = [];

if(strlen($form_data->uname) < 5){
    $errors[] = 'uname_short';
}else if(!regex_username($form_data->uname)){
    $errors[] = 'uname_complex';
}else if(user_exists($form_data->uname)){
    $errors[] = 'uname_exists';
}

if (strlen($form_data->email) <5) $errors[] = 'email_short';
else if (!regex_email($form_data->email)) $errors[] = 'email_complex';
else if (email_exists($form_data->email)) $errors[] = 'email_exists';

if($form_data->pword1 != $form_data->pword2){
    $errors[] = 'pword_nomatch';
}else if($form_data->pword1 < 8){
    $errors[] = 'pword_short';
}else if(!regex_password($form_data->pword1)){
    $errors[] = 'pword_complex';
}


if(count($errors) > 0){
    $_SESSION['errors'] = $errors;
    $_SESSION['origin'] = $_POST['origin'];
    $_SESSION['kept_data'] = $form_data;
    redirect('auth_register_page.php');
}
auth_register_user($form_data);
$_SESSION['hasRegistered'] = true; 
redirect('auth_register_page.php');