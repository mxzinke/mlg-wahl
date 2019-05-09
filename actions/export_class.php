<?php
include("../settings.php");
include('export.php');

if (isset($_GET['class'])) {
    $class = $_GET['class'];
} else {
    echo("Wrong Classname!");
    //exit();
    $class = "4a";
}

$plain_sql = "SELECT users.username, selection.pname
            FROM users, selection, entries
            WHERE users.uid=entries.uid AND entries.pid=selection.pid AND users.username LIKE '%_$class'";
$db_request = mysqli_query($db, $plain_sql);

while($db_result = mysqli_fetch_array($db_request)) {
    $data[] = array("Name" => $db_result['username'], "Projekt" => $db_result['pname']);
}

dataExport($data, $class);



?>