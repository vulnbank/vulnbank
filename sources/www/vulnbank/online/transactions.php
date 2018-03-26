<?php include("inc/db.php"); ?>
<!doctype html>
<html lang="en">
<?php include("inc/head.php"); ?>
<body>

<?php if (VB_OTP == 1) { include("otp.php"); } ?> 

<div class="wrapper">
<?php include("inc/menu.php"); ?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="header">
                                <h4 class="title"><?php echo(TRANSACTION) ?></h4>
                            </div>
                            <div class="content">
                                <form id="transactions-transaction" action="transactions.php" method="post">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><?php echo(TRANSACTION_ACCOUNT) ;?></label>
                                                <input id="transactions-sender" type="text" class="form-control" disabled value="<?php echo($_SESSION["account"]);?>">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><?php echo(TRANSACTION_RECIPIENT); ?></label>
                                                <input id="transactions-recipient" type="text" class="form-control" placeholder="DE00000000000000000000">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><?php echo(CREDITCARD); ?></label>
                                                <input id="transactions-creditcard" type="text" class="form-control" placeholder="1111-2222-3333-4444">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo(FIRSTNAME); ?></label>
                                                <input id="transactions-firstname" type="text" class="form-control" placeholder="<?php echo(FIRSTNAME); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo(LASTNAME); ?></label>
                                                <input id="transactions-lastname" type="text" class="form-control" placeholder="<?php echo(LASTNAME); ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label><?php echo(AMOUNT); ?>, $</label>
                                                <input id="transactions-amount" type="text" class="form-control" placeholder="100.00">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label><?php echo(COMMENT); ?></label>
                                                <textarea id="transactions-comment" rows="3" class="form-control" placeholder="<?php echo(COMMENT_DESCRIPTION); ?>"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <button id="transactions-submit" type="submit" class="btn btn-info btn-fill pull-right"><?php echo(SEND); ?></button>
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
