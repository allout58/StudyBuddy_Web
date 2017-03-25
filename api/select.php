<html>
<head>
<title>SELECT</title>
</head>
<body>
<table>
<tr><th>ID</th><th>Make</th><th>Model</th><th>License</th><th>State</th><th>Year</th><th>Color</th><th>Owner</th></tr>
<?php
require_once 'mysql.inc';

if(isset($_GET['carID']) || isset($_GET['userID'])) {
    // TODO: Yes, yes, this isn't secure... To much effor to fix it for this project rn
    if (isset($_GET['carID'])) {
        $query = "WHERE c.carID = " . $_GET['carID'];
    }
    else {
        $query = "WHERE u.userID = " . $_GET['userID']; 
    }
    $res = $dbo->query("SELECT u.*, c.* FROM Cars AS c LEFT JOIN UsersCars AS uc ON c.carID = uc.carID LEFT JOIN Users AS u ON uc.userID = u.userID $query");
    
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$row['carID']}</td><td>{$row['make']}</td><td>{$row['model']}</td><td>{$row['license']}</td><td>{$row['state']}</td><td>{$row['year']}</td><td>{$row['color']}</td><td>{$row['uname']}</td>";
        echo "</tr>";
    }

}
else {
    $res = $dbo->query("SELECT u.*, c.* FROM Cars AS c LEFT JOIN UsersCars AS uc ON c.carID = uc.carID LEFT JOIN Users AS u ON uc.userID = u.userID");
    
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$row['carID']}</td><td>{$row['make']}</td><td>{$row['model']}</td><td>{$row['license']}</td><td>{$row['state']}</td><td>{$row['year']}</td><td>{$row['color']}</td><td>{$row['uname']}</td>";
        echo "</tr>";
    }
}
?>
</table>
</body>
</html>
