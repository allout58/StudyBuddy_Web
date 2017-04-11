<?php
require_once '../common.inc';

$uid = getFirebaseUIDFromJWT($_POST['jwt']);

if ($uid != null) {
    // Check if other way is already added
    $sel_prep = $dbo->prepare("SELECT relationshipID FROM Friends WHERE requestee=:fb_uid AND requester=:o_uid");
    $sel_prep->bindValue(":fb_uid", $uid);
    $sel_prep->bindValue(":o_uid", $_POST['otherID']);
    $sel_prep->bindColumn(1, $relID);
    $sel_prep->execute();
    if ($sel_prep->rowCount() == 1) {
        // If it is confirm the relationship
        $sel_prep->fetch();
        $upd_prep = $dbo->prepare("UPDATE Friends SET confirmed=TRUE WHERE relationshipID=:rid");
        $upd_prep->bindValue(":rid", $relID);
        $upd_prep->execute();
    } else {
        // Else create the request
        $ins_prep = $dbo->prepare("INSERT INTO Friends (requester, requestee, confirmed) VALUES (:fb_uid, :o_uid, FALSE)");
        $ins_prep->bindValue(":fb_uid", $uid);
        $ins_prep->bindValue(":o_uid", $_POST['otherID']);
        $ins_prep->execute();
        // TODO: Notify requestee of new friend request
    }
    echo "{'status':'success'}";
} else {
    http_response_code(401);
    die("{'error':'Bad token'}");
}
