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
                            <button id="history-removefailed" class="btn btn-danger btn-fill"><?php echo(HISTORY_FAILED); ?></button>
                            <button id="history-canceloperations" class="btn btn-warning btn-fill"><?php echo(HISTORY_CANCEL); ?></button>
                        </div>
                    </div>
                </div>

                <div class="row" id="history-searchdiv">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title"><?php echo(MENU_HISTORY); ?></h4>
                                <p id="history-searchinfo" class="category"/>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table id="history-history" class="table table-hover table-striped">
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
$rows = sqlQuery("SELECT *, (SELECT CONCAT(firstname, ' ', lastname) FROM users WHERE (account=from_user OR account=to_user) AND account!=?) AS name, (SELECT creditcard FROM users users WHERE (account=from_user OR account=to_user) AND account!=?) AS creditcard FROM transactions WHERE from_user=? or to_user=? ORDER BY timestamp DESC",
    array($_SESSION["account"], $_SESSION["account"], $_SESSION["account"], $_SESSION["account"]), "all");
foreach ($rows as $row) {
    $status = ($_SESSION["account"] == $row["from_user"]);
    echo("<tr>\n");
    if ($row["approved"] == 1) {
        echo("<td><i style=\"font-size:2em;color:green\" class=\"pe-7s-check\"/></td>");
    } elseif ($row["approved"] == 2) {
        echo("<td><i style=\"font-size:2em;color:red;\" class=\"pe-7s-close-circle\"/></td>");
    } elseif ($row["approved"] == 3) {
        echo("<td><i style=\"font-size:2em;color:#f49242;\" class=\"pe-7s-back\"/></td>");
    } else {
        echo("<td><i style=\"font-size:2em;color:#f49242;\" class=\"pe-7s-clock\"/></td>");
    }
    echo("<td><p class=\"". ($status == 1 ? "text-danger\">-" : "text-success\">") . "{$row["amount"]}$</p></td>");
    echo("<td>". $row["name"] ."</td>");
    if ($status) {
        echo("<td>". (empty($row["to_user"]) ? "Withdraw" : $row["to_user"]) ."</td>");
    } else {
        echo("<td>". (empty($row["from_user"]) ? "Deposit" : $row["from_user"]) ."</td>");
    }
    echo("<td>{$row["creditcard"]}</td>");
    echo("<td>{$row["timestamp"]}</td>");
    echo("<td>{$row["comment"]}</td>");
    echo("</tr>\n");
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
