<?php
require_once "inc/mysql.inc";
require_once "inc/functions.inc";
require_once "vendor/autoload.php";
require_once "inc/firebase_cm.inc";

if (isset($_POST['name'])) {

    $dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $dbo->beginTransaction();

    $upd_prep = $dbo->prepare("INSERT INTO Locations (name,longitude, latitude, radius) VALUES (:name, :long, :lat, :rad)");
    $upd_prep->bindValue(":name", $_POST['name']);
    $upd_prep->bindValue(":long", $_POST['longitude']);
    $upd_prep->bindValue(":lat", $_POST['latitude']);
    $upd_prep->bindValue(":rad", $_POST['radius']);
    $upd_prep->execute();

    $locID = $dbo->lastInsertId();

//    $uc_prep = $dbo->prepare("INSERT INTO SubLocations (name, locationID) VALUES (:name, :locID)");
//    $uc_prep->bindParam(":name", $subName);
//    $uc_prep->bindValue(":locID", $locID);
//    $uc_prep->execute();

    $dbo->commit();

    $sel = $dbo->query("SELECT fcm_regID FROM Users");
    $regids = array();
    while (($row = $sel->fetch())) {
        array_push($regids, $row[0]);
    }
    fcm_sendMulti(array("action" => "upd_locs"), $regids, "locs");

    header("Location: edit_loc.php?id=$locID");
    die();
}

?>
<html>
<head>
    <title>StudyBuddy - Add Location</title>
    <?php require_once "inc/css.inc"; ?>
</head>
<body>
<?php require_once "inc/menu.inc"; ?>
<div class="container">
    <h3>Location</h3>
    <form method="post">
        <div class="row">
            <div class="input-field col m12 l6">
                <input id="name" name="name" type="text" class="validate" required>
                <label for="name">Name</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col m12 l6">
                <input id="longitude" name="longitude" type="number" class="validate" required step="any">
                <label for="longitude">Longitude</label>
            </div>
            <div class="input-field col m12 l6">
                <input id="latitude" name="latitude" type="number" class="validate" required step="any">
                <label for="latitude">Latitude</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col m12 l6">
                <input id="radius" name="radius" type="number" class="validate" required>
                <label for="radius">Radius</label>
            </div>
        </div>
        <a href="index.php" class="waves-effect waves-light btn blue">Cancel</a>
        <button class="waves-light waves-effect btn" type="submit">
            <i class="material-icons right">send</i>Submit
        </button>
    </form>
    <hr>
    <!--    <div>-->
    <!--        <h4>Sublocations</h4>-->
    <!--        <div class="row">-->
    <!--            <div class="input-field col m12 l6">-->
    <!--                <input id="sub-add-name" type="text">-->
    <!--                <label for="sub-add-name">Name</label>-->
    <!---->
    <!--            </div>-->
    <!--            <div class="col m12 l6">-->
    <!--                <button type="button" class="btn waves-effect waves-light" onclick="doAdd()"><i-->
    <!--                            class="material-icons right">add</i>Add-->
    <!--                </button>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <ul class="collection" id="sublocs">-->
    <!--        </ul>-->
    <!--    </div>-->
</div>
<div id="delModal" class="modal" data-id="-1">
    <div class="modal-content">
        <h4>Delete</h4>
        <p>Are you sure you wish to delete <span id="deleteName">-error-</span></p>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">No</a>
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat" onclick="doDelete()"><i
                    class='material-icons'>delete</i>Yes</a>
    </div>
</div>
<?php require_once "inc/js.inc"; ?>
<script type="text/template" data-template="listItem">
    <li class='collection-item row' id='sub-${subID}' data-id='${subID}' data-name='${name}'>
        <div class='col s9'>
            <span class='title'>${name}</span>
            <div class='input-field edit'>
                <input type='text' id='sub-${subID}-edit' value='${name}'/>
                <label for='sub-${subID}-edit'>Name</label>
            </div>
        </div>
        <div class='col s3'>
            <button type='button' class='btn waves-effect waves-light blue' onclick='startEdit(${subID})'><i
                        class='material-icons'>edit</i></button>
            <button type='button' class='btn waves-effect waves-light red' onclick='startDelete(${subID})'><i
                        class='material-icons'>delete</i></button>
        </div>
    </li>
</script>
<script>
    // Item templating code taken from http://stackoverflow.com/a/39065147/1781706
    var itemTpl;
    function render(props) {
        return function (tok, i) {
            return (i % 2) ? props[tok] : tok;
        };
    }

    function setupEl(item) {
        $(item).find('span.title').show();
        $(item).find('div.edit').hide();
        $(item).find('div.edit input').on('blur', function (e) {
            cancelEdit($(e.target).parents("li").data("id"));
        }).on('keydown', function (e) {
            if ((e.keyCode || e.which) == 13) {
                doEdit($(e.target).parents("li").data("id"), e.target);
            }
        });
    }

    $(document).ready(function () {
        itemTpl = $('script[data-template="listItem"]').text().split(/\$\{(.+?)\}/g);
        setupEl(document);
        $('#sub-add-name').on('keydown', function (e) {
            if ((e.keyCode || e.which) == 13) {
                doAdd();
            }
        });
        $(".modal").modal();

        // Fix anchor links with the base href set -_-
        var pathname = window.location.href;
        $('a').each(function () {
            var link = $(this).attr('href');
            if (link && link.substr(0, 1) == "#") {
                $(this).attr('href', pathname + link);
            }
        });
    });
    function doAdd() {
//        var name = $("#sub-add-name").val();
//        var obj = {
//            'name': name,
//            'locationID': '<?php //echo $_REQUEST['id'];?>//'
//        };
//        $.ajax("api/locations/sub.add.php", {
//            method: 'POST',
//            data: obj
//        }).done(function (data) {
//            console.log("AJAX Resp", data);
//            if (data.status === "success") {
//                $("#sub-add-name").val("");
//                var newParams = {
//                    'name': name,
//                    'subID': data.id
//                };
//                var text = itemTpl.map(render(newParams)).join('');
//                var textEl = $(text);
//                $("#sublocs").append(textEl);
//                setupEl(textEl);
//            }
//            else {
//                console.error("Error on server", data);
//            }
//        }).fail(function (xhr, status, err) {
//            console.log("AJAX Error", xhr, status, err);
//        });
    }
    function startEdit(id) {
        var li = $("#sub-" + id);
        li.find(".title").hide();
        li.find(".edit").show();
    }
    function startDelete(id) {
        //Modal to confirm delete
        var li = $("#sub-" + id);
        var name = li.data("name");
        $("#deleteName").text(name);
        $("#delModal").data("id", id).modal("open");
    }
    function doDelete() {
//        var id = $("#delModal").data("id");
//        var obj = {
//            'subID': id
//        };
//        $.ajax("api/locations/sub.delete.php", {
//            method: 'POST',
//            data: obj
//        }).done(function (data) {
//            console.log("AJAX Resp", data);
//            if (data.status === "success") {
//                var li = $("#sub-" + id);
//                li.remove();
//            }
//            else {
//                console.error("Error on server", data);
//            }
//        }).fail(function (xhr, status, err) {
//            console.log("AJAX Error", xhr, status, err);
//        });
    }
    function cancelEdit(id) {
        var li = $("#sub-" + id);
        var title = li.find(".title");
        title.show();
        li.find(".edit").hide();
        li.find(".edit input").val(title.text());
    }
    function doEdit(id, el) {
//        var obj = {
//            'subID': id,
//            'name': $(el).val()
//        };
//        $.ajax("api/locations/sub.edit.php", {
//            method: 'POST',
//            data: obj
//        }).done(function (data) {
//            if (data.status === "success") {
//                var li = $("#sub-" + id);
//                li.find(".title").text($(el).val()).show();
//                li.find(".edit").hide();
//            }
//            else {
//                console.error("Error on server", data);
//            }
//        }).fail(function (xhr, status, err) {
//            console.log("AJAX Error", xhr, status, err);
//        });
    }
</script>
</body>
</html>