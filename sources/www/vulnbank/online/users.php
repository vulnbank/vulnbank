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
                            <button id="users-createuser" class="btn btn-info btn-fill"><?php echo(CREATE_USER); ?></button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Users</h4>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th style="text-align:center;vertical-align:middle">ID</th>
                                        <th style="text-align:center;vertical-align:middle"><?php echo(USER); ?></th>
                                        <th style="text-align:center;vertical-align:middle"><?php echo(FIRSTNAME); ?></th>
                                        <th style="text-align:center;vertical-align:middle"><?php echo(LASTNAME); ?></th>
                                        <th style="text-align:center;vertical-align:middle">E-mail</th>
                                        <th style="text-align:center;vertical-align:middle"><?php echo(ACCOUNT); ?></th>
                                        <th style="text-align:center;vertical-align:middle"><?php echo(CREDITCARD); ?></th>
                                        <th style="text-align:center;vertical-align:middle"><?php echo(BIRTHDATE); ?></th>
                                        <th style="text-align:center;vertical-align:middle"><?php echo(BALANCE); ?></th>
                                        <th style="text-align:center;vertical-align:middle"><?php echo(ROLE); ?></th>
                                        <th style="text-align:center;vertical-align:middle">OTP</th>
                                        </th>
                                    </thead>
                                    <tbody>
<?php
$rows = sqlQuery("SELECT * FROM users", NULL, "all");
foreach($rows as $row) {
    echo("<tr>");
    echo("<td style=\"text-align:center;vertical-align:middle\">{$row["id"]}</td>");
    echo("<td style=\"text-align:center;vertical-align:middle\">{$row["login"]}</td>");
    echo("<td style=\"text-align:center;vertical-align:middle\">{$row["firstname"]}</td>");
    echo("<td style=\"text-align:center;vertical-align:middle\">{$row["lastname"]}</td>");
    echo("<td style=\"text-align:center;vertical-align:middle\">{$row["email"]}</td>");
    echo("<td style=\"text-align:center;vertical-align:middle\">{$row["account"]}</td>");
    echo("<td style=\"text-align:center;vertical-align:middle\">{$row["creditcard"]}</td>");
    echo("<td style=\"text-align:center;vertical-align:middle\">{$row["birthdate"]}</td>");
    echo("<td style=\"text-align:center;vertical-align:middle\"><input id=\"users-amount{$row["id"]}\" lineid=\"users-{$row["id"]}\" class=\"form-control\" type=\"number\" value=\"{$row["amount"]}\"></td>");
    echo("<td style=\"text-align:center;vertical-align:middle\"><select class=\"form-control\" lineid=\"users-{$row["id"]}\" id=\"users-roleselect{$row["id"]}\">");
    echo('<option'. ($row["role"] == "admin" ? ' selected="selected">' : '>')  .'admin</option>');
    echo('<option'. ($row["role"] == "user" ? ' selected="selected">' : '>')  .'user</option>');
    echo("</select></td>");
    echo("<td style=\"text-align:center;vertical-align:middle\" lineid=\"users-{$row["id"]}\" id=\"users-otp{$row["id"]}\"><input class=\"form-control\" type=\"checkbox\" " . ($row["otp"] == 1 ? "checked" : NULL) . "></td>");
    echo("<td style=\"vertical-align:middle\"><button id=\"users-deleteuser{$row["id"]}\" lineid=\"users-{$row["id"]}\" class=\"btn btn-danger btn-fill btn-simple btn-xs\" rel=\"tooltip\" type=\"button\" data-original-title=\"Delete\"><i class=\"fa fa-times\"></button></td>");
    echo("</tr>");
}?>
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
