<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../common.inc';


use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;

$token = (new Parser())->parse((string) $_POST['jwt']);

$validator = new ValidationData();
$validator->setAudience("studybuddy-66b00");
$validator->setIssuer("https://securetoken.google.com/studybuddy-66b00");

if ($token->validate($validator)) {
    $out = array();
    $out["valid"] = true;
    $out["uid"] = $token->getClaim("sub");
    echo json_encode($out);
    // Go to the DB and try to pull up the account associated with the Firebase UID

    // If it doesn't exist, make one
}
else {
    $out = array();
    $out["valid"] = false;
    echo json_encode($out);
}