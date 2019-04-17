<?php
include("../settings.php");

$action = $_GET['action'];
$setting = $_GET['setting'];

if (isset($action)) {
    if ($action == 1 and $permission >= 50) {
        $n = mysqli_query($db, "UPDATE settings SET value = 'off' WHERE name = 'state_blocked'");
        if ($n) { header("Location: ../admin.php?error=12"); } else { echo("Datenbankfehler!"); }
    } else if ($action == 2 and $permission >= 50) {
        $n = mysqli_query($db, "UPDATE settings SET value = 'on' WHERE name = 'state_blocked'");
        if ($n) { header("Location: ../admin.php?error=11"); } else { echo("Datenbankfehler!"); }
    } else if ($action == 3 and $permission >= 100) {
        $n = mysqli_query($db, "UPDATE settings SET value = 'dev' WHERE name = 'state_blocked'");
        if ($n) { header("Location: ../admin.php?error=3"); } else { echo("Datenbankfehler!"); }
    } else { header("Location: ../admin.php?error=10"); }
}

if (isset($setting)) {
    if ($setting == "wahl_infotext" and $permission >= 50) {
        $infotext = $_POST['infotext'];
        $as = mysqli_query($db, "UPDATE settings SET value = '$infotext' WHERE name = 'wahl_infotext'");
        if ($as) { header("Location: ../admin.php?error=3"); } else { echo("Datenbankfehler!"); }
    } else if ($setting == "max_entries" and $permission >= 80) {
        $max = $_POST['max'];
        if (ctype_digit($max)) {
            $at = mysqli_query($db, "UPDATE settings SET value = '$max' WHERE name = 'max_entries'");
            if ($at) { header("Location: ../admin.php?error=3"); } else { echo("Datenbankfehler!"); }
        } else { header("Location: ../admin.php?error=102"); }
    } else if ($setting == "timer" and $permission >= 50) {
        $timerstart = $_POST['timerstart'];
        $timerend = $_POST['timerend'];

        $timerstart = strtotime($timerstart);
        $timerend = strtotime($timerend);

        if ($timerend == false or $timerstart == false) { header("Location: ../admin.php?error=105"); exit(); }

        $update1 = mysqli_query($db, "UPDATE settings SET value = '$timerstart' WHERE name = 'timerstart'");
        $update2 = mysqli_query($db, "UPDATE settings SET value = '$timerend' WHERE name = 'timerend'");
        if ($update1 and $update2) { header("Location: ../admin.php?error=3"); } else { echo("Datenbankfehler!"); }
    } else if ($setting == "timerdel" and $permission >= 50) {
        $times = 0;
        $update1 = mysqli_query($db, "UPDATE settings SET value = '$times' WHERE name = 'timerstart'");
        $update2 = mysqli_query($db, "UPDATE settings SET value = '$times' WHERE name = 'timerend'");
        if ($update1 and $update2) { header("Location: ../admin.php?error=3"); } else { echo("Datenbankfehler!"); }
    } else { header("Location: ../admin.php?error=6"); }
}

?>