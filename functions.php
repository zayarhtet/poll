<?php

function get_active_poll() {
    $data = json_read("database/poll_data.json")->active_poll;
    usort($data, 'date_cmp');
    return $data;
}

function get_past_poll() {
    $data = json_read("database/poll_data.json")->past_poll;
    usort($data, 'date_cmp');
    return $data;
}

function has_user_voted($username, $vote_id) {
    $active_poll = get_active_poll();
    foreach ($active_poll as $poll) {
        if ($poll->id == $vote_id) {
            return in_array($username, $poll->votedBy);
        }
    }
    return false;
}

function save_active_poll($active_poll) {
    $data = json_read("database/poll_data.json");
    $data->active_poll = $active_poll;
    json_write("database/poll_data.json",$data);
}

/**
 * Read a JSON file and converts the content to and Array or an Object.
 * @param string $filename The name of the JSON file with the extension.
 * @param bool|null $associative [optional] When TRUE, returned objects will be converted into associative arrays.
 * @return (Array|Object) Depending on the content of the JSON file, an Array or an Object of the data that was inside.
 */
function json_read($filename, $associative = false){
    return json_decode(file_get_contents($filename), $associative);
}

/**
 * Write an Array or Object into a JSON file. It OVERWRITES the content of the file.
 * @param string $filename The name of the JSON file with the extension.
 * @param (Array|Object) $data The data to be converted to string.
 */
function json_write($filename, $data){
    file_put_contents($filename,json_encode($data, JSON_PRETTY_PRINT));
}

/**
 * Tells whether the user a user is already logged in or not using a session.
 * @return bool Is anyone logged in?
 */
function auth_is_logged_in(){
    $user = $_SESSION['user'] ?? '';
    return $user != '';
}

function get_poll_by_id($id) {
    $data = get_active_poll();
    foreach ($data as $poll) {
        if ($poll->id == $id) return $poll;
    }
}

/**
 * Redirects you to a page and stops the originating script.
 * @param string $page The page you want to redirect the user to.
 */
function redirect($page){
    header('Location: ' . $page);
    die;
}

/**
 * Adds a user to the database with hashed password an unique ID.
 * @param Object $user The user to add.
 * @return Number The ID of the newly registered user.
 */
function auth_register_user($user){
    $users = json_read('database/users.json');
    $new_id = uniqid();
    $user->id = $new_id;
    $user->pword = password_hash($user->pword1, PASSWORD_DEFAULT);
    $user->pword1 = $user->pword2 = '';
    $user->role = "user";
    $users->$new_id = $user;
    json_write('database/users.json', $users);

    return $new_id;
}

function get_user_by_username($uname){
    $users = json_read('database/users.json');
    foreach($users as $user){
        if($user->uname == $uname) return $user;
    }
    return null;
}

function get_user_by_id($id){
    $users = json_read('database/users.json');
    foreach($users as $user){
        if($user->id == $id) return $user;
    }
    return null;
}
/**
 * 
 */
function auth_password_verify($uname, $pword){
    $user = get_user_by_username($uname);
    // echo '<p>'.var_dump($user).'</p>';
    if($user == null) return false;
    return password_verify($pword, $user->pword);
}

/**
 * Checks if a given string matches the username criteria of only a-z A-Z 0-Z characters.
 * @param string $uname The username to check.
 */
function regex_username($uname){
    return preg_match('/^[a-zA-Z0-9]+$/',$uname);
}

/**
 * Checks if a given string matches the email criteria of only a-z A-Z 0-Z characters.
 * @param string $email The email to check.
 */
function regex_email($email){
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}


/**
 * Checks if a given string matches the password criteria of atleast one a-z and A-Z and 0-9 and (# or @) character.
 * @param string $pword The password to check.
 */
function regex_password($pword){
    return preg_match('/[a-z]/',$pword) &&
           preg_match('/[A-Z]/',$pword) &&
           preg_match('/[0-9]/',$pword) &&
           preg_match('/[#@]/',$pword);
}

/**
 *  Checks if a user already exists in the database with the given name.
 */
function user_exists($uname){
    $users = json_read('database/users.json');

    // Linear search for the user
    $found = false;
    foreach($users as $user){
        if(strtolower($uname) == strtolower($user->uname)){
            $found = true;
            break;
        }
    }
    return $found;
}

/**
 *  Checks if a user already exists in the database with the given name.
 */
function email_exists($email){
    $users = json_read('database/users.json');

    // Linear search for the user
    $found = false;
    foreach($users as $user){
        if(strtolower($email) == strtolower($user->email)){
            $found = true;
            break;
        }
    }
    return $found;
}

/**
 * 
 */
function user_id_exists($user_id){
    $users = json_read('data/users.json');
    return isset($users->$user_id);
}

/**
 * Checks if all the elements in an array are inside another array.
 * @param Array $needles The elements to check.
 * @param Array $haystack The base of the elements to check in.
 */
function array_in_array($needles, $haystack){
    foreach($needles as $needle){
        if(!in_array($needle, $haystack)) return false;
    }
    return true;
}

function auth_is_admin() {
    $user = $_SESSION['user'] ?? '';
    return $user != '' && $user->role == 'admin';
}

function date_cmp($pollOne, $pollTwo) {
    $date1 = strtotime($pollOne->createdAt);
    $date2 = strtotime($pollTwo->createdAt);
    if ($date1 > $date2) return -1;
    elseif ($date1 < $date2) return 1;
    return 0;
}