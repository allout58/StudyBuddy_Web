<?php
require_once "inc/mysql.inc";

$upd_prep = $dbo->prepare("UPDATE Cars SET isDeleted=0 WHERE carID=:id");
$upd_prep->bindValue(":id", $_GET['id']);
$upd_prep->execute();
header("Location: view.php");
die();