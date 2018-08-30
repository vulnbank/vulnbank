<?php
    include("inc/common.php");
    $baseurl = (string)basename($_SERVER["PHP_SELF"]);
    switch ($baseurl) {
        case "login.php":
            if (isset($_SESSION["login"])) {
                $url = ((isset($_GET["r"]) && $_GET["r"]) ? $_GET["r"] : "portal.php");
                header("Location: " . $url . "?" . $_SERVER['QUERY_STRING']);
                die("Redirecting to " . $url);
            }
            break;
        default:
            if (!in_array($baseurl, array("api.php", "forgot.php"))) {
                if (!isset($_SESSION["login"])) {
                    $_GET["r"] = $baseurl;
                    $url = "login.php?" . $_SERVER['QUERY_STRING'];
                    header("Location: " . $url);
                    die("Redirecting to " . $url);
                } else {
                    if (in_array("r",$_GET) && $_GET["r"]) {
                        $url = $_GET["r"];
                        unset($_GET["r"]);
                        $url .= implode("&", $_GET);
                        header("Location: " . $url);
                        die("Redirecting to " . $url);
                    }
                }
            }
            break;
    }
    if (isset($_SESSION["account"])) {
        $row = sqlQuery("SELECT * FROM users where account=?",array($_SESSION["account"]), "one");
        $_SESSION["amount"] = $row["amount"];
    }
?>
<head>
	<meta charset="utf-8" />
    <meta name="api" type="<?php echo(VB_API); ?>">
    <meta name="language" type="<?php echo($_SESSION["language"]); ?>">
    <meta name="currentUser" type="<?php echo(isset($_SESSION["login"]) ? $_SESSION["login"] : "none"); ?>">
	<link rel="icon" type="image/png" href="../assets/images/favicon.ico">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>VulnBank ltd.</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name="viewport" />
    <meta name="viewport" content="width=device-width" />
<?php
    if (in_array((string)basename($_SERVER["PHP_SELF"]), array("login.php", "forgot.php"))) {
        echo("<link href=\"../assets/css/login.css\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />");
    }
?>
    <!-- Bootstrap core CSS     -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="../assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Light Bootstrap Table core CSS    -->
    <link href="../assets/css/main.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" media="screen" href="../assets/css/bootstrap.min.css" />
    <link href="../assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link href="../assets/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="../assets/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/introjs.css" rel="stylesheet">


    <!--     Fonts and icons     -->
    <link href="../assets/css/font-awesome.min.css" rel="stylesheet">
    <link href='../assets/css/roboto.css' rel="stylesheet" type="text/css">
    <link href="../assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
    <link href="../assets/css/bootstrap-switch.css" rel="stylesheet" />
    <link href="../assets/css/bootstrap-select.min.css" rel="stylesheet" />
    <link href="../assets/css/flag-icon.min.css" rel="stylesheet" />

    <!--   Core JS Files   -->
    <script src="../assets/js/jquery-2.1.1.min.js" type="text/javascript"></script>
    <script src="../assets/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../assets/js/bootstrap-select.min.js" type="text/javascript"></script>
    <script src="../assets/js/moment-with-locales.js" type="text/javascript"></script>
    <script src="../assets/js/bootstrap-datetimepicker.js" type="text/javascript"></script>
    <script src="../assets/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="../assets/js/dataTables.bootstrap.min.js" type="text/javascript"></script>

    <!--  Checkbox, Radio & Switch Plugins -->
    <script src="../assets/js/bootstrap-checkbox-radio-switch.js"></script>

    <!--  Charts Plugin -->
    <script src="../assets/js/chartist.min.js"></script>

    <!--  Notifications Plugin    -->
    <script src="../assets/js/bootstrap-notify.js"></script>

    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
    <script src="../assets/js/light-bootstrap-dashboard.js"></script>
    <script src="../assets/js/easyResponsiveTabs.js" type="text/javascript"/></script>
    <script src="../assets/js/vbcommon.js" type="text/javascript"></script>
    <script src="../assets/js/vulnbank.js" type="text/javascript"></script>
    <?php if (isset($_GET["tour"])) {
        echo("<script src=\"../assets/js/tour.js\" type=\"text/javascript\"></script>");
        echo("<script src=\"../assets/js/intro.js\" type=\"text/javascript\"></script>");
    } ?>

</head>
