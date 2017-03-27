<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../common.inc';


use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;

$token = (new Parser())->parse((string)$_POST['jwt']);

$validator = new ValidationData();
$validator->setAudience("studybuddy-66b00");
$validator->setIssuer("https://securetoken.google.com/studybuddy-66b00");

$out = array();

if ($token->validate($validator)) {
//    $out["valid"] = true;
//    $out["uid"] = $token->getClaim("sub");
    // Go to the DB and try to pull up the account associated with the Firebase UID
    $prep_sel = $dbo->prepare("SELECT userID, lastLogin FROM Users WHERE firebase_uid=:uid");
    $prep_sel->bindColumn(1, $userid);
    $prep_sel->bindColumn(2, $lastLogin);
    $prep_sel->bindValue(":uid", $token->getClaim("sub"));
    $prep_sel->execute();
    if ($prep_sel->rowCount() == 0) {
        // If it doesn't exist, make one
        $prep_ins = $dbo->prepare("INSERT INTO Users (firebase_uid, lastLogin) VALUES (:f_uid, NOW())");
        $prep_ins->bindValue(":f_uid", $token->getClaim("sub"));
        $prep_ins->execute();
    }
    $prep_sel->fetch();
    $out['uid'] = $userid;
    $out['lastLogin'] = $lastLogin;

} else {

    $out["valid"] = false;

}
echo json_encode($out);