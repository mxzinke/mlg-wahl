<?php

include("../settings.php");

if (isset($_POST['klassenstufe']) and $_GET['action'] == 1) {
    echo('<table border="1" style="border-collapse:collapse;border:1px solid black;font-family: Arial, sans-serif;" id="table"><tr><td style="padding:0 5px;">Nutzername</td><td style="padding:0 5px;">Passwort</td></tr>');

    $class = $_POST['klassenstufe'];

    $vornam = explode(PHP_EOL, $_POST['vornam']);
    $nachnam = explode(PHP_EOL, $_POST['nachnam']);

    $l = count($vornam);
    if ($l == count($nachnam)) {
        $i = 0;
        print_r($vornam);
        while ($i < $l)  {
            $pw = mt_rand(10000000, 99999999);
            $name = substr($vornam[$i], 0, 3) .'_'. substr($nachnam[$i], 0, 3) .'_'. $class;

            $checkup = mysqli_num_rows(mysqli_query($db, "SELECT * FROM users WHERE username = '$name'"));
            if ($checkup == 0) {
                $password = hash('haval256,4', $pw);
                $take = mysqli_query($db, "INSERT INTO users (username, password, class) VALUES ('$name', '$password', '$class')");
                if ($take) {
                    echo('<tr><td style="padding:0 5px;">'. $name .'</td><td style="padding:0 5px;">'. $pw .'</td></tr>');
                    $i++;
                } else {
                    echo("Datenbankfehler!");
                    $i++;
                }
            } else { echo("DATENBANK FEHLER!!! $name : Gleicher Benutzer bereits vorhanden!"); break; }
        }
    } else { echo("Anzahl der Vornamen und Nachname stimmen nicht überein!"); }

    echo('</table><br>');
    echo('<a href="javascript:self.print()">Seite drucken</a><br>');
    echo('<a href="nutzer.php">Neu Nutzer erstellen</a><br>');
    echo('<a href="../admin.php?site=users">Zurück zur Seite</a><br>');
}

# If there was not pushed the action button yet
else {
?>
<form id="nutzers" method="post" action="nutzer.php?action=1">
    Klassenstufe: <input name="klassenstufe" required><br>
    Vorname / Nachname: <br>
    <textarea name="vornam" cols="20" rows="30" required></textarea>
    <textarea name="nachnam" cols="20" rows="30" required></textarea><br>

    <button type="submit">Nutzer erstellen</button>
</form><br>

<a href="../admin.php?site=users">Zurück zur Seite</a><br>
<?php   
}
?>