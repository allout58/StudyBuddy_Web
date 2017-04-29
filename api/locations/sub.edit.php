<?php
require_once '../common.inc';

try {
    $upd_prep = $dbo->prepare("UPDATE SubLocations SET name=:name WHERE subID=:subid");
    $upd_prep->bindValue(":name", $_POST['name']);
    $upd_prep->bindValue(":subid", $_POST['subID']);
    $upd_prep->execute();
    $sel = $dbo->query("SELECT fcm_regID FROM Users");
    $regids = array();
    while (($row = $sel->fetch())) {
        array_push($regids, $row[0]);
    }
    fcm_sendMulti(array("action" => "upd_locs"), $regids, "locs");
    echo json_encode(array("status" => "success"));
} catch (PDOException $e) {
    die(json_encode(array("error" => print_r($e, true))));
}