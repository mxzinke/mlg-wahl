<?php
/* This file is for generating a general useful output to use data later in real life */
include("../settings.php");

# Prüfen ob Eintragung noch am laufen
if (!$spring) {
    echo("Bitte zuerst die Eintragung schließen, bevor Auswertung möglich ist!");
    exit();
}

/*
echo("Bitte Jahrgänge, welche zu betrachten sind, auswählen!");
# Mögliche Jahrgänge
$klassen = [];
while ($c = mysqli_fetch_object(mysqli_query($db, "SELECT class FROM users"))) {
    $cl = $c->class;
    $baum = false;
    foreach ($klassen as $k) {
        if $cl = $k { $baum = true; break(); } # Wenn die Klassenstufe schon in der Array vorhanden
    }

    if (!$baum) { $klassen[] = $k; } # Neue Klassenstufe zu Array hinzufügen
}

# Ausgabe der Auswahl von Jahrgängen
foreach ($klassen as $kl) {
    echo('<input '); # irgendeine Auswahl <---
}

*/


$klassenstufen = [5, 6, 7, 8, 9]; # Klassenstufen die Berücksichtigt werden sollen.
$a_mind = 1; # Anzahl der mind. Einträge

foreach ($klassenstufen as $klasse) {    
    $users = mysqli_query($db, "SELECT uid FROM users WHERE class = '$klasse'");

    echo("<br>Personen die sich nicht eingetragen haben aus Jahrgang $klasse:<br>");
    while ($u = mysqli_fetch_object($users)) {
        $uid = $u->uid;

        $req = mysqli_num_rows(mysqli_query($db, "SELECT pid FROM entries WHERE uid = '$uid'"));
        if ($req < $a_mind) {
            $n = mysqli_fetch_array(mysqli_query($db, "SELECT username, gid FROM users WHERE uid = '$uid'"));
            $gid = $n['gid'];

            $req_gid = mysqli_fetch_array(mysqli_query($db, "SELECT permission FROM usergroups WHERE gid = '$gid'"));
            if ($req_gid['permission'] < 50) {
                echo($n['username'] .' (Anzahl der Einträge: '. $req .')<br>');
            }
        }
    }
}


function allClasses($database) {
    $db_request = mysqli_query($database, "SELECT username FROM users WHERE CHAR_LENGTH(username)>7");

    while($db_username = mysqli_fetch_array($db_request)) {
        $thisClass = preg_split('/(\X{8})/', $db_username['username'])[1];

        if (array_search($thisClass, $classes) == false) {
            $classes[] = $thisClass;
        }
    }

    sort($classes);
    $classes = array_unique($classes);
    return $classes;
}

# Export nach Klassen:
echo('<br><br><h3>nach Klassen Exportieren:</h3><form method="GET" action="export_class.php"><select id="class">');
foreach(allClasses($db) as $class) {
    echo('<option value="'. $class .'">'. $class .'</option>');
}
echo('</select> <button type="submit">Exportieren</button></form>');

# Themenabfrage
echo("<br><h3>Die Listen zu den Themen:</h3>");

$s = mysqli_query($db, "SELECT pid, pname FROM selection");
if (mysqli_num_rows($s) > 0) {
	foreach($s as $o) {
		$pid = $o['pid'];
		$name = $o['pname'];

		echo('<h2 style="font-size:22px;">'. $name .' (Nr.'. $pid .') <a href="export_project.php?pid='. $pid .'"><img height="20px" src="../design/icons/export-icon.png" alt="Export" /></a><br></h2>');

        $request = mysqli_query($db, "SELECT uid FROM entries WHERE pid = '$pid'");
        
		echo('<table style="border-collapse: collapse;border:1px solid black;min-width:300px;">');
		echo('<tr style="border-collapse: collapse;border:1px solid black;"><td>Name:</td></tr>');
		foreach($request as $h) {
			$usid = $h['uid'];
			$j = mysqli_fetch_array(mysqli_query($db, "SELECT username FROM users WHERE uid = '$usid'"));
			$uname = $j['username'];

			echo('<tr style="border-collapse: collapse;border:1px solid black;"><td>'.$uname.'</td></tr>');
		}

		echo('</table>');
	}
}

?>