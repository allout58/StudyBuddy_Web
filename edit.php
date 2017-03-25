<?php
require_once "inc/login.inc";
require_once "inc/mysql.inc";
require_once "inc/functions.inc";

if (isset($_POST['id'])) {
    $colo = hexdec('FF' . $_POST['color']);
    echo "<!-- Color :0xFF${_POST['color']} :: $colo -->";
    $upd_prep = $dbo->prepare("UPDATE Cars SET make=:make, model=:model, license=:license, state=:state, color=:color, year=:y WHERE carID=:id");
    $upd_prep->bindValue(":id", $_POST['id']);
    $upd_prep->bindValue(":make", $_POST['make']);
    $upd_prep->bindValue(":model", $_POST['model']);
    $upd_prep->bindValue(":license", $_POST['license']);
    $upd_prep->bindValue(":state", $_POST['state']);
    $upd_prep->bindValue(":y", $_POST['year']);
    $upd_prep->bindValue(":color", $colo);
    $upd_prep->execute();
    $updSuccess = true;
}

$row = array();

$sel_prep = $dbo->prepare("SELECT * FROM Cars WHERE carID=:carID");
$sel_prep->bindValue(":carID", $_REQUEST['id']);
$sel_prep->execute();

$row = $sel_prep->fetch(PDO::FETCH_ASSOC);
?>
<html>
<head>
    <title>MyGarage - Edit Car</title>
    <?php require_once "inc/css.inc"; ?>
</head>
<body>
<?php require_once "inc/menu.inc"; ?>
<div class="container">
    <h3>Edit Car</h3>
    <?php if (isset($updSuccess)) { ?>
        <p class="green white-text" style="padding: 10px; border: darkgreen 1px; border-radius: 5px;">Update Successful</p>
        <?php
    }
    ?>
    <form method="post">
        <input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>"/>
        <div class="row">
            <div class="input-field col m12 l6">
                <input id="make" name="make" type="text" class="validate"
                       required value="<?php echo $row['make']; ?>">
                <label for="make">Make</label>
            </div>
            <div class="input-field col m12 l6">
                <input id="model" name="model" type="text" class="validate"
                       required value="<?php echo $row['model']; ?>">
                <label for="model">Model</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col m12 l6">
                <input id="license" name="license" type="text" class="validate"
                       required value="<?php echo $row['license']; ?>">
                <label for="license">License</label>
            </div>
            <div class="input-field col m12 l6">
                <input id="state" name="state" type="text" class="validate"
                       value="<?php echo $row['state']; ?>">
                <label for="state">State</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col m12 l6">
                <input id="year" name="year" type="text" class="validate"
                       value="<?php echo $row['year']; ?>">
                <label for="year">Year</label>
            </div>
            <div class="col m12 l6">
                <input name="color" type="hidden" id="color_value"
                       value="<?php echo colorIntToHex(intval($row['color'])); ?>">
                <button class="waves-effect waves-light btn jscolor {valueElement: 'color_value'}" type="button">
                    Pick a color
                </button>
            </div>
        </div>
        <a href="view.php" class="waves-effect waves-light btn">Cancel</a>
        <button class="waves-light waves-effect btn" type="submit">
            <i class="material-icons right">send</i>Submit
        </button>
    </form>
</div>
<?php require_once "inc/js.inc"; ?>
<script src="js/jscolor.min.js"></script>
</body>
</html>