<?php
include('../settings.php');

$userid = $_GET['uid'];
$session_key = $_GET['session_key'];

$apiurl = 'http://' . $_SERVER['HTTP_HOST'] . '/api/user/?mode=validate&uid=' . $userid . '&session_key=' . $session_key;

if (ask_api($apiurl) == "true") {
    echo("Logged-in.");

    $_SESSION['uid'] = $userid;
    $_SESSION['session_key'] = $session_key;
} else {
    print_r(ask_api($apiurl));
}

?>