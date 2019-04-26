<?php
require_once('index.php');

$result = logout_method($_GET['uid'], $_GET['session_key']);
$json_format = json_encode($result, JSON_FORCE_OBJECT);

echo($json_format);
?>