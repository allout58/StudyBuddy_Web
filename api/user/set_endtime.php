<?php
require_once '../common.inc';

$uid = getFirebaseUIDFromJWT($_POST['jwt']);

if ($uid != null) {
    $upd_prep = $dbo->prepare("UPDATE Users SET endTime=:endTime WHERE firebase_uid=:fid");
    $upd_prep->bindValue(":fid", $uid);
    $upd_prep->bindValue(":endTime", $_POST['endTime']);
    $upd_prep->execute();
    $out = array("status" => "success");
    echo json_encode($out);
} else {
    http_response_code(401);
    die("{'error':'Bad token'}");
}
