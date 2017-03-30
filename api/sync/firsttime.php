<?php
/**
 * Fetches all locations, sublocations, and friends for a given user. Used to load all of a user's data the first time the log into a device
 */

require_once '../common.inc';

$uid = getFirebaseUIDFromJWT($_POST['jwt']);

if ($uid != null) {
    $prep_sel_sub = $dbo->prepare("SELECT subID, name FROM SubLocations WHERE locationID=:lid");
    $prep_sel_sub->bindParam(":lid", $locID);

    $out = array();

    $sel_locs = $dbo->query("SELECT locationID, name, longitude, latitude, radius FROM Locations");
    $locs = array();
    while (($row = $sel_locs->fetch(PDO::FETCH_ASSOC))) {
        $row["sublocs"] = array();
        $prep_sel_sub->execute();
        while (($r = $prep_sel_sub->fetch(PDO::FETCH_ASSOC))) {
            array_push($row['sublocs'], $r);
        }
    }

    $friends = array();
    $friends['connected'] = array();
    $friends['my_requests'] = array();
    $friends['their_requests'] = array();



    $out['locations'] = $locs;
    $out['friends'] = $friends;
    echo json_encode($out);
} else {
    http_response_code(401);
    echo "{'error':'Bad token'}";
}
