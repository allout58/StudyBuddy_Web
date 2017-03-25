<?php
require_once 'mysql.inc';

switch ($_GET['mode']) {
    case 'user':
        $ins = $dbo->prepare("INSERT INTO Users (uname, password, joindate) VALUES (:uname, SHA2(:pwd, 256), NOW())");
        $ins->bindParam(':uname', $_GET['uname']);
        $ins->bindParam(':pwd', $_GET['password']);
        echo "{$_GET['uname']} :: {$_GET['password']}";
        $ins->execute();
        break;
    case 'car':
        $ins = $dbo->prepare("INSERT INTO Cars (make, model, license, state, color, year, sort_order) VALUES (:make, :model, :license, :state, :color, :year, :sort_order)");
        $ins->bindParam(':make', $_GET['make']);
        $ins->bindParam(':model', $_GET['model']);
        $ins->bindParam(':license', $_GET['license']);
        $ins->bindParam(':state', $_GET['state']);
        $ins->bindParam(':color', $_GET['color']);
        $ins->bindParam(':year', $_GET['year']);
        $ins->bindParam(':sort_order', $_GET['sort_order']);
        $ins->execute();
        break;
    case 'uc':
        $ins = $dbo->prepare("INSERT INTO UsersCars (userID, carID) VALUES (:uid, :cid)");
        $ins->bindParam(':uid', $_GET['userID']);
        $ins->bindParam(':cid', $_GET['carID']);
        $ins->execute();
        break;
}
echo "Insert Submitted";
