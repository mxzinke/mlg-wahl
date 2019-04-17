<?php
include('settings.php'); # Einstellungen importieren

# GETS
$site = $_GET['site'];

if (!$user) { header("Location: index.php?site=login");  } # User Login vorhanden?
if ($permission < 50) { header("Location: index.php?error=6"); } # Zugriffsberechtigung prüfen
?>

<html>
    <head>
        <title><?php echo $webtitle; ?></title>
        <meta charset="utf-8">
        <meta author="Maximilian Zinke">
        <meta website="mxzinke.dev">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="design/admin.css">
        <link rel="icon" type="image/icon" href="design/icons/favicon.ico">
    </head><!-- Head-Setting -->
    <body>
        <div class="navigation">
            <header><h1>Einstellung und Verwaltung</h1></header>
        </div><!-- Navigation -->
        <div class="content">
<?php

# Navigationsfunktion

function subnav($topic) {
    if ($topic == "main") { $title ='<i class="material-icons">assignment</i> Einstellungen zur Einschreibung'; }
    if ($topic == "galerie") { $title ='<i class="material-icons">dashboard</i> Einstellungen zur Galerie'; }
    if ($topic == "dev") { $title ='<i class="material-icons">build</i> Entwickler Einstellungen'; }
    if ($topic == "users") { $title ='<i class="material-icons">face</i> Nutzerübersicht'; }
    if ($topic == "useredit") { $title ='<i class="material-icons">face</i> Nutzer bearbeiten'; }
    if ($topic == "useradd") { $title ='<i class="material-icons">face</i> Nutzer hinzufügen'; }
    if ($topic == "usergroups") { $title ='<i class="material-icons">group</i> Nutzergruppen'; }
    if ($topic == "usergroupadd") { $title ='<i class="material-icons">group</i> Nutzergruppe hinzufügen'; }
    if ($topic == "usergroupedit") { $title ='<i class="material-icons">group</i> Nutzergruppe bearbeiten'; }
    if ($topic == "rights") { $title = '<i class="material-icons">gavel</i> Rechteverwaltung'; }

    $subnav = ' <div class="headline">
                    <h1 id="title">'. $title .'</h1>
                    <ul id="sub-nav">
                        <li> | <a href="admin.php">Allgemeines</a></li>
                        <li> | <a href="admin.php?site=users">Nutzerübersicht</a></li>
                        <li> | <a href="admin.php?site=usergroups">Nutzergruppen</a></li>
                        <li> | <a href="admin.php?site=rights">Rechteverwaltung</a></li>
                        <li> | <a href="admin.php?site=galerie">Galerie</a></li>
                        <li> | <a href="admin.php?site=dev">Entwickler</a></li>
                    </ul>
                </div>';

    return $subnav;
}

# Verschiedene Seiten
if ($site == '' and $permission >= 50) { # Einstellungen zur Einschreibung
    echo(subnav("main"));
    echo('<div class="admin"><p>');
    echo('Freigeben & Sperrung der Einschreibung: ');
    if ($blocked['value'] == 'on') { 

        echo('<a href="actions/setting.php?action=1"><button>Jetzt freigeben</button></a><br><br>');
        if ($timerstart < time() and $timerend < time()) {
            echo('<form id="timer" method="post" action="actions/setting.php?setting=timer">
            <u>Timer festlegen:</u> von <input name="timerstart" value="'. date('d.m.Y G:i', $time) .'" required> bis <input name="timerend" value="'. date('d.m.Y G:i', $time + 24*3600) .'" required> freischalten<br>
            <button type="submit">Timer aktivieren</button>
            </form><br>');
        } else {
            echo('<form id="timer" method="Post" action="actions/setting.php?setting=timer">
            <u>Timer festlegen:</u> von <input name="timerstart" value="'. date('d.m.Y G:i', $timerstart) .'" required> bis <input name="timerend" value="'. date('d.m.Y G:i', $timerend) .'" required> freischalten<br>
            <button type="submit">Timer ändern</button><a href="actions/setting.php?setting=timerdel"><button>Timer deaktivieren</button></a>
            </form>');
        }
        echo("<b>Hinweis:</b> Achten Sie bei dem Timer immer auf das korrekte Format (DD.MM.YY HH:MM)!<br>");
    } else { echo('<a href="actions/setting.php?action=2"><button>Jetzt sperren</button></a><br>'); }

    echo('<br><br><form action="actions/setting.php?setting=wahl_infotext" method="post">
    Informationstext zum aktuellen Einschreiben (BBCode verfügbar):
    <textarea name="infotext">'. $wahl_infotext .'</textarea>
    <button type="submit">Speichern</button>
    </form><br>');

    echo('<form action="actions/setting.php?setting=max_entries" method="post">
    maximal Anzahl an möglichen Einschreibungen:<input name="max" width="25px" value="'. $max_entries .'" required>
    <button type="submit">Speichern</button>
    </form><br>');

    echo('<a href="actions/auswertung.php"><button>Auswertung</button></a><br>');
    echo('</p></div>');
} else if ($permission >= 50 and $site == 'galerie' ) { # Galerie Einstellungen
    echo(subnav("galerie"));
    echo('<div class="admin"><p>');

    if (isset($_POST['galeriepw']) and isset($_GET['edit'])) {
        $galeriepw = $_POST['galeriepw'];
        $update = mysqli_query($db, "UPDATE settings SET value='$galeriepw' WHERE name = 'galerie_password'");
        if ($update) { echo("Erfolgreich geändert!"); }
    }

    $set = mysqli_fetch_array(mysqli_query($db, "SELECT value FROM settings WHERE name = 'galerie_password'"));
    echo('<form id="galerieedit" method="post" action="admin.php?site=galerie&edit=1">
    Gast-Passwort: <input id="galeriepw" name="galeriepw" value="'. $set['value'] .'" required><button type="submit">Speichern</button>
    </form>');
    echo('</p></div>');
} else if ($permission >= 100 and $site == 'dev') { # Entwickler Einstellungen
    echo(subnav("dev"));
    echo('<div class="admin"><p>');
    echo('Freigeben & Sperrung über Entwickler-Modus: ');

    if ($closedmode) { echo('<a href="actions/setting.php?action=1"><button>Jetzt freigeben</button></a><br>'); }
    else { echo('<a href="actions/setting.php?action=3"><button>Jetzt sperren</button></a><br>'); }

    echo('</p></div>');
} else if ($permission >= 80 and $site == 'users') {
    echo(subnav("users"));
    echo('<div class="admin"><p>');
?>

<table>
    <tr>
        <td>User ID</td>
        <td>Nutzername</td>
        <td>Gruppe</td>
        <td></td>
    </tr>
<?php
    $users = mysqli_query($db, "SELECT uid, username, gid FROM users");

    while($user = mysqli_fetch_object($users)) {
        $guid = $user->uid;
        $gusername = $user->username;
        $ggid = $user->gid;
        $groups = mysqli_fetch_array(mysqli_query($db, "SELECT name, permission FROM usergroups WHERE gid = '$ggid'"));
        $group = $groups['name'];
        $gpermission = $groups['permission'];

        if ($permission > $gpermission) {
            $actions = '<td><a href="admin.php?site=useredit&uid='. $guid .'"><i class="material-icons">mode edit</i></a><a href="actions/options.php?act=5&uid='. $guid .'"><i class="material-icons">delete</i></a></td>';
        } else { $actions = '<td></td>'; }

        echo('<tr><td>'. $guid .'</td><td>'. $gusername .'</td><td>'. $group .'</td>'. $actions .'</tr>');
    }
?>

</table>
<?php if ($permission >= 80) { ?><span style="text-align:right;"><a href="admin.php?site=useradd"><i class="material-icons" >add_box</i></a><a href="actions/nutzer.php"><i class="material-icons">library_add</i></a></span>
<?php }
echo('</p></div>');

} else if ($permission >= 80 and $site == 'useradd') {
    $groups = mysqli_query($db, "SELECT gid, name FROM usergroups WHERE permission < '$permission'");
    echo(subnav("useradd"));
    echo('<div class="admin"><p>');
    echo('<form id="useradd" method="post" action="actions/options.php?act=6">
            User ID: <input maxlength="6" name="uid"><br>
            Nutzername*: <input maxlength="32" name="username" required><br>
            Passwort*: <input maxlength="32" type="password" name="password" required><br>
            Klassenstufe*: <input maxlength="3" name="class" required><br>
            Gruppe*: <select name="gid" required>');

    while ($group = mysqli_fetch_object($groups))  {
        echo('<option value="'. $group->gid .'">'. $group->name .'</option>');
    }

    echo('</select><br><button type="submit">Hinzufügen</button></form><br>');
    echo('<br>
    Mit Stern * versehen, sind zwingend notwendig!
    <br><br><a href="admin.php?site=users">Zurück zur Nutzerübersicht</a>');
    echo('</p></div>');
} else if ($permission >= 80 and $site == 'useredit' and isset($_GET['uid'])) {
    $guid = $_GET['uid'];
    $user = mysqli_fetch_object(mysqli_query($db, "SELECT uid, username, class, gid FROM users WHERE uid = '$guid'"));
    $groups = mysqli_query($db, "SELECT gid, name FROM usergroups WHERE permission < '$permission'");

    echo(subnav("useredit"));
    echo('<div class="admin"><p>');
    echo('<form id="useredit" method="post" action="actions/options.php?act=4&uid='. $user->uid .'">
            User ID: <input maxlength="6" name="uid" value="'. $user->uid .'" required><br>
            Nutzername: <input maxlength="32" name="username" value="'. $user->username .'" required><br>
            Neues Passwort: <input maxlength="32" type="newpassword" name="password"> (Leer lassen wenn nicht benötigt!)<br>
            Klassenstufe: <input maxlength="3" name="class" value="'. $user->class .'" required><br>
            Gruppe: <select name="gid" value="'. $user->gid .'" required>');

    while ($group = mysqli_fetch_object($groups))  {
        if ($group->gid == $user->gid) { $select = "selected"; } else { $select = ""; }
        echo('<option value="'. $group->gid .'" '. $select .'>'. $group->name .'</option>');
    }

    echo('</select><br><button type="submit">Speichern</button></form><br>');
    echo('</p></div>');
} else if ($permission >= 80 and $site == 'usergroups') {
    echo(subnav("usergroups"));
    echo('<div class="admin"><p>');
?>

<table>
    <tr>
        <td>Gruppen ID</td>
        <td>Gruppenname</td>
        <td>P-Punkte</td>
        <td></td>
    </tr>

<?php
    $groups = mysqli_query($db, "SELECT gid, name, permission FROM usergroups");
    while($group = mysqli_fetch_object($groups)) {
        if ($permission > $group->permission) {
            $actions = '<td><a href="admin.php?site=usergroupedit&gid='. $group->gid .'"><i class="material-icons">mode edit</i></a><a href="actions/options.php?act=7&gid='. $group->gid .'"><i class="material-icons">delete</i></a></td>';
        } else { $actions = '<td></td>'; }

        echo('<tr><td>'. $group->gid .'</td><td>'. $group->name .'</td><td>'. $group->permission .'</td>'. $actions .'</tr>');
    }
?>

</table>
<?php
if ($permission >= 80) { ?>
    <span style="text-align:right;"><a href="admin.php?site=usergroupadd"><i class="material-icons" >add_box</i></a></span>
<?php } 
echo('</p></div>');

} else if ($permission >= 80 and $site == 'usergroupadd') {
    echo(subnav("usergroupadd"));
    echo('<div class="admin"><p>');
    echo('<form id="usergroupadd" method="post" action="actions/options.php?act=8">
            Gruppen ID: <input maxlength="6" name="gid"><br>
            Gruppenname: <input maxlength="32" name="name" required><br>
            P-Punkte: <input maxlength="6" name="permission" required><br>
            </select><br><button type="submit">Hinzufügen</button></form><br>');

    echo('</p></div>');

} else if ($permission >= 80 and $site == 'usergroupedit' and isset($_GET['gid'])) {
    $ggid = $_GET['gid'];
    $group = mysqli_fetch_object(mysqli_query($db, "SELECT gid, name, permission FROM usergroups WHERE gid = '$ggid'"));

    echo(subnav("usergroupedit"));
    echo('<div class="admin"><p>');
    echo('<form id="useredit" method="post" action="actions/options.php?act=9&gid='. $group->gid .'">
            Gruppen ID: <input maxlength="6" name="gid" value="'. $group->gid .'" required><br>
            Gruppenname: <input maxlength="32" name="name" value="'. $group->name .'" required><br>
            P-Punkte: <input maxlength="32" name="permission" value="'. $group->permission .'" required><br>
            </select><br><button type="submit">Speichern</button></form><br>');
    echo('</p></div>');

} else if ($site == 'rights' and $permission >= 100) {
    echo(subnav("rights"));
    echo('<div class="admin"><p>');
    $rights = mysqli_query($db, "SELECT rname, description, permission FROM rights");
    echo ('<table>');

    while ($right = mysqli_fetch_object($rights)) {
        echo('<tr><form id="'. $right->rname .'" method="post" action="actions/rights.php?act=1&rid='. $right->rid .'">
        <td>'. $right->description .'</td><td><input name="permission" maxlength="6" value="'. $right->permission .'"></td><td><button type="submit">Speichern</button></td>
        </form></tr>');
    }

    echo('</table>');
    echo('</p></div>');
} else { # Index
    echo(subnav("main"));
    echo('<div class="admin"><p>Du hast darauf kein Zugriff. Hier gibts nix zu sehen!</p></div>');
}

?>      
        <a href="/?site=wahl">Zurück zur Einschreibung</a>
        </div><!-- Inhalt -->
    </body><!-- Frontend of the Backend -->
</html>