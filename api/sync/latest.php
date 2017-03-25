<?php

require_once '../common.inc';

// TODO: Get rid of the $method and just replace it in all instances
$method = $_GET;

$uid = $method['userid'];
$lastTimestamp = "";
$prep = $dbo->prepare("SELECT c.last_update FROM Cars AS c INNER JOIN UsersCars AS uc ON c.carID = uc.carID WHERE uc.userID=:userid ORDER BY c.last_update ASC LIMIT 1");
$prep->bindParam(":userid", $uid);
$prep->bindColumn(1, $lastTimestamp);
$prep->execute();
$prep->fetch();
if ($prep->rowCount() == 1) {
    $out = array();
    $out['lastTimestamp'] = $lastTimestamp;
    echo json_encode($out);
}
else {
    echo '{"error": "No cars for this user."}';
}
