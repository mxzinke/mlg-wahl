<?php
# Rechtesystem und Einstellungen-Aktionen
include("../settings.php"); # Einstellungen importieren

# Actionen:

# P-Points Anzahl neu setzen
function set_points($rid, $newpermission, $ownpermission) {
    global $db;
    if (check_right($rid, $ownpermission) == false) {
        return true;
    } else {
        if ($ownpermission < $newpermission) {
            return false;
        } else {
            $update = mysqli_query($db, "UPDATE rights SET permission = '$newpermission' WHERE rid = '$rid'");
            if (!$update) {
                
                return false;
                
            } else {
                return true;
            }
        }
    }
}

# UPDATE Right
if ($_GET['act'] == 1 and isset($_GET['rid']) and isset($_POST['permission'])) {
    $rid = $_GET['rid'];
    $newperm = $_POST['permission'];
    
    if (check_right('ableto_edit_right', $permission)) { # Änderungsberechtigung?
        if (set_points($rid, $newperm, $permission)) {
            header("Location: ../admin.php?site=rights&error=3");
        } else {
            exit();
            header("Location: ../admin.php?site=rights&error=6");
        }
    } else {
        header("Location: ../admin.php?site=rights&error=6");
    }
}

?>