<?php
include('../settings.php');

$act = $_GET['act'];

# Sel Löschen
if ($act == 1 and isset($_GET['pid']) and $permission >= 80) {
    $pid = $_GET['pid'];
    $delpid = mysqli_query($db, "DELETE FROM selection WHERE pid = '$pid'");
    $delentry = mysqli_query($db, "DELETE FROM entries WHERE pid = '$pid'");
    
    if ($delpid == true and $delentry == true) {
        header("Location: ../index.php?site=wahl&error=4");
    } else { echo("Datenbank FEHLER!"); }
}

# Sel Editieren
else if ($act == 2 and isset($_GET['pid']) and $permission >= 80){
    $oldpid = $_GET['pid'];
    $pid = $_POST['pid'];
    $pname = $_POST['title'];
    $maxusers = $_POST['maxusers'];
    $beschr = $_POST['description'];

    if ($pname != "" and ctype_digit($pid) and ctype_digit($maxusers)) {
        $check = mysqli_num_rows(mysqli_query($db, "SELECT pid WHERE pid = '$pid'"));
        if ($check == 0 or $oldpid == $pid) {
            $edit = mysqli_query($db, "UPDATE selection SET pid='$pid', pname='$pname', maxusers='$maxusers', description='$beschr' WHERE pid='$oldpid'");
            if ($edit) { header("Location: ../index.php?site=info&pid=$pid"); } else { echo("Datenbank FEHLER!"); }
        } else { header("Location: ../index.php?site=edit&$oldpid&error=103"); exit(); }
    } else { header("Location: ../index.php?site=info&pid=$oldpid&error=102"); }
}

# Sel Hinzufügen
else if ($act == 3 and $permission >= 80) {
    $pid = $_POST['pid'];
    $pname = $_POST['pname'];
    $maxusers = $_POST['maxusers'];
    $beschr = $_POST['description'];

    if ($pname != "" and ctype_digit($maxusers)) {
        if (!ctype_digit($pid)) { $add = mysqli_query($db, "INSERT INTO selection (pname, maxusers, description) VALUES ('$pname', '$maxusers', '$beschr')"); }
        else {
            $check = mysqli_num_rows(mysqli_query($db, "SELECT pid FROM selection WHERE pid = '$pid'"));
            if ($check == 0) { $add = mysqli_query($db, "INSERT INTO selection (pid, pname, maxusers, description) VALUES ('$pid', '$pname', '$maxusers', '$beschr')"); }
            else { header("Location: ../index.php?site=add&error=102"); exit(); }
        }

        if ($add) {
            if (!ctype_digit($pid)) { $pid = mysqli_insert_id($db); }
            header("Location: ../index.php?site=info&pid=$pid");
        } else { echo("Datenbank FEHLER!"); }
    } else { header("Location: ../index.php?site=wahl&error=102"); }
}

# User Edit
else if ($act == 4 and $_GET['uid'] and $permission >= 80) {
    $olduid = $_GET['uid'];
    $guid = $_POST['uid'];
    $gusername = $_POST['username'];
    $gpassword = $_POST['password'];
    $gclass = $_POST['class'];
    $ggid = $_POST['gid'];

    # Rechte überprüfen
    $checkup = mysqli_fetch_array(mysqli_query($db, "SELECT permission FROM usergroups LEFT JOIN users ON usergroups.gid = users.gid WHERE users.uid = '$olduid'"));
    if ($permission <= $checkup['permission']) { header("Location: ../admin.php?site=useredit&$olduid&error=6"); exit(); } # Rechte überprüfen

    if ($gusername != "" and ctype_digit($guid) and ctype_digit($gclass)) {
        $check = mysqli_num_rows(mysqli_query($db, "SELECT uid FROM users WHERE uid = '$guid'"));
        if ($olduid == $uid or $check == 0) {
            if ($gpassword == "") {
                $edit = mysqli_query($db, "UPDATE users SET uid='$guid', username='$gusername', class='$gclass', gid='$ggid' WHERE uid='$olduid'");
                if ($edit) { header("Location: ../admin.php?site=users&error=3"); } else { echo("Datenbank FEHLER!"); }
            } else {
                if (strlen($gpassword) >= 6) { $gpassword = hash('haval256,4', $gpassword); } else { header("Location: ../admin.php?site=useredit&uid=$olduid&error=104"); }
                $edit = mysqli_query($db, "UPDATE users SET uid='$guid', username='$gusername', password='$gpassword', class='$gclass', gid='$ggid' WHERE uid='$olduid'");
                if ($edit) { header("Location: ../admin.php?site=users&error=3"); } else { echo("Datenbank FEHLER!"); }
            }
        }
    } else {
        header("Location: ../admin.php?site=useredit&uid=$olduid&error=102");
    }
}

# User Delete
else if ($act == 5 and isset($_GET['uid']) and $permission >= 80) {
    $guid = $_GET['uid'];

    # Rechte überprüfen
    $checkup = mysqli_fetch_array(mysqli_query($db, "SELECT permission FROM usergroups LEFT JOIN users ON usergroups.gid = users.gid WHERE users.uid = '$guid'"));
    if ($permission <= $checkup['permission']) { header("Location: ../admin.php?site=users&error=6"); exit(); } # Rechte überprüfen

    $delguid = mysqli_query($db, "DELETE FROM users WHERE uid = '$guid'");
    $delentry = mysqli_query($db, "DELETE FROM entries WHERE uid = '$guid'");

    if ($delguid and $delentry) {
        header("Location: ../admin.php?site=users&error=4");
    } else { echo("Datenbank FEHLER!"); }
}

# User Add
else if ($act == 6 and $permission >= 80) {
    $guid = $_POST['uid'];
    $gusername = $_POST['username'];
    $gpassword = $_POST['password'];
    $gclass = $_POST['class'];
    $ggid = $_POST['gid'];

    if ($gusername != "" and $gpassword != "" and ctype_digit($gclass)) {
        if (strlen($gpassword) >= 6) { $gpassword = hash('haval256,4', $gpassword); } else { header("Location: ../admin.php?site=useradd&error=104"); exit();}
        if (!ctype_digit($guid)) { $add = mysqli_query($db, "INSERT INTO users (username, password, gid, class) VALUES ('$gusername', '$gpassword', '$ggid', '$gclass')"); }
        else {
            $check = mysqli_num_rows(mysqli_query($db, "SELECT uid FROM users WHERE uid = '$guid'"));
            if ($check == 0) { $add = mysqli_query($db, "INSERT INTO users (uid, username, password, gid, class) VALUES ('$guid', '$gusername', '$gpassword', '$ggid', '$gclass')"); }
            else { header("Location: ../admin.php?site=useradd&error=104"); exit(); }
        }

        if ($add) {
            if (!ctype_digit($guid)) { $guid = mysqli_insert_id($db); }
            header("Location: ../admin.php?site=useradd&error=1");
        } else { echo("Datenbank FEHLER!"); }
    } else {
        header("Location: ../admin.php?site=useradd&error=102");
    }
}

# Gruppe löschen
else if ($act == 7 and $permission >= 80)  {
    $ggid = $_GET['gid'];

    # Rechte überprüfen
    $checkup = mysqli_fetch_array(mysqli_query($db, "SELECT permission FROM usergroups WHERE gid = '$ggid'"));
    if ($permission <= $checkup['permission']) { header("Location: ../admin.php?site=usergroups&error=6"); exit(); } 

    $del = mysqli_query($db, "DELETE FROM usergroups WHERE gid = '$ggid'");
    $update = mysqli_query($db, "UPDATE users SET gid = '0' WHERE gid = '$ggid'");

    if ($del and $update) {
        header("Location: ../admin.php?site=usergroups&error=4");
    } else { echo("Datenbank FEHLER!"); }
}

# Gruppe hinzufügen
else if ($act == 8 and $permission >= 80)  {
    $ggid = $_POST['gid'];
    $gname = $_POST['name'];
    $gpermission = $_POST['permission'];

    if ($gname != "" and ctype_digit($gpermission)) {
        # Rechte überprüfen
        if ($gpermission >= $permission) { header("Location: ../admin.php?site=usergroups&error=9"); exit(); }

        if (!ctype_digit($ggid)) { $add = mysqli_query($db, "INSERT INTO usergroups (name, permission) VALUES ('$gname', '$gpermission')"); }
        else {
            $check = mysqli_num_rows(mysqli_query($db, "SELECT gid FROM usergroups WHERE gid = '$ggid'"));
            if ($check == 0) { $add = mysqli_query($db, "INSERT INTO usergroups (gid, name, permission) VALUES ('$ggid', '$gname', '$gpermission')"); }
            else { header("Location: ../admin.php?site=usergroupadd&error=103"); }
        }

        if ($add) {
            if (!ctype_digit($ggid)) { $guid = mysqli_insert_id($db); }
            header("Location: ../admin.php?site=useradd&error=1");
        } else { echo("Datenbank FEHLER!"); }
    } else { header("Location: ../admin.php?site=useradd&error=102"); }
}

# Gruppe editieren
else if ($act == 9 and $permission >= 80 and isset($_GET['gid']))  {
    $oldgid = $_GET['gid'];
    $ggid = $_POST['gid'];
    $gname = $_POST['name'];
    $gpermission = $_POST['permission'];

    if ($gpermission >= $permission) { header("Location: ../admin.php?site=usergroupedit&gid=$oldgid&error=9"); exit(); }

    $check = mysqli_num_rows(mysqli_query($db, "SELECT gid FROM usergroups WHERE gid = '$ggid'"));
    if ($oldgid == $ggid or $check == 0) {
        $edit = mysqli_query($db, "UPDATE usergroups SET gid = '$ggid', name = '$gname', permission = '$gpermission' WHERE gid = '$oldgid'");
        if ($ggid != $oldgid) { $uedit = mysqli_query($db, "UPDATE users SET gid = '$ggid' WHERE gid = '$oldgid'"); } else { $uedit = true; }
        if ($edit and $uedit) { header("Location: ../admin.php?site=usergroups&error=1"); } else { echo("Datenbank FEHLER?!"); }
    } else { header("Location: ../admin.php?site=usergroupedit&error=103"); }
}

# URL Fehlern / Rechte ungenügend
else { header("Location: ../admin.php?error=6"); }
?>