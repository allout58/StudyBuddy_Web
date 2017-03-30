<?php
require_once '../common.inc';

$uid = getFirebaseUIDFromJWT($_POST['jwt']);
$out = array();

if ($uid != null) {
//    $out["valid"] = true;
//    $out["uid"] = $token->getClaim("sub");
    // Go to the DB and try to pull up the account associated with the Firebase UID
    $prep_sel = $dbo->prepare("SELECT userID, lastLogin FROM Users WHERE firebase_uid=:uid");
    $prep_sel->bindColumn(1, $userid);
    $prep_sel->bindColumn(2, $lastLogin);
    $prep_sel->bindValue(":uid", $uid);
    $prep_sel->execute();
    if ($prep_sel->rowCount() == 0) {
        // If it doesn't exist, make one
        $prep_ins = $dbo->prepare("INSERT INTO Users (firebase_uid, realName, lastLogin) VALUES (:f_uid, :name, NOW())");
        $prep_ins->bindValue(":f_uid", $uid);
        $prep_ins->bindValue(":name", $_POST['name']);
        $prep_ins->execute();
        $prep_sel->execute();
    }
    $prep_sel->fetch();
    $out['uid'] = $userid;
    $out['lastLogin'] = $lastLogin;
} else {
    $out["valid"] = false;
}
echo json_encode($out);