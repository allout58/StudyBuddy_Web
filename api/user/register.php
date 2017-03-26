<?php
require_once '../common.inc';

// TODO: Get rid of the $method and just replace it in all instances
$uname = $_GET['user'];
$passwd = $_GET['pass'];
$rname = $_GET['realName'];
$sel_prep = $dbo->prepare("SELECT username FROM Users WHERE username=:uname");
$sel_prep->bindParam(":uname", $uname);
$sel_prep->bindColumn(1, $checkUname);
$sel_prep->execute();
if($sel_prep->rowCount() == 1) {
    die('{"error":"Username already in use"}');
}
else {
    $ins_prep = $dbo->prepare("INSERT INTO Users (username, password, realName) VALUES (:uname, SHA2(:pwd, 256), :rname)");
    $ins_prep->bindParam(':uname', $uname);
    $ins_prep->bindParam(':pwd', $passwd);
    $ins_prep->bindParam(':rname', $rname);
    $ins_prep->execute();
    $out = array();
    $out['userid'] = $dbo->lastInsertId();
    echo json_encode($out);
}
?>
