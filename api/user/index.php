<?php
/* This is the API of the Project for accessing directly from the page (via JS/TS) or by outside */

# Settings for Database-Connection
include('../../settings.php');

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

/* Different Modes: login | register | validate | logout */
if (isset($_GET['mode']) == true) {
    $mode = $_GET['mode'];

    if ($mode == "login") {
        if (isset($_GET['hashedPassword'])) { $password = $_GET['hashedPassword']; }
        else { $password = do_hash($_GET['unhashedPassword']); }

        $result = login_method($_GET['username'], $password);
        $json_format = json_encode($result, JSON_FORCE_OBJECT);
        
    } else if ($mode == "register") {
        new_user($_GET['username'], $_GET['hashedPassword']);
        $json_format = "{comming soon}"; # Comming soon
    } else if ($mode == "validate") {
        $json_format = json_encode(validate_session($_GET['uid'], $_GET['session_key']), JSON_FORCE_OBJECT);
    } else if ($mode == "logout") {
        $result = logout_method($_GET['uid'], $_GET['session_key']);
        $json_format = json_encode($result, JSON_FORCE_OBJECT);
    }

    echo($json_format);
}


?>