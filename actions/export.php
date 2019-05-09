<?php
include('../settings.php');

if (isset($_GET['pid'])) {
    $pid = $_GET['pid'];
} else {
    //exit();
    $pid = '1';
}
$data = array();
$pname = mysqli_fetch_array(mysqli_query($db, "SELECT pname FROM selection WHERE pid='$pid'"))['pname'];
$db_request = mysqli_query($db, "SELECT users.username FROM entries, users WHERE entries.pid='$pid' AND entries.uid=users.uid");

while($db_result = mysqli_fetch_array($db_request)) {
    $data[] = array("Username" => $db_result['username']);
}

function cleanData(&$str){
    // escape tab characters
    $str = preg_replace("/\t/", "\\t", $str);
    // escape new lines
    $str = preg_replace("/\r?\n/", "\\n", $str);
    // convert 't' and 'f' to boolean values
    if($str == 't') $str = 'TRUE';
    if($str == 'f') $str = 'FALSE';
    // force certain number/date formats to be imported as strings
    if(preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) {
      $str = "'$str";
    }
    // escape fields that include double quotes
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
}

// filename for download
$filename = $pname ."_". time() .".xls";

header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Type: application/vnd.ms-excel");

$flag = false;
foreach($data as $row) {
    if(!$flag) {
        // display field/column names as first row
        echo implode("\t", array_keys($row)) . "\r\n";
        $flag = true;
    }
    array_walk($row, __NAMESPACE__ . '\cleanData');
    echo implode("\t", array_values($row)) . "\r\n";
}
exit;

?>