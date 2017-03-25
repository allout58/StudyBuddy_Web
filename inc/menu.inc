<base href="/~jthollo/4820/projects/asg3/"/>
<nav>
    <div class="nav-wrapper theme_bg">
        <a href="index.php" class="brand-logo">&nbsp;MyGarage</a>
        <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
        <ul id="nav-main" class="right hide-on-med-and-down">
            <?php if ($_SESSION['loggedIn']): ?>
                <li class="waves-effect waves-light"><a href="view.php">View Your Garage</a></li>
                <li class="waves-effect waves-light"><a href="logout.php">Logout</a></li>
            <?php endif; ?>
        </ul>
        <ul id="nav-mobile" class="side-nav">
            <?php if ($_SESSION['loggedIn']): ?>
                <li><a href="view.php">View Your Garage</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
