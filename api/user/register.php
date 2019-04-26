<?php
require_once('index.php');

new_user($_GET['username'], $_GET['hashedPassword']);
$json_format = "{comming soon}"; # Comming soon

echo($json_format);
?>