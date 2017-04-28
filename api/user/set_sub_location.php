<?php
require_once '../common.inc';

$uid = getFirebaseUIDFromJWT($_POST['jwt']);

if ($uid != null) {
    $upd_prep = $dbo->prepare("UPDATE Users SET subID=:sid, blurb=:blurb WHERE firebase_uid=:fid");
    $upd_prep->bindValue(":fid", $uid);
    $upd_prep->bindValue(":sid", $_POST['sublocationID']);
    $upd_prep->bindValue(":blurb", $_POST['other']);
    $upd_prep->execute();
    $sel_friends_regid_prep = $dbo->prepare("SELECT * FROM 
        (SELECT fcm_regID FROM Users INNER JOIN Friends ON Users.firebase_uid = Friends.requestee WHERE requester=:fid) AS x 
        UNION
        (SELECT fcm_regID FROM Users INNER JOIN Friends ON Users.firebase_uid = Friends.requester WHERE requestee=:fid)");
    $sel_friends_regid_prep->bindValue(":fid", $uid);
    $sel_friends_regid_prep->bindColumn(1, $regID);
    $sel_friends_regid_prep->execute();
    $friendsRegID = array();
    while($sel_friends_regid_prep->fetch()) {
        array_push($friendsRegID, $regID);
    }
    fcm_sendMulti(array("moved" => $uid), $friendsRegID);
    $out = array("status" => "success");
    echo json_encode($out);
} else {
    http_response_code(401);
    die("{'error':'Bad token'}");
}
