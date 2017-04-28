<?php
use paragraph1\phpFCM\Client;
use paragraph1\phpFCM\Message;
use paragraph1\phpFCM\Recipient\Device;


define("FCM_SENDER_ID", "805299222455");
define("FCM_SERVER_KEY", "AAAAu3-S97c:APA91bEli17J5qdjMNhVHJnDhXvC4ogBMDzNgAYJcZm_z67EuLqdh62seqoXiC2vtOFWo80OeMcD-AWVkh7AqULsdmQGePhB2LUBkltKsuG7Q1fAWJeUPIL0Dy_6R8atd6GvEw3gZbQH");
define("FCM_ENDPOINT", "https://fcm.googleapis.com/fcm/send");

function fcm_sendMulti($data, $targets, $key = null, $priority = "normal")
{
    $client = new Client();
    $client->setApiKey(FCM_SERVER_KEY);
    $client->injectHttpClient(new \GuzzleHttp\Client());
    $message = new Message();
    foreach ($targets as $target) {
        $message->addRecipient(new Device($target));
    }
    $message->setData($data)->setPriority($priority);
    if ($key != null) {
        $message->setCollapseKey($key);
    }
    $response = $client->send($message);
    return json_decode($response->getBody());
}

function fcm_sendSingle($data, $target, $key = null, $priority = "normal")
{
    $client = new Client();
    $client->setApiKey(FCM_SERVER_KEY);
    $client->injectHttpClient(new \GuzzleHttp\Client());
    $message = new Message();
    $message->addRecipient(new Device($target));
    $message->setData($data)->setPriority($priority);
    if ($key != null) {
        $message->setCollapseKey($key);
    }
    $response = $client->send($message);
    return json_decode($response->getBody());
}