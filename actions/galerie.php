<?php
include('../settings.php');

$passwort = $_POST['password'];

$qrequest = mysqli_query($db, "SELECT value FROM settings WHERE name = 'galerie_password'");
$request = mysqli_fetch_array($qrequest);
$value = $request['value'];

if ($value == $passwort) {
    header("Location: ../index.php?site=galerie&sid=access");
} else {
    header("Location: ../index.php?site=galerie&error=7"); 
}

?>