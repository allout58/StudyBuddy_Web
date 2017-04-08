<?php
/**
 * Fetches newly updated/created (sub)locations for a given user. Used to synchronize a client database
 *
 * Inputs:
 *  Client's Firebase JWT - jwt
 *  Last Timestamp of Client - last_upd - in the form of a UNIX timestamp
 */

require_once '../common.inc';

$uid = getFirebaseUIDFromJWT($_POST['jwt']);

if ($uid != null) {
    $out = array();

    $last_upd = intval($_POST['last_upd']);
    $last_upd = date("Y-m-d H:i:s", $last_upd);

    $prep_sel_locs = $dbo->prepare("SELECT locationID, name, longitude, latitude, radius FROM Locations WHERE last_update>:upd");
    $prep_sel_locs->bindValue(":upd", $last_upd);
    $prep_sel_locs->execute();
    $locs = array();
    while (($row = $prep_sel_locs->fetch(PDO::FETCH_ASSOC))) {
        array_push($locs, $row);
    }

    $prep_sel_sublocs = $dbo->prepare("SELECT subID, name, locationID FROM SubLocations WHERE last_update>:upd");
    $prep_sel_sublocs->bindValue(":upd", $last_upd);
    $prep_sel_sublocs->execute();
    $sublocs = array();
    while (($row = $prep_sel_sublocs->fetch(PDO::FETCH_ASSOC))) {
        array_push($sublocs, $row);
    }

    $out['locations'] = $locs;
    $out['sublocations'] = $sublocs;
    $out['last_upd'] = array($_POST['last_upd'], $last_upd);
    echo json_encode($out);
} else {
    http_response_code(401);
    die("{'error':'Bad token'}");
}

