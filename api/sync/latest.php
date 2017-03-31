<?php
require_once '../common.inc';

$uid = getFirebaseUIDFromJWT($_POST['jwt']);
if ($uid != "") {
    $ts = "";
    $lastTimestamp = "";
    $prep = $dbo->prepare("SELECT UTC_TIMESTAMP(last_update) FROM Locations ORDER BY last_update ASC LIMIT 1");
    $prep->bindColumn(1, $lastTimestamp);
    $prep->execute();
    $prep->fetch();
    $prep2 = $dbo->prepare("SELECT UTC_TIMESTAMP(last_update) FROM SubLocations ORDER BY last_update ASC LIMIT 1");
    $prep2->bindColumn(1, $ts);
    $prep2->execute();
    $prep2->fetch();
    echo json_encode(array('locations' => $lastTimestamp, 'sublocations' => $ts));

} else {
    http_response_code(401);
    die("{'error':'Bad token'}");
}