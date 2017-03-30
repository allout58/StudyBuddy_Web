<?php
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;

function getFirebaseUIDFromJWT($jwt)
{
    $token = (new Parser())->parse((string)$jwt);

    $validator = new ValidationData();
    $validator->setAudience("studybuddy-66b00");
    $validator->setIssuer("https://securetoken.google.com/studybuddy-66b00");

    if ($token->validate($validator)) {
        return $token->getClaim("sub");
    } else {
        return null;
    }
}