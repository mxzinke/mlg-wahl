<?php
/* This file is for checking login 
 * Please rewrite this code @important */

include('../settings.php');

session_start();
$mode = $_GET['mode'];

if ($mode == 1) { # Normal Userlogin Mode
    $username = $_POST['username'];
    $password = hash('haval256,4', $_POST['password']);

    $user = mysqli_query($db, "SELECT uid, gid, password FROM users WHERE username = '$username'");
    if (mysqli_num_rows($user) == 1 ) {
        $u_results = mysqli_fetch_array($user);
        if ($u_results['password'] == $password) {
            $uid = $u_results['uid'];
            $gid = $u_results['gid'];

            # Gruppenabfrage
            $groups = mysqli_fetch_array(mysqli_query($db, "SELECT permission FROM usergroups WHERE gid = '$gid'"));
            $permission = groups['permission'];

            $_SESSION['userc'] = $uid;
            $_SESSION['passc'] = $password;
            if ($permission >= 100) { setcookie("devcookie", "enabled", time() + 3600 * 24 * 3); } # Für Entwickler der Entwicklercookie

            header("Location: ../index.php");
        } else {
            header("Location: ../index.php?site=login&error=5"); # Falscher Login
        }
    } else {
        header("Location: ../index.php?site=login&error=8"); # Nutzer Exisiert nicht
    }
} else {
    echo "Mode FEHLER!";
}
?>