<?php
include("../settings.php");

if ($_GET['act'] == '1' and $user) {
	# POSTs
	$oldpassword = md5($_POST['oldpw']);
	$newpassword = md5($_POST['newpw']);
	$retrypassword = md5($_POST['rnewpw']);

	if ($passc == $oldpassword) {
		if ($newpassword == $retrypassword) {
			$update = mysqli_query($db, "UPDATE users SET password = '$newpassword' WHERE uid = '$userc'");
			if ($update) {
                $_SESSION['passc'] = $newpassword;
                header("Location: ../index.php?site=own&error=1");
            } else { echo("Datenbank Fehler l13, own"); }
		} else {
			header("Location: ../index.php?site=own&error=101"); # Beiden neuen Passwörter stimmen nicht überein
		}
	} else {
		header("Location: ../index.php?site=own&error=100"); # Fehler, altes Passwort falsch
	}
}

?>