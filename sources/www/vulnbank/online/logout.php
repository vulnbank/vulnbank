<?php
    if(isset($_SESSION["language"])) $language = $_SESSION["language"];
    session_unset();
    session_destroy();
    session_write_close();
    setcookie(session_name(),"",0,"/");
    if (session_id() == '') {
        session_start();
    } else {
        session_regenerate_id(true);
    }
    $_SESSION["language"] = $language;
    header("Location: login.php");
?>
