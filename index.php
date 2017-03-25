<?php
require_once "inc/login.inc";
require_once "inc/mysql.inc";
if ($_SESSION['loggedIn']) {
    // Go to the view page if we
    header("Location: view.php");
    die();
}
if (isset($_POST['username'])) {
    $uname = $_POST['username'];
    $passwd = $_POST['password'];
    $userID = "";
    $prep = $dbo->prepare("SELECT userID FROM Users WHERE uname=:user AND password=SHA2(:pwd, 256)");
    $prep->bindParam(":user", $uname);
    $prep->bindParam(":pwd", $passwd);
    $prep->bindColumn(1, $userID);
    $prep->execute();
    $prep->fetch();
    if ($prep->rowCount() == 1) {
        $_SESSION['loggedIn'] = true;
        $_SESSION['username'] = $uname;
        $_SESSION['userid'] = $userID;
        header("Location: view.php");
    } else {
        $errors = "Invalid username or password.";
    }
}
?>
<html>
<head>
    <title>MyGarage - Login</title>
    <?php require_once "inc/css.inc"; ?>
</head>
<body>
<?php require_once "inc/menu.inc"; ?>
<div class="container">
    <h3>Login</h3>
    <p>Login to access your garage!</p>
    <form method="post">
        <?php if (isset($errors)): ?>
            <p class="error"><?php echo $errors; ?></p>
        <?php endif; ?>
        <div class="input-field">
            <input id="username" name="username" type="text" class="validate"
                   required <?php if (isset($_POST['username'])) echo "value='{$_POST['username']}'"; ?>>
            <label for="username">Username</label>
        </div>
        <div class="input-field">
            <input id="password" name="password" type="password" class="validate" required>
            <label for="password">Password</label>
        </div>
        <button class="btn waves-effect waves-light" type="submit" name="action">Submit
            <i class="material-icons right">send</i>
        </button>
    </form>
</div>
<?php require_once "inc/js.inc"; ?>
</body>
</html>