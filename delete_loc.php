<?php
require_once "inc/mysql.inc";

if (isset($_GET['confirm']) && $_GET['confirm'] == "1") {
    $dbo->beginTransaction();

    $upd_prep = $dbo->prepare("DELETE FROM Locations WHERE locationID=:id");
    $upd_prep->bindValue(":id", $_GET['id']);
    $upd_prep->execute();

    $del_sl_prep = $dbo->prepare("DELETE FROM SubLocations WHERE locationID=:id");
    $del_sl_prep->bindValue(":id", $_GET['id']);
    $del_sl_prep->execute();

    $dbo->commit();
    header("Location: index.php");
    die();
}

$row = array();

$sel_prep = $dbo->prepare("SELECT name FROM Locations WHERE locationID=:id");
$sel_prep->bindValue(":id", $_GET['id']);
$sel_prep->execute();

$row = $sel_prep->fetch(PDO::FETCH_ASSOC);
?>
<html>
<head>
    <title>StudyBuddy - Delete Location</title>
    <?php require_once "inc/css.inc"; ?>
</head>
<body>
<?php require_once "inc/menu.inc"; ?>
<div class="container">
    <h3>Confirm Delete</h3>
    <p>Are you sure you want to delete the
        <strong><?php echo $row['name']; ?></strong> location?</p>
    <a href="delete_loc.php?id=<?php echo $_GET['id']; ?>&confirm=1" class="waves-effect waves-light btn red"><i
                class="material-icons right">delete</i>Delete</a>
    <a href="index.php" class="waves-effect waves-light btn">Cancel</a>
</div>
<?php require_once "inc/js.inc"; ?>
</body>
</html>