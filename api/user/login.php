<?php
require_once '../common.inc';


$uname = $_POST['user'];
$tok = $_POST['token'];
$userID = "";
if ($tok != "") {
    // Token based login
    // Token (and thus last login) only valid for 30 days, then will need to relogin
    $prep = $dbo->prepare("SELECT userID, (lastLogin > NOW() - INTERVAL 30 DAY), realName FROM Users WHERE username=:user AND SHA2(CONCAT(UNIX_TIMESTAMP(lastLogin), password),256)=:token");
    $prep->bindParam(":user", $uname);
    $prep->bindParam(":token", $tok);
    $prep->bindColumn(1, $userID);
    $prep->bindColumn(2, $lastLoginRecent);
    $prep->bindColumn(3, $name);
    $prep->execute();
    $prep->fetch();
    // If the token is too old, tell the client to ask the user to login
    if ($lastLoginRecent == 0 || $prep->rowCount() == 0) {
        die('{"error": "Please log in again", "code": 1}');
    }
} else {
    $passwd = $_POST['pass'];
    $prep = $dbo->prepare("SELECT userID, realName FROM Users WHERE username=:user AND password=SHA2(:pwd, 256)");
    $prep->bindParam(":user", $uname);
    $prep->bindParam(":pwd", $passwd);
    $prep->bindColumn(1, $userID);
    $prep->bindColumn(2, $name);
    $prep->execute();
    $prep->fetch();
}
if ($prep->rowCount() == 1) {
    $out = array();
    $out['username'] = $uname;
    $out['userid'] = $userID;
    $out['name'] = $name;
    // Update the lastLogin
    if (isset($passwd)) {
        //We don't need the security of a prepared statement for a non user-generated value
        $dbo->exec("UPDATE Users SET lastLogin=NOW() WHERE userID=$userID");
    }
    $statement = $dbo->query("SELECT SHA2(CONCAT(UNIX_TIMESTAMP(lastLogin), password),256) AS token FROM Users WHERE userID=$userID");
    $row = $statement->fetch(PDO::FETCH_ASSOC);
    $out['token'] = $row['token'];
//    $lastTimestamp = "";
//    $prep_upd = $dbo->prepare("SELECT c.last_update FROM Cars AS c INNER JOIN UsersCars AS uc ON c.carID = uc.carID WHERE uc.userID=:userid ORDER BY c.last_update ASC LIMIT 1");
//    $prep_upd->bindParam(":userid", $userID);
//    $prep_upd->bindColumn(1, $lastTimestamp);
//    $prep_upd->execute();
//    $prep_upd->fetch();
//    if ($prep_upd->rowCount() == 1) {
//        $out['lastTimestamp'] = $lastTimestamp;
//    }
    $unixTimeDB = $dbo->query("SELECT UNIX_TIMESTAMP(NOW())");
    $out["currentTime"] = $unixTimeDB->fetchColumn(0);
    echo json_encode($out);
} else {
    echo '{"error": "Invalid username or password."}';
}
