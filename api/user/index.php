<?php
/* This is the API of the Project for accessing directly from the page (via JS/TS) or by outside */

# Settings for Database-Connection
require_once('../../settings.php');

function new_session($uid) {      
    # generating a new session key:
    $sessionKey = mt_rand(10000000, 99999999);
    $sessionDbKey = md5($sessionKey);

    $updateSession = mysqli_query($db, "UPDATE users SET session_key = '$sessionDbKey' WHERE uid = '$uid'");
    return $sessionKey;
}

function validate_session($uid, $session_key) {
    $databaseRequest = mysqli_query($db, "SELECT uid FROM users WHERE uid = '$uid' AND session_key = '$session_key'");
    if (mysqli_num_rows($databaseRequest) > 0) { return true; } else { return false; }
}

function login_method($userName, $hashedPassword) {
    $databaseAnswer = mysqli_query($db, "SELECT uid FROM users WHERE username = '$userName' AND password = '$hashedPassword'");

    if (mysqli_num_rows($databaseAnswer) > 0) {
        $fetchedResult = mysqli_fetch_array($databaseAnwser);
        $sessionKey = new_session($fetchedResult['uid']);
        
        return Array(
            'uid' => $fetchedResult['uid'],
            'session_key' => $sessionKey
        );
    } else { return Array(); }
}

function logout_method($uid, $sessionKey) {
    if (validate_session($uid, $sessionKey) == true) {
        new_session($uid);
        return true;
    } else {
        return false;
    }
}

function new_user($userName, $hashedPassword) {
    #comming soon
}

if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    $json_format = json_encode(Array(
        "actions" => Array("login", "logout", "register", "validate"),
        "values" => Array("uid", "username", "hashedPassword", "unhashedPassword", "session_key")
    ), JSON_FORCE_OBJECT);
    
    echo($json_format);
}
?>