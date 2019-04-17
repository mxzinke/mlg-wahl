<?php
include("../settings.php");

$uid = $_SESSION['userc'];
$pid = $_GET['pid'];
$eid = $_GET['eid'];
if (isset($uid) and isset($pid)) {
	if (!isset($eid)) {
		if ($spring) { header("Location: ../index.php?site=info&pid=$pid&error=66"); exit(); } // Nicht freigegeben
		
		$now = mysqli_num_rows(mysqli_query($db, "SELECT eid FROM entries WHERE uid = '$uid'"));
		if ($max_entries <= $now) { header("Location: ../index.php?site=info&pid=$pid&error=99"); exit(); } // Keine weiteren Eintragungen möglich

		$check = mysqli_query($db, "SELECT eid FROM entries WHERE uid = '$uid' AND pid = '$pid'");
		if (mysqli_num_rows($check) == 0)  {
			$create = mysqli_query($db, "INSERT INTO entries (uid, pid) VALUES ('$uid', '$pid')");
			if ($create) { header("Location: ../index.php?site=info&pid=$pid&error=1"); } else { echo("Datenbanken Fehler oder Interner Systemfehler!"); }
		} else { header("Location: ../index.php?site=info&pid=$pid&error=403"); } // Bereits eingetragen
	} else {
		$recheck = mysqli_fetch_array(mysqli_query($db, "SELECT uid FROM entries WHERE eid = '$eid'"));
		$uuid = $recheck['uid'];
		if ($uuid != $uid and $permission < 50) { header("Location: ../index.php?site=info&pid=$pid&error=6"); } // Keine Rechte zum Fremdlöschen
		else {
			$delete = mysqli_query($db, "DELETE FROM entries WHERE eid = '$eid'"); // Löschen
			if ($delete) { header("Location: ../index.php?site=info&pid=$pid&error=2"); }
			else { header("Location: ../index.php?site=info&pid=$pid&error=402"); } // Noch garnicht eingetragen
		}
	}
} else {
	echo("Fehler: Entweder nicht eingeloggt, oder interner Fehler! Bitte versuch es später noch einmal!");
}

?>