<?php
    $db_server = "localhost";
    $db_username = "root";
    $db_password = "";
    $db_name = "vulnbank";
    $link = new mysqli($db_server, $db_username, $db_password);
    if(!$link) {
        echo(mysql_error());
        die("DB connection error: ".mysql_error());
    }
    $db_selected = mysqli_select_db($link, $db_name);
    if (!$db_selected) {
        mysqli_query($link, "CREATE DATABASE ".$db_name);
        mysqli_query($link, "CREATE TABLE IF NOT EXISTS ".$db_name.".settings (param_name varchar(255) NOT NULL, param_value varchar(255) DEFAULT NULL, param_type varchar(100) DEFAULT NULL, PRIMARY KEY (param_name))");
        mysqli_query($link, "CREATE TABLE IF NOT EXISTS ".$db_name.".transactions ( id mediumint(9) NOT NULL AUTO_INCREMENT, from_user varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL, to_user varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL, amount float DEFAULT NULL, timestamp datetime DEFAULT NULL, comment varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL, approved tinyint(1) DEFAULT NULL, PRIMARY KEY (id) )");
        mysqli_query($link, "CREATE TABLE IF NOT EXISTS ".$db_name.".users ( id mediumint(9) NOT NULL AUTO_INCREMENT, login varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL, firstname varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL, lastname varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL, email varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL, phone varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL, password varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL, account varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL, creditcard varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL, birthdate date DEFAULT NULL, lastvisit datetime DEFAULT NULL, amount float DEFAULT NULL, role varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL, code smallint(6) DEFAULT NULL, avatar varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL, about varchar(10000) COLLATE utf8mb4_unicode_ci DEFAULT NULL, otp int(11) DEFAULT NULL, PRIMARY KEY ( id ) )");
        mysqli_query($link, "INSERT INTO ".$db_name.".settings VALUES ('nexmo_api_key','0000000000','input'),('nexmo_api_secret','0000000000000000','input'),('sms_api','0','options'),('upload_path','uploads','input'),('vb_api','none','options'),('vb_otp','0','checkbox')");
        mysqli_query($link, "INSERT INTO ".$db_name.".users VALUES (1,'j.doe','John','Doe','j.doe@vulnbank.com','+15555555','5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8','DE12345123451234512345','5138-3266-5138-5315','1984-04-04','2017-06-02 11:07:04',760,'admin',845,'uploads/1_profile-1.png','Hi!',0),(2,'j.adams','Jack','Adams','j.adams@vulnbank.com','+14444444','5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8','DE00000111112222233333','4556-7491-4729-3700','1990-05-05','2017-04-29 13:36:55',940,'user',121,'uploads/2_profile-3.png',NULL,1)");
        mysqli_query($link, "INSERT INTO ".$db_name.".transactions VALUES (1,NULL,'DE12345123451234512345',500,'2016-10-10 10:01:17','Deposit',1),(2,'DE12345123451234512345','DE00000111112222233333',50,'2016-10-13 10:08:41','Thank you for buying me sandwich',1),(3,'DE12345123451234512345','DE00000111112222233333',35,'2016-10-25 12:15:05','I owed you some',1),(4,'DE12345123451234512345','DE00000111112222233333',100,'2016-10-13 14:12:52','Take that and return when you will have spare money',1),(5,'DE00000111112222233333','DE12345123451234512345',50,'2016-10-20 15:22:13','Partial return of my debt',1),(6,NULL,'DE00000111112222233333',300,'2016-10-17 12:44:04','Deposit',1),(7,'DE00000111112222233333','DE12345123451234512345',50,'2016-10-30 09:06:17','Thank you to borrow me money :)',1),(8,'DE12345123451234512345','DE00000111112222233333',100,'2016-10-31 16:27:16','Happy Halloween!',1),(9,'DE12345123451234512345','DE00000111112222233333',75,'2016-11-05 12:20:40','Thank you for that thing, you know',1),(10,NULL,'DE12345123451234512345',250,'2016-11-10 10:05:10','Deposit',1),(11,'DE00000111112222233333','DE12345123451234512345',50,'2016-11-17 15:16:37','As promised',1),(12,'DE12345123451234512345','DE00000111112222233333',35,'2016-11-25 13:31:27','You are welcome :)',1),(13,NULL,'DE12345123451234512345',150,'2016-12-15 13:23:20','Deposit',1),(14,'DE12345123451234512345','DE00000111112222233333',100,'2016-12-25 12:10:17','Merry Christmas!',1),(15,'DE00000111112222233333','DE12345123451234512345',75,'2016-12-25 17:14:43','Merry Christmas to you too, pal!',1),(16,'DE12345123451234512345','DE00000111112222233333',75,'2016-12-31 14:16:57','Happy New Year!',1),(17,'DE00000111112222233333','DE12345123451234512345',100,'2016-12-31 16:20:22','Wish you great New Year! :)',1),(18,NULL,'DE12345123451234512345',150,'2017-01-01 11:12:22','Deposit',1),(19,NULL,'DE00000111112222233333',250,'2017-01-05 21:17:02','Deposit',1),(20,'DE12345123451234512345',NULL,75,'2017-01-16 18:56:34','Withdraw',1),(21,'DE12345123451234512345','DE00000111112222233333',145,'2017-02-15 13:36:54','Wish you all the best',1),(22,NULL,'DE12345123451234512345',175,'2017-02-20 13:21:02','Deposit',1)");
    }

    $db = new PDO('mysql:host='. $db_server .';dbname='. $db_name, $db_username, $db_password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (session_id() == '') {
        session_start();
        if (!isset($_SESSION["language"])) {
            $_SESSION["language"] = "en";
        }
    }

    if (isset($_SERVER['HTTP_REFERER'])) {
        $ref = (string)basename($_SERVER['HTTP_REFERER']);
        preg_match("/index_(.*)\.html/",$ref,$match);
        switch ($ref) {
            case "index.html":
                $_SESSION["language"] = "en";
            default:
                if ($match && $match[1]) $_SESSION["language"] = $match[1];
                break;

        }
    }

    $sql = $db->prepare("SELECT * FROM settings");
    $sql-> execute();
    $rows = $sql->fetchAll();
    foreach ($rows as $row) {
        if (!defined(strtoupper($row["param_name"]))) {
            define(strtoupper($row["param_name"]), $row["param_value"]);
        }
    }
?>

