<?php
require_once "inc/login.inc";
require_once "inc/mysql.inc";

if (isset($_GET['confirm']) && $_GET['confirm'] == "1") {
    $upd_prep = $dbo->prepare("UPDATE Cars SET isDeleted=1 WHERE carID=:id");
    $upd_prep->bindValue(":id", $_GET['id']);
    $upd_prep->execute();
    header("Location: view.php");
    die();
}

$row = array();

$sel_prep = $dbo->prepare("SELECT make, model, year FROM Cars WHERE carID=:carID");
$sel_prep->bindValue(":carID", $_GET['id']);
$sel_prep->execute();

$row = $sel_prep->fetch(PDO::FETCH_ASSOC);
?>
<html>
<head>
    <title>MyGarage - Delete Car</title>
    <?php require_once "inc/css.inc"; ?>
</head>
<body>
<?php require_once "inc/menu.inc"; ?>
<div class="container">
    <h3>Confirm Delete</h3>
    <p>Are you sure you want to send your <strong><?php echo $row['year'] . " " .$row['make'] . " " . $row['model'];?></strong> to the trashcan?</p>
    <a href="delete.php?id=<?php echo $_GET['id'];?>&confirm=1" class="waves-effect waves-light btn red"><i class="material-icons right">delete</i>Delete</a>
    <a href="view.php" class="waves-effect waves-light btn">Cancel</a>
</div>
<?php require_once "inc/js.inc"; ?>
</body>
</html>