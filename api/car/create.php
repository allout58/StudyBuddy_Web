<?php
require_once '../common.inc';

$data = json_decode($_POST['data'], true);
$uid = $_POST['userid'];

$dbo->beginTransaction();

$ins = $dbo->prepare("INSERT INTO Cars (make, model, license, state, color, year, sort_order, last_update) VALUES (:make, :model, :license, :state, :color, :y, :sort_order, NOW())");
$ins->bindParam(':make', $data['make']);
$ins->bindParam(':model', $data['model']);
$ins->bindParam(':license', $data['license']);
$ins->bindParam(':state', $data['state']);
$upd->bindParam(':color', hexdec($data['colorHex']));
$ins->bindParam(':y', $data['year']);
$ins->bindParam(':sort_order', $data['sort_order']);
$ins->execute();
$carID = $dbo->lastInsertId();

$ins_uc = $dbo->prepare("INSERT INTO UsersCars (userID, carID) VALUES (:uid, :cid)");
$ins_uc->bindParam(":uid", $uid);
$ins_uc->bindParam(":cid", $carID);
$ins_uc->execute();

$dbo->commit();

$out = array();
$out["carID"] = $carID;
echo json_encode($out);
?>
