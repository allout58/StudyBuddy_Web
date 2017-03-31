<?php
require_once '../common.inc';

$uid = getFirebaseUIDFromJWT($_POST['jwt']);

if ($uid != null) {
    $friends = getFriendsForUser($uid, $dbo);
    echo json_encode($friends);
} else {
    http_response_code(401);
    die("{'error':'Bad token'}");
}
