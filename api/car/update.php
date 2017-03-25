<?php
require_once '../common.inc';

$data = json_decode($_POST['data'], true);

$file = fopen("../../update.api.log", "a");
fwrite($file, print_r($data, true));
fclose($file);

$upd = $dbo->prepare("UPDATE Cars SET make=:make, model=:model, license=:license, state=:state, color=:color, year=:y, sort_order=:sort_order WHERE carID=:id");
$upd->bindParam(':make', $data['make']);
$upd->bindParam(':model', $data['model']);
$upd->bindParam(':license', $data['license']);
$upd->bindParam(':state', $data['state']);
$upd->bindParam(':color', hexdec($data['colorHex']));
$upd->bindParam(':y', $data['year']);
$upd->bindParam(':sort_order', $data['sort_order']);
$upd->bindValue(":id", $data['id']);
$upd->execute();

?>
