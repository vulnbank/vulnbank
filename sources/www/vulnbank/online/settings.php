<!doctype html>
<html lang="en">
<?php include("inc/head.php"); ?>
<body>

<div class="wrapper">
<?php include("inc/menu.php"); ?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="header">
                                <h4 class="title"><?php echo(MENU_SETTINGS); ?></h4>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="table table-hover table-striped">
                                    <tbody>
<?php
$rows = sqlQuery("SELECT * FROM settings", NULL, "all");
foreach($rows as $row) {
    echo("<tr>");
    switch ($row["param_type"]) {
        case "input":
            echo("<td style=\"text-align:center;vertical-align:middle\">". constant("SETTINGS_" . strtoupper($row["param_name"])) ."</td>");
            echo("<td style=\"text-align:center;vertical-align:middle\"><input id=\"settings-{$row["param_name"]}\" class=\"form-control\" type=\"text\" value=\"{$row["param_value"]}\"></td>");
            break;
        case "checkbox":
            echo("<td style=\"text-align:center;vertical-align:middle\">". constant("SETTINGS_" . strtoupper($row["param_name"])) ."</td>");
            echo("<td style=\"text-align:center;vertical-align:middle\" id=\"settings-{$row["param_name"]}\"><input class=\"form-control\" type=\"checkbox\" " . ($row["param_value"] == 1 ? "checked" : NULL) . "></td>");
            break;
        case "options":
            echo("<td style=\"text-align:center;vertical-align:middle\">". constant("SETTINGS_" . strtoupper($row["param_name"])) ."</td>");
            echo("<td style=\"text-align:center;vertical-align:middle\"><select class=\"form-control\" id=\"settings-{$row["param_name"]}\">");
            switch ($row["param_name"]) {
                case "vb_api":
                    foreach (array("none", "rest", "xml") as $api) {
                        echo("<option name=\"".strtoupper($api) ." API\" data=\"{$api}\" ". ($row["param_value"] == $api ? " selected=\"selected\">" : ">")  . strtoupper($api) ." API</option>");
                    }
                    break;
                case "sms_api":
                    foreach (array("nexmo") as $api) {
                        echo("<option name=\"".strtoupper($api) ." API\" data=\"{$api}\" ". ($row["param_value"] == $api ? " selected=\"selected\">" : ">")  . strtoupper($api) ." API</option>");
                    }
                    break;
            }
            echo("</select></td>");
            break;
        case "api":
            break;
        case "upload_path":
            echo("<td style=\"text-align:center;vertical-align:middle\">Avatars upload path</td>");
            echo("<td style=\"text-align:center;vertical-align:middle\"><input id=\"settings-{$row["param_name"]}\" class=\"form-control\" type=\"text\" value=\"" . $row["param_value"] . "\"></td>");
            break;
        case "sms_api_key":
            echo("<td style=\"text-align:center;vertical-align:middle\">Nexmo SMS API key</td>");
            echo("<td style=\"text-align:center;vertical-align:middle\"><input id=\"settings-{$row["param_name"]}\" class=\"form-control\" type=\"text\" value=\"{$row["param_value"]}\"></td>");
            break;
        case "sms_api_secret":
            echo("<td style=\"text-align:center;vertical-align:middle\">Nexmo SMS API secret</td>");
            echo("<td style=\"text-align:center;vertical-align:middle\"><input id=\"settings-{$row["param_name"]}\" class=\"form-control\" type=\"text\" value=\"{$row["param_value"]}\"></td>");
            break;
    }
    echo("</tr>");
}?>
                                    </tbody>
                                </table>
                                <button id="settings-dbreset" class="btn btn-danger btn-fill btn-block"><?php echo(RESETDB); ?></button>
                            </div>
                        </div>

                </div>


            </div>
        </div>
<?php include("inc/footer.php"); ?>
    </div>
</div>
</body>
</html>
