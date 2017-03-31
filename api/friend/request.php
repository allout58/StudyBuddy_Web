<?php
require_once '../common.inc';

$uid = getFirebaseUIDFromJWT($_POST['jwt']);

if ($uid != null) {
    $upd_prep = $dbo->prepare("INSERT INTO Friends (requester, requestee, confirmed) VALUES (:fb_uid, :o_uid, FALSE)");
    $upd_prep->bindValue(":fb_uid", $uid);
    $upd_prep->bindValue(":o_uid", $_POST['otherID']);
    $upd_prep->execute();
    echo "{'status':'success'}";
} else {
    http_response_code(401);
    die("{'error':'Bad token'}");
}
