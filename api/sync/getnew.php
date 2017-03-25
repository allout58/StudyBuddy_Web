<?php
/**
 * Fetches newly updated/created cars for a given user. Used to synchronize a client database
 *
 * Inputs:
 *  User ID - uid
 *  Last Timestamp of Client - last_upd - in the form of a UNIX timestamp
 */

require_once '../common.inc';

$row = array();

$last_upd = intval($_GET['last_upd']);
$last_upd = date("Y-m-d H:i:s", $last_upd);

$sel_prep = $dbo->prepare("SELECT c.carID AS id, make, model, license, state, color, year, sort_order AS sortOrder, isDeleted, UNIX_TIMESTAMP(last_update) as lastUpdate FROM Cars AS c INNER JOIN UsersCars AS uc ON c.carID=uc.cariD WHERE uc.userID=:uid AND c.last_update > :last_upd");

$sel_prep->bindParam(":uid", $_GET['uid']);
$sel_prep->bindParam(":last_upd", $last_upd);
$sel_prep->execute();

$out = array();
while (($row = $sel_prep->fetch(PDO::FETCH_ASSOC))) {
    $row['isDeleted'] = $row['isDeleted'] == 1;
    $row['colorHex'] = colorIntToARGBHex(intval($row['color']));
    unset($row['color']);
    array_push($out, $row);
}
echo json_encode($out);
