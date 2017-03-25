<?php
/**
 * Fetches all cars for a given user. Used to load all of a user's data the first time the log into a device
 */

require_once '../common.inc';

$row = array();

$sel_prep = $dbo->prepare("SELECT c.carID AS id, make, model, license, state, color, year, sort_order AS sortOrder, isDeleted, UNIX_TIMESTAMP(last_update) AS lastUpdate FROM Cars AS c INNER JOIN UsersCars AS uc ON c.carID=uc.cariD WHERE uc.userID=:uid");
$sel_prep->bindParam(":uid", $_GET['uid']);
$sel_prep->execute();

$out = array();
while (($row = $sel_prep->fetch(PDO::FETCH_ASSOC))) {
    $row['isDeleted'] = $row['isDeleted'] == 1;
    $row['colorHex'] = colorIntToARGBHex(intval($row['color']));
    unset($row['color']);
    array_push($out, $row);
}
echo json_encode($out);
