<?php
require_once('index.php');



if ( basename($_SERVER["SCRIPT_FILENAME"]) == basename(__FILE__) and (!isset($_GET['username']) or !(isset($_GET['hashedPassword']) or isset($_GET['unhashedPassword']))) ) {
    $json_format = json_encode(Array(
        "actions" => Array("login"),
        "values" => Array("username", "hashedPassword", "unhashedPassword"),
        "result" => Array("uid", "session_key")
    ), JSON_FORCE_OBJECT);
} else {
    $json_format = json_encode(validate_session($_GET['uid'], $_GET['session_key']), JSON_FORCE_OBJECT);
}

echo($json_format);
?>
