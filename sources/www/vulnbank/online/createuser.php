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
                    <div class="col-md-8">
                        <div class="card">
                            <div class="header">
                                <h4 class="title"><?php echo(LOGIN_REGISTER); ?></h4>
                            </div>
                            <div class="content">
                                <form id="createuser-createuser" action="api.php" method="post">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><?php echo(USER); ?></label>
                                                <input id="createuser-username" type="text" class="form-control" placeholder="<?php echo(USER); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><?php echo(FIRSTNAME); ?></label>
                                                <input id="createuser-firstname" type="text" class="form-control" placeholder="<?php echo(FIRSTNAME); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><?php echo(LASTNAME); ?></label>
                                                <input id="createuser-lastname" type="text" class="form-control" placeholder="<?php echo(LASTNAME); ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>E-Mail</label>
                                                <input id="createuser-email" type="text" class="form-control" placeholder="E-Mail">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><?php echo(ACCOUNT); ?></label>
                                                <input id="createuser-account" type="text" disabled class="form-control" placeholder="DE00000000000000000000">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><?php echo(CREDITCARD); ?></label>
                                                <input id="createuser-creditcard" type="text" disabled class="form-control" placeholder="0000-0000-0000-0000">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><?php echo(BIRTHDATE); ?></label>
                                                <input id="createuser-birthdate" type="text" class="form-control" placeholder="Birth Date">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><?php echo(PHONE); ?></label>
                                                <input id="createuser-phone" type="text" class="form-control" placeholder="79995554433">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><?php echo(PASSWORD); ?></label>
                                                <input id="createuser-password" type="password" class="form-control" placeholder="<?php echo(PASSWORD); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><?php echo(PASSWORD_CONFIRM); ?></label>
                                                <input id="createuser-confirmpassword" type="password" class="form-control" placeholder="<?php echo(PASSWORD_CONFIRM); ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-info btn-fill pull-right"><?php echo(CREATE_USER); ?></button>
                                    <div class="clearfix"></div>
                                </form>
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
