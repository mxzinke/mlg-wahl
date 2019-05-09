<?php
include('../settings.php');
include('export.php');

if (isset($_GET['pid'])) {
    $pid = $_GET['pid'];
} else {
    exit();
}
$data = array();
$pname = mysqli_fetch_array(mysqli_query($db, "SELECT pname FROM selection WHERE pid='$pid'"))['pname'];
$db_request = mysqli_query($db, "SELECT users.username FROM entries, users WHERE entries.pid='$pid' AND entries.uid=users.uid");

while($db_result = mysqli_fetch_array($db_request)) {
    $data[] = array("Username" => $db_result['username']);
}

dataExport($data, $pname);

?>