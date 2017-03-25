<?php
require_once '../common.inc';

$data = json_decode($_POST['data'], true);

$upd = $dbo->prepare("UPDATE Cars SET isDeleted=:del WHERE carID=:id");
$upd->bindParam(':del', $data['isDeleted']);
$upd->bindValue(":id", $data['id']);
$upd->execute();

?>
