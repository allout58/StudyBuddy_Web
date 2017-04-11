<?php
require_once '../common.inc';

$uid = getFirebaseUIDFromJWT($_POST['jwt']);

if ($uid != null) {
    $upd_prep = $dbo->prepare("UPDATE Users SET locationID=:lid WHERE firebase_uid=:fid");
    $upd_prep->bindValue(":fid", $uid);
    $upd_prep->bindValue(":lid", $_POST['locationID']);
    $upd_prep->execute();
    $out = array("status" => "success");
    echo json_encode($out);
} else {
    http_response_code(401);
    die("{'error':'Bad token'}");
}
