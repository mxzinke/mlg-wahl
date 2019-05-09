<?php
include("../settings.php");
include('export.php');

if (isset($_GET['class'])) {
    $class = $_GET['class'];
} else {
    exit();
}


$plain_sql = "SELECT users.username, selection.pname
            FROM users, selection, entries
            WHERE users.uid=entries.uid AND entries.pid=selection.pid AND CHAR_LENGTH(username)>7";
$db_request = mysqli_query($db, $plain_sql);

if (mysqli_num_rows($db_request > 0)) {
    while($db_result = mysqli_fetch_array($db_request)) {

    }
} else {
    print("Error 404: There were no users found.");
}


?>