<?php
require_once '../common.inc';

ini_set('display_errors', 'On');

$uid = getFirebaseUIDFromJWT($_POST['jwt']);
//$uid = "zaTb1sQtz9hcOhlSJKcrH3xIJcR2";

if ($uid != null) {
    $sel_fcm_prep = $dbo->prepare("SELECT fcm_regID FROM Users WHERE firebase_uid=:id");
    $sel_fcm_prep->bindValue(":id", $_POST['otherID']);
    $sel_fcm_prep->bindColumn(1, $regID);
    $sel_fcm_prep->execute();
    $sel_fcm_prep->fetch();
    // TODO: Error checking on FCM response?
    $resp = fcm_sendSingle(array(
        "action" => "send_loc",
        "from_user" => $uid,
        "locationID" => $_POST['locationID'],
//        "sublocationID" => $_POST['sublocationID'],
        "blurb" => $_POST['blurb']
    ), $regID);
    echo "{'status':'success'}";
} else {
    http_response_code(401);
    die("{'error':'Bad token'}");
}