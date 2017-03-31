<?php
require_once '../common.inc';

$uid = getFirebaseUIDFromJWT($_POST['jwt']);

if ($uid != null) {
    $upd_prep = $dbo->prepare("DELETE FROM Friends WHERE (requester=:fb_uid AND requestee=:o_uid) OR (requestee=:fb_uid AND requester=:o_uid)");
    $upd_prep->bindValue(":fb_uid", $uid);
    $upd_prep->bindValue(":o_uid", $_POST['otherID']);
    $upd_prep->execute();
    echo "{'status':'success'}";
} else {
    http_response_code(401);
    die("{'error':'Bad token'}");
}
