<?php
require_once '../common.inc';

$uid = getFirebaseUIDFromJWT($_POST['jwt']);
$out = array();

if ($uid != null) {
    // Go to the DB and try to pull up the account associated with the Firebase UID
    $prep_sel = $dbo->prepare("SELECT realName FROM Users WHERE firebase_uid=:uid");
    $prep_sel->bindColumn(1, $name);
    $prep_sel->bindValue(":uid", $uid);
    $prep_sel->execute();
    if ($prep_sel->rowCount() == 0) {
        // If it doesn't exist, make one
        $prep_ins = $dbo->prepare("INSERT INTO Users (firebase_uid, realName, imageURL, lastLogin) VALUES (:f_uid, :name, :url, NOW())");
        $prep_ins->bindValue(":f_uid", $uid);
        $prep_ins->bindValue(":name", $_POST['name']);
        $prep_ins->bindValue(":url", $_POST['imageURL']);
        $prep_ins->execute();
        $prep_sel->execute();
    } else {
        $prep_upd = $dbo->prepare("UPDATE Users SET lastLogin=NOW() WHERE firebase_uid=:f_uid");
        $prep_upd->bindValue(":f_uid", $uid);
        $prep_upd->execute();
    }
    $prep_sel->execute();
    $prep_sel->fetch();
    $out['name'] = $name;
    $unixTimeDB = $dbo->query("SELECT UNIX_TIMESTAMP(NOW())");
    $out["currentTime"] = $unixTimeDB->fetchColumn(0);
    echo json_encode($out);
} else {
    http_response_code(401);
    die("{'error':'Bad token'}");
}