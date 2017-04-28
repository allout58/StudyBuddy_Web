<?php
require_once '../common.inc';

//ini_set('display_errors', 'On');

$uid = getFirebaseUIDFromJWT($_POST['jwt']);

if ($uid != null) {
    // Check if other way is already added
    $sel_prep = $dbo->prepare("SELECT relationshipID FROM Friends WHERE requestee=:fb_uid AND requester=:o_uid");
    $sel_prep->bindValue(":fb_uid", $uid);
    $sel_prep->bindValue(":o_uid", $_POST['otherID']);
    $sel_prep->bindColumn(1, $relID);
    $sel_prep->execute();
    if ($sel_prep->rowCount() == 1) {
        // If it is confirm the relationship
        $sel_prep->fetch();
        $upd_prep = $dbo->prepare("UPDATE Friends SET confirmed=TRUE WHERE relationshipID=:rid");
        $upd_prep->bindValue(":rid", $relID);
        $upd_prep->execute();
    } else {
        // Else create the request
        $ins_prep = $dbo->prepare("INSERT INTO Friends (requester, requestee, confirmed) VALUES (:fb_uid, :o_uid, FALSE)");
        $ins_prep->bindValue(":fb_uid", $uid);
        $ins_prep->bindValue(":o_uid", $_POST['otherID']);
        $ins_prep->execute();
        // Send
        $sel_fcm_prep = $dbo->prepare("SELECT fcm_regID FROM Users WHERE firebase_uid=:id");
        $sel_fcm_prep->bindValue(":id", $_POST['otherID']);
        $sel_fcm_prep->bindColumn(1, $regID);
        $sel_fcm_prep->execute();
        $sel_fcm_prep->fetch();
        $resp = fcm_sendSingle(array("action" => "request", "req_id" => $uid), $regID);
        $file = fopen("./log.txt", "a");
        fwrite($file, json_encode($resp));
    }
    echo "{'status':'success'}";
} else {
    http_response_code(401);
    die("{'error':'Bad token'}");
}
