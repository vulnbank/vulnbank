    <div id="sidebar" class="sidebar" data-color="purple" data-image="../assets/images/sidebar.jpg">

    <!--   you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple" -->


    	<div class="sidebar-wrapper">
            <div class="logo">
                <a class="simple-text">
               <img class="logo-icon" src="../assets/images/vb.svg" alt="icon" style="width:45px">
                    VulnBank ltd.
                </a>
            </div>
            <ul class="nav">
                <li id="menu-portal"<?php if (basename($_SERVER["SCRIPT_FILENAME"]) == "portal.php") { echo(" class=\"active\"");}?>>
                    <a href="portal.php">
                        <i class="pe-7s-graph"></i>
                        <p><?php echo(MENU_STATISTICS); ?></p>
                    </a>
                </li>
                <li id="menu-transactions"<?php if (basename($_SERVER["SCRIPT_FILENAME"]) == "transactions.php") { echo(" class=\"active\"");}?>>
                    <a href="transactions.php">
                        <i class="pe-7s-cash"></i>
                        <p><?php echo(MENU_TRANSACTIONS); ?></p>
                    </a>
                </li>
                <li id="menu-history"<?php if (basename($_SERVER["SCRIPT_FILENAME"]) == "history.php") { echo(" class=\"active\"");}?>>
                    <a href="history.php">
                        <i class="pe-7s-note2"></i>
                        <p><?php echo(MENU_HISTORY); ?></p>
                    </a>
                </li>
                <?php if ($_SESSION["role"] == "admin") { ?>
                <li id="menu-users"<?php if (basename($_SERVER["SCRIPT_FILENAME"]) == "users.php") { echo(" class=\"active\"");}?>>
                    <a href="users.php">
                        <i class="pe-7s-users"></i>
                        <p><?php echo(MENU_USERS); ?></p>
                    </a>
                </li>
                <li id="menu-status"<?php if (basename($_SERVER["SCRIPT_FILENAME"]) == "status.php") { echo(" class=\"active\"");}?>>
                    <a href="status.php">
                        <i class="pe-7s-display1"></i>
                        <p><?php echo(MENU_SYSTEM); ?></p>
                    </a>
                </li>
                <li id="menu-status"<?php if (basename($_SERVER["SCRIPT_FILENAME"]) == "settings.php") { echo(" class=\"active\"");}?>>
                    <a href="settings.php">
                        <i class="pe-7s-tools"></i>
                        <p><?php echo(MENU_SETTINGS); ?></p>
                    </a>
                </li>
                <?php } ?>
            </ul>
    	</div>
    </div>

    <div class="main-panel">
            <nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a id="balance" class="navbar-brand"><?php echo(BALANCE . ": " . $_SESSION["amount"]);?></a>
                    </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                        <select id="language" class="selectpicker" data-width="fit">
                            <option data-content='<span class="flag-icon flag-icon-us"></span> English' <?php echo($_SESSION["language"] == "en" ? "selected=\"selected\"" : "" ) ?>>en</option>
                            <option  data-content='<span class="flag-icon flag-icon-ru"></span> Русский' <?php echo($_SESSION["language"] == "ru" ? "selected=\"selected\"" : "" ) ?>>ru</option>
                            <option  data-content='<span class="flag-icon flag-icon-kr"></span> 한국어' <?php echo($_SESSION["language"] == "kr" ? "selected=\"selected\"" : "" ) ?>>kr</option>
                        </select>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <p><?php echo(MENU_WELCOME . $_SESSION["firstname"] . " " . $_SESSION["lastname"]); ?>
                                <b class="caret"></b></p>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="userinfo.php"><?php echo(MENU_USERINFO); ?></a></li>
                                <li class="divider"></li>
                                <li><a href="logout.php"><?php echo(MENU_LOGOUT); ?></a></li>
                            </ul>
                        </li>
						<li class="separator hidden-lg hidden-md"></li>
                    </ul>
                </div>
            </div>
        </nav>
