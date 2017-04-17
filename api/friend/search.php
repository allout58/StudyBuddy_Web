<?php
require_once '../common.inc';

$uid = getFirebaseUIDFromJWT($_POST['jwt']);

if ($uid != null) {
    $sel_prep = $dbo->prepare("SELECT firebase_uid, realName, imageURL FROM Users WHERE realName LIKE :name AND firebase_uid != :fbid");
    $sel_prep->bindValue(":name", "%" . implode("%", explode(" ", $_POST['search'])) . "%");
    $sel_prep->bindValue(":fbid", $uid);
    $sel_prep->execute();
    $res = array();
    while (($row = $sel_prep->fetch(PDO::FETCH_ASSOC))) {
        if (!isset($row['imageURL']) || $row['imageURL'] == null) {
            $row['imageURL'] = "";
        }
        array_push($res, $row);
    }
    $out = array("results" => $res, "status" => "success");
    echo json_encode($out);
} else {
    http_response_code(401);
    die("{'error':'Bad token'}");
}
