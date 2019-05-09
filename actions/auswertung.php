<html>
<head>
    <title>Auswertungsübersicht der Einschreibung</title>
    <link rel="stylesheet" type="text/css" href="../design/evaluation.css">
</head>
<body>
<div class="topnav">
    <span>Auswertung</span>
  <a href="#home" onclick="">Home</a>
  <a href="#unfinished" onclick="">Fehlende Schüler</a>
  <a href="#export" onclick="">Export</a>
</div> 

<div id="unfinished">
<?php
/* This file is for generating a general useful output to use data later in real life */
include("../settings.php");

# Welche Schüler noch nicht alle einträge gemacht haben
echo("<h3>Folgende Schüler sind noch nicht (vollständig) eingetragen haben:</h3>");

function unfinishedUsers($database, $min_entries) {
    $ignore_entries = (int) mysqli_fetch_array(mysqli_query($database, "SELECT permission FROM rights WHERE rname='ignore_min_entries'"))['permission'];

    $db_sql = "SELECT users.username, users.class
    FROM users LEFT
    JOIN (
        SELECT users.uid, COUNT(entries.uid) amount
        FROM entries LEFT
        JOIN users ON users.uid=entries.uid
        GROUP BY entries.uid
    ) AS counter ON users.uid=counter.uid
    JOIN usergroups ON usergroups.gid=users.gid AND usergroups.permission<$ignore_entries
    WHERE counter.uid IS NULL OR counter.amount<$min_entries";

    $db_request = mysqli_query($database, $db_sql);
    while($db_result = mysqli_fetch_array($db_request)) {
        $unfinishedUsers[$db_result['class']][] = $db_result['username'];
    }

    return $unfinishedUsers;
}

$data = unfinishedUsers($db, $min_entries);

function findBiggestArray($array) {
    $counter = 0;
    foreach($array as $element) {
        if ($counter < count($element)) { $counter = count($element); }
    }
    return $counter;
}

$max_unfinished = findBiggestArray($data);

if (count($data) > 0) {
    echo('<table class="easyTable"><thead><tr><th>Klassenstufe '. implode('</th><th>Klassenstufe ', array_keys($data)) .'</th></tr></thead><tbody>');
    
    $main_index = 0;
    while($main_index < $max_unfinished) {
        echo('<tr>');
        foreach (array_keys($data) as $index) {
            echo('<td>'. $data[$index][$main_index] .'</td>');
        }
        echo('</tr>');
        $main_index++;
    }
    echo('</tbody></table>');
}
?>
</div>

<div id="export">
<?php
function allClasses($database) {
    $db_request = mysqli_query($database, "SELECT users.username FROM users, entries WHERE CHAR_LENGTH(username)>7 AND users.uid=entries.uid");

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

function allProjects($database) {
    $db_request = mysqli_query($database, "SELECT selection.pname, selection.pid FROM selection");

    while($db_pname = mysqli_fetch_array($db_request)) {
        $entries[] = array($db_pname['pid'], $db_pname['pname']);
        
    }

    return $entries;
}

# Export nach Klassen:
echo('<h3>nach jeweiligen Klassen exportieren:</h3><form class="exportForm" action="export_class.php" method="get"><select name="class">');
foreach(allClasses($db) as $class) {
    echo('<option value="'. $class .'">'. $class .'</option>');
}
echo('</select> <button type="submit">Exportieren</button></form>');

# Export nach Projekten:
echo('<h3>nach jeweiligen Projekt exportieren:</h3><form class="exportForm" action="export_project.php" method="get"><select name="pid">');
foreach(allProjects($db) as $class) {
    echo('<option value="'. $class[0] .'">'. $class[1] .'</option>');
}
echo('</select> <button type="submit">Exportieren</button></form>');

?>
</div>

<div id="home">
<?php
# Themenabfrage
echo("<br><h2>Die Listen zu den Themen:</h2>");

$s = mysqli_query($db, "SELECT pid, pname FROM selection");
if (mysqli_num_rows($s) > 0) {
	foreach($s as $o) {
		$pid = $o['pid'];
		$name = $o['pname'];

		echo('<h3 style="font-size:16px;">'. $name .' (Nr.'. $pid .') <a href="export_project.php?pid='. $pid .'"><img height="16px" src="../design/icons/export-icon.png" alt="Export" /></a><br></h3>');

        $request = mysqli_query($db, "SELECT uid FROM entries WHERE pid = '$pid'");
        
		echo('<table class="easyTable" style="min-width:300px;">');
		echo('<thead><tr><th>Name:</th></tr></thead><tbody>');
		foreach($request as $h) {
			$usid = $h['uid'];
			$j = mysqli_fetch_array(mysqli_query($db, "SELECT username FROM users WHERE uid = '$usid'"));
			$uname = $j['username'];

			echo('<tr><td>'.$uname.'</td></tr>');
		}

		echo('</tbody></table>');
	}
}
?>
</div>