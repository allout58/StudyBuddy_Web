<?php
require_once '../common.inc';

try {
    $add_prep = $dbo->prepare("INSERT INTO SubLocations (name, locationID) VALUES (:name, :parent) ");
    $add_prep->bindValue(":name", $_POST['name']);
    $add_prep->bindValue(":parent", $_POST['locationID']);
    if ($add_prep->execute()) {
        $lastID = $dbo->lastInsertId();
        $sel = $dbo->query("SELECT fcm_regID FROM Users");
        $regids = array();
        while (($row = $sel->fetch())) {
            array_push($regids, $row[0]);
        }
        fcm_sendMulti(array("action" => "upd_locs"), $regids, "locs");
        echo json_encode(array("status" => "success", "id" => $lastID));
    } else {
        die(json_encode(array("error" => print_r($add_prep->errorInfo(), true))));
    }
} catch (PDOException $e) {
    die(json_encode(array("error" => print_r($e, true))));
}