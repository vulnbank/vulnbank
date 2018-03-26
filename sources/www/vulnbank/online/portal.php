<!doctype html>
<html lang="en">
<?php include("inc/head.php"); ?>
<body>

<div class="wrapper">
<?php include("inc/menu.php"); ?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title"><?php echo(MENU_STATISTICS); ?></h4>
                                <p class="category"><?php echo(PORTAL_DURATION); ?></p>
                            </div>
                            <div class="content">
                                <div id="chartHours" class="ct-chart"></div>
                                <div class="footer">
                                    <div class="legend">
                                        <i class="fa fa-circle text-info"></i> <?php echo(INCOME); ?>
                                        <i class="fa fa-circle text-danger"></i> <?php echo(OUTCOME); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title"><?php echo(PORTAL_TRANSACTIONS); ?></h4>
                                <p class="category"><?php echo(PORTAL_TRANSACTIONS_DURATION); ?></p>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th><?php echo(STATE); ?></th>
                                        <th><?php echo(AMOUNT); ?></th>
                                        <th><?php echo(USER); ?></th>
                                        <th><?php echo(ACCOUNT); ?></th>
                                        <th><?php echo(CREDITCARD); ?></th>
                                        <th><?php echo(DATE); ?></th>
                                        <th><?php echo(COMMENT); ?></th>
                                    </thead>
                                    <tbody>
<?php
$rows = sqlQuery("SELECT *, (SELECT CONCAT(firstname, ' ', lastname) FROM users WHERE (account=from_user OR account=to_user) AND account!=?) AS name, (SELECT creditcard FROM users WHERE (account=from_user OR account=to_user) AND account!=?) AS creditcard FROM transactions WHERE from_user=? or to_user=? ORDER BY timestamp DESC LIMIT 10", 
    array($_SESSION["account"], $_SESSION["account"], $_SESSION["account"], $_SESSION["account"]), "all");
foreach ($rows as $row) {
    $status = ($_SESSION["account"] == $row["from_user"]);
    echo("<tr>\n");
    if ($row["approved"] == 1) {
        echo("<td><i style=\"font-size:2em;color:green\" class=\"pe-7s-check\"/></td>");
    } elseif ($row["approved"] == 2) {
        echo("<td><i style=\"font-size:2em;color:red;\" class=\"pe-7s-close-circle\"/></td>");
    } else {
        echo("<td><i style=\"font-size:2em;color:#f49242;\" class=\"pe-7s-clock\"/></td>");
    }
    echo("<td><p class=\"". ($status ? "text-danger\">-" : "text-success\">") . "{$row["amount"]}$</p></td>");
    echo("<td>{$row["name"]}</td>");
    if ($status) {
        echo("<td>". (empty($row["to_user"]) ? WITHDRAW : $row["to_user"]) ."</td>");
    } else {
        echo("<td>". (empty($row["from_user"]) ? DEPOSIT : $row["from_user"]) ."</td>");
    }
    echo("<td>{$row["creditcard"]}</td>");
    echo("<td>{$row["timestamp"]}</td>");
    echo("<td>{$row["comment"]}</td>");
    echo("</tr>");
}
?>
                                    </tbody>
                                </table>
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
