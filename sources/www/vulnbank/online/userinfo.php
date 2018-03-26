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
                    <div class="col-md-4">
                        <div id="userinfo-dropfile" class="card card-user">
                            <div class="image">
                                <img src="https://ununsplash.imgix.net/photo-1431578500526-4d9613015464?fit=crop&fm=jpg&h=300&q=75&w=400" alt="..."/>
                            </div>
                            <div class="content">
                                <div class="author">
                                <img id="userinfo-avatar" class="avatar border-gray" src="<?php
                                        echo((isset($_SESSION["avatar"]) && $_SESSION["avatar"] && file_exists($_SESSION["avatar"])) ? $_SESSION["avatar"] : "../assets/img/default-avatar.png");
                                                                                ?>" alt="..."/>
                                    <input id="userinfo-upload" name="upload_avatar" type="file" style="display:none;"/>
                                    <h4 class="title"><?php echo($_SESSION["firstname"]. " " .$_SESSION["lastname"]);?><br />
                                    <small><?php echo($_SESSION["login"]);?></small>
                                    </h4>
                                </div>
                                <p id="userinfo-description" class="description text-center"><?php
                                    echo(preg_replace("/\n/", "<br/>", $_SESSION["about"]));
                                ?></p>
                                <form id="userinfo-changepass" action="api.php" method="post">
                                    <input id="userinfo-oldpassword" type="password" class="form-control" placeholder="<?php echo(PASSWORD_OLD); ?>">
                                    <input id="userinfo-newpassword" type="password" class="form-control" placeholder="<?php echo(PASSWORD_NEW); ?>">
                                    <input id="userinfo-confirmpass" type="password" class="form-control" placeholder="<?php echo(PASSWORD_CONFIRM); ?>">
                                    <input type="submit" id="userinfo-submitButton" name="submitButton" class="btn btn-info btn-fill" style="width:100%" value="<?php echo(SEND); ?>">
                                </form>
                            </div>
                            <hr>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card">
                            <div class="header">
                                <h4 class="title"><?php echo(PROFILE_EDIT); ?></h4>
                            </div>
                            <div class="content">
                                <form action="about.php" method="post">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><?php echo(ACCOUNT); ?></label>
                                                <input id="userinfo-account" type="text" class="form-control" disabled value="<?php echo($_SESSION["account"]);?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><?php echo(CREDITCARD); ?></label>
                                                <input id="userinfo-creditcard" type="text" class="form-control" disabled value="<?php echo($_SESSION["creditcard"]);?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><?php echo(USER); ?></label>
                                                <input id="userinfo-login" type="text" class="form-control" disabled value="<?php echo($_SESSION["login"]);?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><?php echo(PHONE); ?></label>
                                                <input id="userinfo-phone" type="text" class="form-control" value="<?php echo($_SESSION["phone"]);?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><?php echo(FIRSTNAME); ?></label>
                                                <input id="userinfo-firstname" type="text" class="form-control" value="<?php echo($_SESSION["firstname"]);?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><?php echo(LASTNAME); ?></label>
                                                <input id="userinfo-lastname" type="text" class="form-control" value="<?php echo($_SESSION["lastname"]);?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>E-mail</label>
                                                <input id="userinfo-email" type="text" class="form-control" value="<?php echo($_SESSION["email"]);?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><?php echo(BIRTHDATE); ?></label>
                                                <input id="userinfo-birthdate" type="text" class="form-control" value="<?php echo($_SESSION["birthdate"]);?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label><?php echo(ABOUT); ?></label>
                                                <textarea id="userinfo-about" rows="3" class="form-control"><?php echo($_SESSION["about"]);?></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <button id="userinfo-userupdate" type="submit" class="btn btn-info btn-fill pull-right"><?php echo(UPDATE); ?></button>
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
