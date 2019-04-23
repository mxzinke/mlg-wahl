<?php
# @class This Class is for User specific actions and secruity fixes
include('../settings.php');
include('session.php')

class User {
    public $userName;
    public $hashedPassword;
    public $userId;
    public $userGroup;

    function __construct($newUserName, $newPassword, $registerNewUser = false) {
        $userName = $newUserName;
        $hashedPassword = $newPassword;

        if (!$registerNewUser and isset($_SESSION[])) {
            try { this.login_method(); }
            catch(Exception $e) {
                # For debugging: echo 'Exception: ', $e->getMessage(), '\n';
                header("Location: ../index.php?site=login&error=5"); # UserMessage => "Falscher Login"
            }
        }
        
        
        
    }

    # generating a new session at browser
    function new_session() {
        session_start(); # starting new session at the browser
        
        # generating a new session key:
        $sessionKey = mt_rand(10000000, 99999999);
        $sessionDbKey = md5($sessionKey);

        $updateSession = mysqli_query($db, "UPDATE users SET session_key = '$sessionDbKey'");


        new Session()
    }

    function validate_session() {
        $databaseRequest = mysqli_query($db, "SELECT ")
    }

    # wanting the hashed password, as browser-response
    function login_method() {
        $databaseAnswer = mysqli_query($db, "SELECT uid, gid FROM users WHERE username = '$userName' AND password = '$hashedPassword'");

        if (mysqli_num_rows($databaseAnswer) > 0) { $fetchedResult = mysqli_fetch_array($databaseAnwser); }
        else { throw new Exception('User Login failed.'); } # If there no users with this username 
    
        
    }
}

?>