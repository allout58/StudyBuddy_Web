<?php
require_once "common.inc";

ini_set('display_errors', 'On');

$_GET['otherID'] = "XU3PFUMLjTgEPInxlQU3TMpSmKz2";
$sel_fcm_prep = $dbo->prepare("SELECT fcm_regID FROM Users WHERE firebase_uid=:id");
$sel_fcm_prep->bindValue(":id", $_GET['otherID']);
$sel_fcm_prep->bindColumn(1, $regID);
$sel_fcm_prep->execute();
$sel_fcm_prep->fetch();
$resp = fcm_sendSingle(array("request" => "bla"), $regID);
echo json_encode($resp);