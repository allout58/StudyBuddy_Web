<?php
require_once '../common.inc';

$uid = getFirebaseUIDFromJWT($_POST['jwt']);

if ($uid != null) {
    try {
        $dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $upd_prep = $dbo->prepare("UPDATE Users SET locationID=:lid, subID=:sid, blurb=:blurb, endTime=:endTime  WHERE firebase_uid=:fid");
        $upd_prep->bindValue(":fid", $uid);
        $upd_prep->bindValue(":lid", $_POST['locationID']);
        $upd_prep->bindValue(":sid", $_POST['sublocationID']);
        $upd_prep->bindValue(":blurb", $_POST['other']);
        $upd_prep->bindValue(":endTime", $_POST['endTime']);
        $upd_prep->execute();
        $sel_friends_regid_prep = $dbo->prepare("SELECT * FROM 
        (SELECT fcm_regID FROM Users INNER JOIN Friends ON Users.firebase_uid = Friends.requestee WHERE requester=:fid) AS x 
        UNION
        (SELECT fcm_regID FROM Users INNER JOIN Friends ON Users.firebase_uid = Friends.requester WHERE requestee=:fid)");
        $sel_friends_regid_prep->bindValue(":fid", $uid);
        $sel_friends_regid_prep->bindColumn(1, $regID);
        $sel_friends_regid_prep->execute();
        $friendsRegID = array();
        while ($sel_friends_regid_prep->fetch()) {
            array_push($friendsRegID, $regID);
        }
        fcm_sendMulti(array("moved" => $uid), $friendsRegID);
        $out = array("status" => "success");
    } catch (PDOException $e) {
        $out = array("error" => "Database Error", "details" => $e);
    }
    echo json_encode($out);
} else {
    http_response_code(401);
    die("{'error':'Bad token'}");
}
