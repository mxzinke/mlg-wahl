<?php
include('settings.php'); # Einstellungen importieren
?>

<html>
    <head>
        <title><?php echo $webtitle; ?></title>

        <meta charset="utf-8">
        <meta author="Maximilian Zinke">
        <meta website="mxzinke.dev">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="design/main.css">
        <link rel="icon" type="image/icon" href="design/icons/favicon.ico">

        <?php
        # Variablen aus GET
        $get = $_GET['site'];
        $get_sid = $_GET['sid'];
        $get_pid = $_GET['pid'];
        $get_section = $_GET['section'];
        ?>
    </head>
    <body>
        <header>
            <img id="logo" src="design/images/Schullogo.png" alt="Schullogo">
            <ul id="main-nav">
                <?php if (!$user) {
                    echo('<li id="button"><a href="index.php?site=login"><i class="material-icons" id="lock" alt="Login">lock</i>Login</a></li>');
                } else {
                    echo('<li id="button"><a href="actions/logout.php"><i class="material-icons" id="lock" alt="Logout">lock_open</i>Logout</a></li>');
                } 
                if ($user) { echo('<li id="button"><a href="index.php?site=wahl">Einschreibung</a></li>'); } /* Nur für Angemeldete User */
                //echo('<li id="button"><a href="index.php?site=galerie">Galerie</a></li>');
                ?>
            </ul>
        </header>
<?php
if (!isset($get)) {
    if (isset($get_sid)) {
        $sid = $get_sid;
        $headline_request = mysqli_query($db, "SELECT title, content FROM sites WHERE sid = '$sid' LIMIT 1");
        $page = mysqli_fetch_array($headline_request);

        if (mysqli_num_rows($headline_request) == 1) {
            $content = BBCode($page['content']);
            $title = $page['title'];
        } else {
            echo "Interner Datenbank Fehler! in51";
            exit();
        }
    } else {
        $homepage_request = mysqli_query($db, "SELECT sid, title, content FROM sites WHERE is_homepage = '1' LIMIT 1");
        $page = mysqli_fetch_array($homepage_request);

        if (mysqli_num_rows($homepage_request) == 1) {
            $sid = $page['sid'];
            $content = BBCode($page['content']);
            $title = $page['title'];
        } else {
            echo "FEHLER: Keine Homepage gesetzt!";
            exit();
        }
    }
?>

        <div class="content">
            <div class="headline">
                <h1 id="title"><?php echo $title; ?></h1>
                <ul id="subnav">
                <!-- Hier kommt noch die Abfrage nach unterseiten hin! -->
                </ul>
            </div>
            <p><?php echo(BBCode($content)); ?></p>
        </div>

<?php
} else if ($get == 'galerie') {
?>
        <div class="content">
            <div class="headline">
                <h1 id="title">Galerie</h1>
            </div>

<?php
    if ($user or $get_sid == 'access') { #### Galerie
?>
        <!-- Galerie -->
        <div class="admin">
        <?php
        $alleordner = scandir('galerie');

        foreach($alleordner as $ordner) {
            if ($ordner != '.' and $ordner != '..') {
                echo ('<h2>'.$ordner.'</h2>');
                $alledateien = scandir('galerie/'.$ordner);

                foreach($alledateien as $datei) {
                    if ($datei != '.' and $datei != '..') {
                        echo('<a href="galerie/'.$ordner.'/'.$datei.'"><img src="galerie/'.$ordner.'/'.$datei.'" style="max-width:350px;height:auto;"></a>  ');
                    }
                }
            }
        }
        echo('</div>');

    } else {
?>
        <!-- Galerie Zugriff -->    
        <div class="admin">
            <form id="galeriezugriff" method="post" action="actions/galerie.php">
                <h1>Login:</h1>
                Log dich direkt mit deinen Nutzerdaten <a href="index.php?site=login">hier</a> ein, oder gib das Gast-Passwort ein!<br>
                <input name="password" required type="password" placeholder="Gast-Passwort"><br>
                <button type="submit">Zugriff</button>
            </form>
        </div>
<?php
    }
} else if ($get == 'login' and !$user) { #### Login
?>
        <form class="login" method="post" action="actions/login.php?mode=1">
            <h1>Login:</h1>
            <input maxlength="32" name="username" required placeholder="Nutzername"><br>
            <input maxlength="12" name="password" type="password" required placeholder="Passwort"><br>
            <button type="submit">Login</button>
        </form>
<?php
} else if ($get == 'own' and $user) { #### Nutzereinstellungen
    ?>
    <div class="content">
        <div class="headline">
            <h1 id="title">Account - <?php echo($username); ?></h1>
        </div>
        <div class="admin">
            <b>Passwort ändern:</b><br>
            <form id="pwretyp" method="post" action="actions/own.php?act=1">
                Altes Passwort: <input maxlength="12" name="oldpw" type="password" required placeholder="Passwort"><br>
                Neues Passwort: <input maxlength="12" name="newpw" type="password" required placeholder="Passwort"><br>
                Neues Passwort: <input maxlength="12" name="rnewpw" type="password" required placeholder="Passwort Wiederholung"><br>
                <button type="submit">Speichern</button>
            </form>
        </div>
    <?php
} else if ($get == 'edit' and $user and $permission >= 80 and isset($get_pid)) { #### Wahlmöglichkeit Editieren
    $pid = $get_pid;
    $req_pid = mysqli_fetch_array(mysqli_query($db, "SELECT pname, maxusers, description FROM selection WHERE pid = '$pid'"));
    $pname = $req_pid['pname'];
    $maxusers = $req_pid['maxusers'];
    $wahl_beschr = $req_pid['description'];
    ?>
        <div class="content">
            <div class="headline">
                <h1 id="title">Bearbeiten von Auswahl  "<?php echo($pname); ?>" </h1>
            </div>
            <div class="admin">
                <form id="sel_edit" method="post" action="actions/options.php?act=2&pid=<?php echo($pid); ?>">
                    Projekt Nr.: <input maxlength="6" name="pid" required value="<?php echo($pid); ?>"><br>
                    Titel: <input maxlength="64" name="title" required value="<?php echo($pname); ?>"><br>
                    maximale Anzahl: <input maxlength="4" name="maxusers" required value="<?php echo($maxusers); ?>"><br>
                    Beschreibung: <textarea name="description"><?php echo($wahl_beschr); ?></textarea><br>
                    <button type="submit">Speichern</button>
                </form><br>
                <a href="index.php?site=info&pid=<?php echo($pid); ?>">Zurück</a>
            </div>
    <?php
} else if ($get == 'add' and $user and $permission >= 80) { #### Wahlmöglichkeit Editieren
    echo('<div class="content">
    <div class="headline">
            <h1 id="title">Neue Auswahl hinzufügen</h1>
        </div>
    <div class="admin">
    <p>
        <form id="sel_add" method="post" action="actions/options.php?act=3">
            Nr.: <input maxlength="6" name="pid"><br>
            Titel: <input maxlength="64" name="pname" required><br>
            maximale Anzahl: <input maxlength="4" name="maxusers" required><br>
            Beschreibung: <textarea name="description"></textarea><br>
            <button type="submit">Hinzufügen</button>
        </form>
        </p>
    </div></div>');
} else if ($get == 'wahl' and $user) { #### Wahlübersicht
    ?>
            <div class="content">
                <div class="headline">
                    <h1 id="title">Wahlmöglichkeiten:</h1>
                    <ul id="sub-nav">
                        <li>| <a href="index.php?site=own">Account Einstellungen</a></li>
                    </ul>
                </div>
<?php
    echo('<p>'. BBCode($wahl_infotext));
    if ($spring) { echo'<i style="color:darkred;"> Die Seite ist zurzeit gesperrt!</i>'; } else { echo('<i style="color:darkgreen;"> Die Seite ist freigegeben.</i>'); }
    echo('</p>');
?>

                <p>
                    <table>
                        <tr>
                            <td>Nr.</td>
                            <td>Name</td>
                            <td>Plätze frei</td>
                            <td></td>
                        </tr>

<?php
    $wrequest = mysqli_query($db, "SELECT pid, pname, maxusers FROM selection ORDER BY pid ASC");
    while ($wahl = mysqli_fetch_object($wrequest)) {
        $pid = $wahl->pid;
        $maxusers = $wahl->maxusers;
        $aentries = mysqli_query($db, "SELECT pid FROM entries WHERE pid = '$pid'");
        $qentries = mysqli_num_rows($aentries);
        $free = $maxusers - $qentries;

        echo '<tr>';
        echo '<td>'. $pid .'</td>';
        echo '<td><a href="index.php?site=info&pid='. $pid .'">'. $wahl->pname .'</a></td>';
        if ($free > 0) { echo('<td>'. $free .' frei</td>'); } else { echo('<td>Voll</td>'); }
        if ($permission >= 80) { echo '<td><a href="actions/options.php?act=1&pid='.$pid.'"><i class="material-icons">delete</i></a><a href="index.php?site=edit&pid='.$pid.'"><i class="material-icons">mode edit</i></a></td>'; }
        else { echo ("<td></td>"); }
        echo '</tr>';
    }
?>

                    </table>

<?php
    if ($permission >= 80) { echo('<span style="text-align:right;"><a href="/?site=add"><i class="material-icons" >note_add</i></a></span>'); }
?>
                </p>
            </div>
<?php
} else if ($get == "info" and isset($get_pid) and $user) {                              #### Wahlinfo
    $pid_request = mysqli_query($db, "SELECT * FROM selection WHERE pid = '$get_pid'");
    if (mysqli_num_rows($pid_request) == 1) {
        $sel = mysqli_fetch_array($pid_request);

        $aentries = mysqli_query($db, "SELECT uid, eid FROM entries WHERE pid = '$get_pid' ORDER BY ctimestamp");
        $taken = mysqli_num_rows($aentries);

        $sel['description'] = nl2br($sel['description']);
?>
            <div class="content">
<?php
        echo '<div class="headline"><h1 id="title">'. $sel['pname'] .'<a href="index.php?site=edit&pid='.$get_pid.'"><i class="material-icons">mode edit</i></a><a href="actions/options.php?act=1&pid='.$get_pid.'"><i class="material-icons">delete</i></a></h1></div>';
        echo '<p><b>Plätze:</b> '. $taken .'/'. $sel['maxusers'] .'<br>';
        echo '<b>Beschreibung:</b><br>'. $sel['description'] .'<br>';
        if ($taken != 0) {
            echo '<br><b>bereits eingetragene Personen:</b><br><table>';
            $i = 0;

            while($entry = mysqli_fetch_object($aentries)) {
                $uid = $entry->uid;
                $eid = $entry->eid;
                $i++;

                $u = mysqli_fetch_array(mysqli_query($db, "SELECT username FROM users WHERE uid = '$uid' LIMIT 1"));
                $remove = '';
                if ($uid == $userc) { $remove = '<td><a href="actions/newentry.php?pid='. $get_pid .'&eid='. $eid .'"><i class="material-icons">delete</i></a></td>'; }
                else if ($permission >= 50) { $remove = '<td><a href="actions/newentry.php?pid='. $get_pid .'&eid='. $eid .'"><i class="material-icons">delete</i></a></td>'; }
                echo '<tr><td>'. $i .'</td><td>'. $u['username'] .'</td>'. $remove .'</tr>';
            }

            echo '</table>';
        } else { echo '<br><b>Es hat sich noch niemand eingeschrieben.</b><br>'; }
        if ($taken < $sel['maxusers']) { echo '<br><a href="actions/newentry.php?pid='. $get_pid .'"><button>Jetzt einschreiben!</button></a>'; }
        else { echo('<p style="color:red;">Diese Auswahlmöglichkeit ist vollständig vergeben!</p>'); }

        echo '<br><br><a href="index.php?site=wahl">Zurück zur Übersicht</a>';
    } else { echo("Fehler: Dies steht nicht zur Wahl!"); }
} else { header("Location: index.php"); }
?>
        </div>
        <div class="footer">
            <span id="author"><a href="https://www.gymnasium-hartha.de">Martin-Luther-Gymnasium Hartha</a> © 2019 - Created & Designed by <a href="mailto://me@mxzinke.dev">Maximilian Zinke</a>
            <?php if ($permission >= 50) { echo(' | <a href="admin.php">Einstellungen</a>'); } ?></span>
        </div>
    </body>
</html>