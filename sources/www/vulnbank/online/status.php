<?php include("inc/db.php"); ?>
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
                                <h4 class="title"><?php echo(SERVICE); ?></h4>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table id="history" class="table table-hover table-striped">
                                    <thead>
                                        <th><?php echo(PORT); ?></th>
                                        <th><?php echo(ACCESSIBILITY); ?></th>
                                        <th><?php echo(RESPONSE); ?></th>
                                        <th><?php echo(RESPONSE_TIME); ?></th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td id="port80">80</td>
                                            <td id="access80"></td>
                                            <td id="response80"></td>
                                            <td id="time80"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title"><?php echo(MENU_SYSTEM); ?></h4>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table id="history" class="table table-hover table-striped">
                                    <thead>
                                        <th/>
                                        <th><?php echo(USED); ?></th>
                                        <th><?php echo(FREE); ?></th>
                                        <th><?php echo(TOTAL); ?></th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td id="disk">Disk</td>
                                            <td id="diskused"></td>
                                            <td id="diskfree"></td>
                                            <td id="disktotal"></td>
                                        </tr>
                                        <tr>
                                            <td id="memory">Memory</td>
                                            <td id="memused"></td>
                                            <td id="memfree"></td>
                                            <td id="memtotal"></td>
                                        </tr>
                                        <tr>
                                            <td id="swpname">Swap</td>
                                            <td id="swpused"></td>
                                            <td id="swpfree"></td>
                                            <td id="swptotal"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
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
