<?php
# Datenbank Login Daten
$dbhost = "localhost";      # Hostname
$dbuser = "mlg-wahl";       # Nutzer
$dbpass = "derfischmax";    # Passwort
$dbtable = "mlg-wahl";      # Tabelle

################################################################################
# ###### Do not touch following code! ###### Folgendes nicht verändern! ###### #
################################################################################

error_reporting(E_ERROR/* | E_WARNING*/); # Error Modus

if(session_status() != PHP_SESSION_ACTIVE) { session_start(); } # Session start

# Session'n'Cookie check
$userc = $_SESSION['userc'];
$passc = $_SESSION['passc'];
$devcookie = $_COOKIE['devcookie'];
$time = time();

# Database connection
$db = mysqli_connect($dbhost, $dbuser, $dbpass, $dbtable);
if (!$db) { exit('Verbindungsfehler!'.mysqli_connect_error()); }
mysqli_set_charset($db, 'utf8');

# loading database settings
$infos = mysqli_query($db, "SELECT value, name FROM settings");

while ($info = mysqli_fetch_array($infos)) {
    $value = $info['value'];
    $name = $info['name'];
    if ($name == 'wahl_infotext') { $wahl_infotext = $value; }
    if ($name == 'website_title') { $webtitle = $value; }
    if ($name == 'max_entries') { $max_entries = $value; }
    if ($name == 'timerstart') { $timerstart = $value; }
    if ($name == 'timerend') { $timerend = $value; }
}



# blocked mode

$blocked = mysqli_fetch_array(mysqli_query($db, "SELECT value FROM settings WHERE name = 'state_blocked'"));
if ($blocked['value'] == 'dev') { $closedmode = true; } else { $closedmode = false; }
if ($blocked['value'] == 'on') {
    $spring = true;
    if ($timerstart <= $time and $timerend >= $time) { $spring = false; }
} else { $spring = false; }



# development mode
if ($devcookie == 'enabled') { $closedmode = false; }
if ($_GET['mode'] == "dev") { setcookie("devcookie", "enabled", time() + 3600 * 24 * 3); } # Temporären Modus aktivieren (3 Tage)
if ($closedmode) { echo('<p style="color:red;">Entschuldige, aber an dieser Seite wird gerade bearbeitet! Bitte versuch es zu einem späteren Zeitpunkt noch einmal. - Danke für deine Verständnis. Deine MLG Entwicklungsteam</p>'); exit(); }



if (isset($userc) and isset($passc)) {

    $user = mysqli_query($db, "SELECT username, gid, class, password FROM users WHERE uid = '$userc'");
    $u_results = mysqli_fetch_array($user);

    if (mysqli_num_rows($user) == 1) { # Variablen und Auswertung der Nutzerdatenbanken Informationen
        if ($u_results['password'] == $passc) {
            $username = $u_results['username'];
            $gid = $u_results['gid'];
            $s_class = $u_results['class'];
            $user = true;
        } else {
            $user = false;
            #header("Location: logout.php");
        } # Wenn Nutzer falsches Password eingespeichert hat!
    } else {
        $user = false;
        header("Location: logout.php");
    } # Wenn Account nicht existiert
}

if ($user and isset($gid)) {
    $group = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM usergroups WHERE gid = '$gid'"));
    $groupname = $group['name'];
    $permission = $group['permission'];
}

# Errors
$get_error = $_GET['error'];

if (isset($get_error) and $get_error != "") {

    if ($get_error == "1")      { echo '<p class="error" style="background:green;">Erfolgreich eingetragen!</p>'; }
    if ($get_error == "2")      { echo '<p class="error" style="background:green;">Erfolgreich ausgetragen!</p>'; }
    if ($get_error == "3")      { echo '<p class="error" style="background:green;">Erfolgreich gespeichert!</p>'; }
    if ($get_error == "4")      { echo '<p class="error" style="background:green;">Erfolgreich gelöscht!</p>'; }
    if ($get_error == "5")      { echo '<p class="error" style="background:red;">Falsches Passwort oder Login-Name!</p>'; }
    if ($get_error == "6")      { echo '<p class="error" style="background:red;">Du hast dazu keine Rechte!</p>'; }
    if ($get_error == "7")      { echo '<p class="error" style="background:red;">Gäste Passwort ist falsch!</p>'; }
    if ($get_error == "8")      { echo '<p class="error" style="background:red;">Dieser Nutzer existiert nicht!</p>'; }
    if ($get_error == "9")      { echo '<p class="error" style="background:red;">Permissions sind zu hoch!</p>'; }
    if ($get_error == "10")     { echo '<p class="error" style="background:green;">Erfolgreich in den Entwicklermodus gesetzt!</p>'; }
    if ($get_error == "11")     { echo '<p class="error" style="background:green;">Erfolgreich gesperrt!</p>'; }
    if ($get_error == "12")     { echo '<p class="error" style="background:green;">Erfolgreich freigegeben!</p>'; }
    if ($get_error == "66")     { echo '<p class="error" style="background:red;">Seite noch nicht freigegeben!</p>'; }
    if ($get_error == "99")     { echo'<p class="error" style="background:red;">Keine weitere Eintragung möglich!</p>'; }
    if ($get_error == "100")    { echo'<p class="error" style="background:red;">Bitte überprüfe dein aktuelles Passwort!</p>'; }
    if ($get_error == "101")    { echo'<p class="error" style="background:red;">Die neuen Passwörter stimmen nicht überein!</p>'; }
    if ($get_error == "102")    { echo'<p class="error" style="background:red;">Die Eingabe ist nicht vollständig!</p>'; }
    if ($get_error == "103")    { echo'<p class="error" style="background:red;">Diese Nummer ist bereits vorhanden!</p>'; }
    if ($get_error == "104")    { echo'<p class="error" style="background:red;">Das Passwort ist zu kurz!</p>'; }
    if ($get_error == "105")    { echo'<p class="error" style="background:red;">Falsches Zeitformat!</p>'; }
    if ($get_error == "402")    { echo '<p class="error" style="background:red;">Du bist nicht eingetragen!</p>'; }
    if ($get_error == "403")    { echo '<p class="error" style="background:red;">Du bist bereits eingetragen!</p>'; }
}

function BBCode($bbtext){
  $bbtags = array(
    '[heading1]' => '<h1>','[/heading1]' => '</h1>',
    '[heading2]' => '<h2>','[/heading2]' => '</h2>',
    '[heading3]' => '<h3>','[/heading3]' => '</h3>',
    '[h1]' => '<h1>','[/h1]' => '</h1>',
    '[h2]' => '<h2>','[/h2]' => '</h2>',
    '[h3]' => '<h3>','[/h3]' => '</h3>',

    '[paragraph]' => '<p>','[/paragraph]' => '</p>',
    '[para]' => '<p>','[/para]' => '</p>',
    '[p]' => '<p>','[/p]' => '</p>',
    '[left]' => '<p style="text-align:left;">','[/left]' => '</p>',
    '[right]' => '<p style="text-align:right;">','[/right]' => '</p>',
    '[center]' => '<p style="text-align:center;">','[/center]' => '</p>',
    '[justify]' => '<p style="text-align:justify;">','[/justify]' => '</p>',

    '[bold]' => '<span style="font-weight:bold;">','[/bold]' => '</span>',
    '[italic]' => '<span style="font-weight:bold;">','[/italic]' => '</span>',
    '[underline]' => '<span style="text-decoration:underline;">','[/underline]' => '</span>',
    '[b]' => '<span style="font-weight:bold;">','[/b]' => '</span>',
    '[i]' => '<span style="font-weight:bold;">','[/i]' => '</span>',
    '[u]' => '<span style="text-decoration:underline;">','[/u]' => '</span>',
    '[break]' => '<br>',
    '[br]' => '<br>',
    '[newline]' => '<br>',
    '[nl]' => '<br>',

    '[unordered_list]' => '<ul>','[/unordered_list]' => '</ul>',
    '[list]' => '<ul>','[/list]' => '</ul>',
    '[ul]' => '<ul>','[/ul]' => '</ul>',

    '[ordered_list]' => '<ol>','[/ordered_list]' => '</ol>',
    '[ol]' => '<ol>','[/ol]' => '</ol>',
    '[list_item]' => '<li>','[/list_item]' => '</li>',
    '[li]' => '<li>','[/li]' => '</li>',

    '[*]' => '<li>','[/*]' => '</li>',
    '[code]' => '<code>','[/code]' => '</code>',
    '[preformatted]' => '<pre>','[/preformatted]' => '</pre>',
    '[pre]' => '<pre>','[/pre]' => '</pre>',            
  );

  $bbtext = str_ireplace(array_keys($bbtags), array_values($bbtags), $bbtext);

  $bbextended = array(
    "/\[url](.*?)\[\/url]/i" => "<a href=\"http://$1\" title=\"$1\">$1</a>",
    "/\[url=(.*?)\](.*?)\[\/url\]/i" => "<a href=\"$1\" title=\"$1\">$2</a>",
    "/\[email=(.*?)\](.*?)\[\/email\]/i" => "<a href=\"mailto:$1\">$2</a>",
    "/\[mail=(.*?)\](.*?)\[\/mail\]/i" => "<a href=\"mailto:$1\">$2</a>",
    "/\[img\]([^[]*)\[\/img\]/i" => "<img src=\"$1\" alt=\" \" />",
    "/\[image\]([^[]*)\[\/image\]/i" => "<img src=\"$1\" alt=\" \" />",
    "/\[image_left\]([^[]*)\[\/image_left\]/i" => "<img src=\"$1\" alt=\" \" class=\"img_left\" />",
    "/\[image_right\]([^[]*)\[\/image_right\]/i" => "<img src=\"$1\" alt=\" \" class=\"img_right\" />",
  );

  foreach($bbextended as $match=>$replacement){
    $bbtext = preg_replace($match, $replacement, $bbtext);
  }

  return $bbtext;
}

# Check, ob Erlaubnis
function check_right($rid, $ownpermission) {
    global $db;
    if (ctype_digit(strval($rid))) { 
        $check = mysqli_query($db, "SELECT permission FROM rights WHERE rid = '$rid'");
    } else {
        $check = mysqli_query($db, "SELECT permission FROM rights WHERE rname = '$rid'");
    }

    if (mysqli_num_rows($check) < 1) {
        return false;
    } else {
        $check_array = mysqli_fetch_array($check);
        if ($check_array['permission'] > $ownpermission) {
            return false;
        } else {
            return true;
        }
    }
}

?>