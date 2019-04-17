<?php
include("../settings.php");

$fileh = 'themen.txt';
$filed = 'beschreibung.txt';

$fph = fopen($fileh, 'r');
$fpd = fopen($filed, 'r');
if ($fph) { $zrh = explode("\n", fread($fph, filesize($fileh))); } else { exit(); }
if ($fpd) { $zrd = explode("\n", fread($fpd, filesize($filed))); } else { exit(); }

$maxusers = 10;

$l = count($zrh);
$i = 1;
while ($i <= $l) {
	$t = $zrd[$i-1];
    $h = $zrh[$i-1];
    $new = mysqli_query($db, "INSERT INTO selection (pid, pname, maxusers, description) VALUES ('$i', '$h', '$maxusers', '$t')");
	if ($new) { $i++; } else { exit(); }
}
echo($l);
echo("Erfolgreich!");

?>