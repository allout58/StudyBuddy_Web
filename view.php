<?php
require_once "inc/login.inc";
require_once "inc/mysql.inc";
require_once "inc/functions.inc";

$row = array();

$sel_prep = $dbo->prepare("SELECT c.carID AS id, make, model, license, state, color, year, sort_order AS sortOrder, isDeleted, last_update AS lastUpdate FROM Cars AS c INNER JOIN UsersCars AS uc ON c.carID=uc.cariD WHERE uc.userID=:uid ORDER BY sort_order");
$sel_prep->bindParam(":uid", $_SESSION['userid']);
$sel_prep->execute();

$out = array();
while (($row = $sel_prep->fetch(PDO::FETCH_ASSOC))) {
    $row['isDeleted'] = $row['isDeleted'] == 1;
    array_push($out, $row);
}
?>
<html>
<head>
    <title>MyGarage - Your Garage</title>
    <?php require_once "inc/css.inc"; ?>
</head>
<body>
<?php require_once "inc/menu.inc"; ?>
<div class="container">
    <h3>Your Garage</h3>
    <a href="add.php" class="waves-light waves-effect btn"><i class="material-icons right">add</i>Add</a>
    <table class="highlight">
        <thead>
        <tr>
            <th>Make</th>
            <th>Model</th>
            <th>License</th>
            <th>State</th>
            <th>Year</th>
            <th>Color</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($out as $r) { ?>
            <tr<?php if($r['isDeleted']) echo " class='deleted'";?>>
                <td><?php echo $r['make'];?></td>
                <td><?php echo $r['model'];?></td>
                <td><?php echo $r['license'];?></td>
                <td><?php echo $r['state'];?></td>
                <td><?php echo $r['year'];?></td>
                <td><?php echo makeColoredBox(intval($r['color']));?></td>
                <td>                    <?php
                    if ($r['isDeleted']) {
                        echo "<a href='undelete.php?id=${r['id']}'><i class='green-text material-icons'>undo</i></a></td>";
                    }
                    else {
                        echo "<a href='edit.php?id=${r['id']}'><i class='material-icons'>edit</i></a>";
                        echo "<a href='delete.php?id=${r['id']}'><i class='red-text material-icons'>delete</i></a></td>";
                    }
                    ?>

            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
</div>
<?php require_once "inc/js.inc"; ?>
</body>
</html>