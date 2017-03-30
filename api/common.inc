<?php

require_once __DIR__ . '/../inc/mysql.inc';
require_once __DIR__ . '/../inc/functions.inc';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../inc/firebase_auth.inc';

header("Content-Type: application/json");
ini_set('display_errors', 'Off');
