<?php
require_once '../common.inc';

$uid = getFirebaseUIDFromJWT($_POST['jwt']);

if ($uid != null) {
    $upd_prep = $dbo->prepare("UPDATE Users SET subID=:sid WHERE firebase_uid=:fid");
    $upd_prep->bindValue(":fid", $uid);
    $upd_prep->bindValue(":sid", $_POST['sublocationID']);
    $upd_prep->execute();
    $out = array("status" => "success");
    echo json_encode($out);
} else {
    http_response_code(401);
    die("{'error':'Bad token'}");
}
