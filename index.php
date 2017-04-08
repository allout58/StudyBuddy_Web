<?php
require_once "inc/mysql.inc";
require_once "inc/functions.inc";

$row = array();

$sel_res = $dbo->query("SELECT locationID AS id, name, longitude, latitude, radius FROM Locations");

$out = array();
while (($row = $sel_res->fetch(PDO::FETCH_ASSOC))) {
    array_push($out, $row);
}
?>
<html>
<head>
    <title>StudyBuddy - Locations</title>
    <?php require_once "inc/css.inc"; ?>
</head>
<body>
<?php require_once "inc/menu.inc"; ?>
<div class="container">
    <h3>Locations</h3>
    <a href="add_loc.php" class="waves-light waves-effect btn"><i class="material-icons right">add</i>Add</a>
    <p>Edit a location to view and edit its sublocations.</p>
    <table class="highlight">
        <thead>
        <tr>
            <th>Name</th>
            <th>Longitude</th>
            <th>Latitude</th>
            <th>Radius</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($out as $r) { ?>
            <tr>
                <td><?php echo $r['name']; ?></td>
                <td><?php echo $r['longitude']; ?></td>
                <td><?php echo $r['latitude']; ?></td>
                <td><?php echo $r['radius']; ?></td>
                <td>
                    <?php
                    echo "<a href='edit_loc.php?id=${r['id']}'><i class='material-icons'>edit</i></a>";
                    echo "<a href='delete_loc.php?id=${r['id']}'><i class='red-text material-icons'>delete</i></a></td>";
                    ?>
                </td>
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