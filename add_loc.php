<?php
require_once "inc/mysql.inc";
require_once "inc/functions.inc";

if (isset($_POST['id'])) {
    $colo = hexdec('FF' . $_POST['color']);
    $dbo->beginTransaction();

    $upd_prep = $dbo->prepare("INSERT INTO Cars (make, model, license, state, color, year, last_update) VALUES (:make, :model, :license, :state, :color, :y, NOW())");
    $upd_prep->bindValue(":make", $_POST['make']);
    $upd_prep->bindValue(":model", $_POST['model']);
    $upd_prep->bindValue(":license", $_POST['license']);
    $upd_prep->bindValue(":state", $_POST['state']);
    $upd_prep->bindValue(":y", $_POST['year']);
    $upd_prep->bindValue(":color", $colo);
    $upd_prep->execute();

    $carID = $dbo->lastInsertId();

    $uc_prep = $dbo->prepare("INSERT INTO UsersCars (userID, carID) VALUES (:uid, :cid)");
    $uc_prep->bindValue(":uid", $_SESSION['userid']);
    $uc_prep->bindValue(":cid", $carID);
    $uc_prep->execute();

    $dbo->commit();
    header("Location: edit.php?id=$carID");
    die();
}

?>
<html>
<head>
    <title>MyGarage - Add Car</title>
    <?php require_once "inc/css.inc"; ?>
</head>
<body>
<?php require_once "inc/menu.inc"; ?>
<div class="container">
    <h3>Add Car</h3>
    <form method="post">
        <input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>"/>
        <div class="row">
            <div class="input-field col m12 l6">
                <input id="make" name="make" type="text" class="validate" required>
                <label for="make">Make</label>
            </div>
            <div class="input-field col m12 l6">
                <input id="model" name="model" type="text" class="validate"
                       required>
                <label for="model">Model</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col m12 l6">
                <input id="license" name="license" type="text" class="validate" required>
                <label for="license">License</label>
            </div>
            <div class="input-field col m12 l6">
                <input id="state" name="state" type="text" class="validate">
                <label for="state">State</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col m12 l6">
                <input id="year" name="year" type="text" class="validate">
                <label for="year">Year</label>
            </div>
            <div class="col m12 l6">
                <input name="color" type="hidden" id="color_value">
                <button class="waves-effect waves-light btn jscolor {valueElement: 'color_value'}" type="button">
                    Pick a color
                </button>
            </div>
        </div>
        <button class="waves-light waves-effect btn" type="submit"><i class="material-icons right">send</i>Submit
        </button>
    </form>
</div>
<?php require_once "inc/js.inc"; ?>
<script src="js/jscolor.min.js"></script>
</body>
</html>