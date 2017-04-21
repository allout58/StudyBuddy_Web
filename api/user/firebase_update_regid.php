<?php
require_once '../common.inc';

$uid = getFirebaseUIDFromJWT($_POST['jwt']);
$out = array();

if ($uid != null) {
    $upd_prep = $dbo->prepare("UPDATE Users SET fcm_regID=:regid WHERE firebase_uid=:fb_uid");
    $upd_prep->bindValue(":regid", $_POST['regID']);
    $upd_prep->bindValue(":fb_uid", $uid);
    $upd_prep->execute();
    $out = array("status" => "success");
    echo json_encode($out);
} else {
    http_response_code(401);
    die("{'error':'Bad token'}");
}