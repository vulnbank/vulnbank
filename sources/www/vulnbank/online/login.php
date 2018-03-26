<!DOCTYPE html>
<html>
<?php include("inc/head.php"); ?>
<body style="background-color:#c7a9f9">

<div class="main">
        <h1><img class="logo-icon" style="width:10%;height:10%" src="../assets/images/vb.svg" alt="icon"></h1>
     <div class="sap_tabs">
            <div id="login-horizontalTab" style="display: block; width: 100%; margin: 0px;">
              <ul class="resp-tabs-list">
                  <li id="login-loginlink" class="resp-tab-item" aria-controls="tab_item-0" role="tab"><span><i style="font-size:2em;" class="pe-7s-lock"></i><br/><?php echo(LOGIN_LOGIN); ?></span></li>
                  <li id="login-registerlink" class="resp-tab-item" aria-controls="tab_item-1" role="tab"><span><i style="font-size:2em;" class="pe-7s-note"></i><br/><?php echo(LOGIN_REGISTER); ?></span></li>
                  <li id="login-resetcodelink" class="resp-tab-item lost" aria-controls="tab_item-2" role="tab"><span><i style="font-size:2em;" class="pe-7s-key"></i><br/><?php echo(LOGIN_RESET); ?></span></li>
                  <div class="clear"></div>
              </ul>
            <div class="resp-tabs-container">
                <div id="login-logintab" class="tab-1 resp-tab-content" aria-labelledby="tab_item-0">
                    <div class="facts">
                        <div class="register">
                            <form id="login-loginform" class="sub" action="api.php" method="post">
                                <input type="text" placeholder="<?php echo(USER); ?>" id="login-login-username">
				<p></p>
                                <input type="password" placeholder="<?php echo(PASSWORD); ?>" id="login-login-password">
<?php
if (VB_OTP == 1) {
    echo('<p id="login-login-p" hidden></p>');
    echo("<input type=\"text\" hidden placeholder=". CODE ." id=\"login-login-code\">");
}
?>
                                <div class="sign-up">
                                    <input type="submit" id="login-login-submit" value="<?php echo(LOGIN_LOGIN); ?>" style="width:100%">
                                    <div class="clear"> </div>
                                </div>
                            </form>
                        </div>
                    </div>
                 </div>
             <div id="login-registertab" class="tab-2 resp-tab-content" aria-labelledby="tab_item-1">
                    <div class="facts">
                        <div class="register">
                            <form id="login-registerform" class="sub" action="api.php" method="post">
                                <input id="login-register-username" type="text" placeholder="<?php echo(USER); ?>">
				<p></p>
                                <input id="login-register-firstname" type="text" placeholder="<?php echo(FIRSTNAME); ?>">
				<p></p>
                                <input id="login-register-lastname" type="text" placeholder="<?php echo(LASTNAME); ?>">
				<p></p>
                                <input id="login-register-email" type="text" placeholder="E-Mail">
				<p></p>
                                <input id="login-register-birthdate" type="text" placeholder="<?php echo(BIRTHDATE); ?>">
				<p></p>
                                <input id="login-register-phone" type="text" placeholder="<?php echo(PHONE); ?>">
				<p></p>
                                <input id="login-register-password" type="password" placeholder="<?php echo(PASSWORD); ?>">
				<p></p>
                                <input id="login-register-confirmpassword" type="password" placeholder="<?php echo(PASSWORD_CONFIRM); ?>">
                                <input hidden id="login-register-account" type="text" placeholder="DE00000000000000000000">
                                <input hidden id="login-register-creditcard" type="text" placeholder="0000-0000-0000-0000">
                                <div class="sign-up">
                                    <input type="submit" id="login-register-submit" value="<?php echo(LOGIN_REGISTER); ?>" style="width:100%">
                                    <div class="clear"> </div>
                                </div>
                            </form>
                        </div>
                    </div>
            </div>
                 <div id="login-resettab" class="tab-3 resp-tab-content" aria-labelledby="tab_item-2 item3">
                    <div class="facts">
                        <div class="register">
                            <form id="login-resetpassform" class="sub" action="api.php" method="post">
                                <input type="text" placeholder="Username" id="login-reset-username">
                                <div class="sign-up">
                                <input id="login-reset-codebutton" type="submit" style="width:100%" value="<?php echo(CODE); ?>">
                                </div>
				<p></p>
                                <input type="text" placeholder="<?php echo(CODE); ?>" id="login-reset-code">
				<p></p>
                                <input type="password" placeholder="<?php echo(PASSWORD_NEW); ?>" id="login-reset-password">
				<p></p>
                                <input type="password" placeholder="<?php echo(PASSWORD_CONFIRM); ?>" id="login-reset-confirmpassword">
                                <div class="sign-up">
                                    <input type="submit" id="login-reset-submit" style="width:100%" value="<?php echo(LOGIN_RESET); ?>">
                                    <div class="clear"> </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
         </div>

</body>
</html>
