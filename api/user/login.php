<?php
require_once('index.php');

# If API User is make a wrong Request:
if ( basename($_SERVER["SCRIPT_FILENAME"]) == basename(__FILE__) and (!isset($_GET['username']) or !(isset($_GET['hashedPassword']) or isset($_GET['unhashedPassword']))) ) {
    $json_format = json_encode(Array(
        "actions" => Array("login"),
        "values" => Array("username", "hashedPassword", "unhashedPassword"),
        "result" => Array("uid", "session_key")
    ), JSON_FORCE_OBJECT);
} else {
    if (isset($_GET['hashedPassword'])) { $password = $_GET['hashedPassword']; }
    else { $password = do_hash($_GET['unhashedPassword']); }

    echo($password);

    $result = login_method($_GET['username'], $password);
    print_r( $result);
    
    $json_format = json_encode($result, JSON_FORCE_OBJECT);
}

echo($json_format);
?>