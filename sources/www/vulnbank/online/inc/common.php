<?php
include("db.php");
include("locale/{$_SESSION["language"]}.php");;
error_reporting(E_ALL);
ini_set('display_errors', 1);

function sqlQuery($query, $params, $action) {
    include("db.php");
    $sql = $db->prepare($query);
    try {
        if (is_null($params)) {
            $sql->execute();
        } else {
            $sql->execute($params);
        }
    } catch(PDOException $exception) { 
        responseSend(FALSE, $exception->getMessage(), "attention", NULL); 
    }
    switch ($action) {
        case "one":
            return $sql->fetch();
            break;
        case "all":
            return $sql->fetchAll();
            break;
        case "count":
            return $sql->rowCount();
            break;
        case "id":
            return $db->lastInsertId();
            break;
        default:
            return;
            break;
    }
}

function responseSend($success, $message, $icon, $params) {
    header('Content-Type: application/json');
    if (is_null($params)) $params = array();
    $result = array("status" => ($success ? "success" : "error"),
                    "message" => $message,
                    "icon" => "pe-7s-{$icon}");
    if (!$success) header(':', true, 400);
    die(json_encode(array_merge($result, $params)));

}

function otpCheck($param) {
    $row = sqlQuery("SELECT otp FROM users WHERE login=? OR account=?",
                array($param, $param), "one");
    $otp = $row["otp"];
    return (int)((bool)VB_OTP && (bool)$otp);
}

function is_admin() {
    if ($_SESSION["role"] != "admin") responseSend(FALSE, MSG_ACCESS_DENIED, "user", NULL);
}

function validate($pattern, $variable, $name) {
    if ($name == "Email") {
        $test = filter_var($variable, FILTER_VALIDATE_EMAIL);
    } elseif ($name == "Sender") {
        $test = preg_match($pattern, $variable) && ($_SESSION["account"] == $variable);
    } else {
        $test = preg_match($pattern, $variable);
    }

    if (!$test && $variable) {
        responseSend(FALSE, sprintf(MSG_VALID_PARAM_FAIL, $name), "user",
            array("variable" => $variable));
    }
}

function userLogin($login, $password, $code) {
    $user = sqlQuery("SELECT * FROM users WHERE login=? AND password=?",
                    array($login, hash("sha256", $password, false)), "one");
    if($user) {
        if (FALSE && otpCheck($login) && $code != $user["code"]) responseSend(FALSE, MSG_LOGIN_FAILED, "key", NULL);
        session_regenerate_id(true);
        foreach($user as $key => $value) {
            if (!is_int($key)) $_SESSION[$key] = $value;
        }
        $_SESSION["token"] = sha1(uniqid(mt_rand(0,100000)));
        sqlQuery("UPDATE users SET lastvisit=CURRENT_TIMESTAMP() WHERE id=?", array($user["id"]), NULL);
        responseSend(TRUE, MSG_LOGIN_SUCCESS, "key", NULL);
    } else {
        responseSend(FALSE, MSG_LOGIN_FAILED, "key", NULL);
    }
}

function userCreate($login, $account, $firstname, $lastname, $password, $email, $phone, $birthdate, $creditcard) {
    $count = sqlQuery("SELECT * FROM users WHERE login=? OR account=?", array($login, $account), "count");
    if ($count > 0) responseSend(FALSE, MSG_USER_EXISTS, "user", NULL);
    sqlQuery("INSERT INTO users (login, firstname, lastname, email, phone, password, account, creditcard, birthdate, lastvisit, amount, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, STR_TO_DATE(?, \"%d-%m-%Y\"), CURRENT_TIMESTAMP(), 100, \"user\")",
              array($login, $firstname, $lastname, $email, $phone, hash("sha256",$password), $account, $creditcard, $birthdate), NULL);
    sqlQuery("INSERT INTO transactions (from_user,to_user,amount,timestamp,comment,approved) VALUES (NULL,?,100,CURRENT_TIMESTAMP(),'Deposit',1)",
              array($account), NULL);
    responseSend(TRUE, sprintf(MSG_USER_ADD_SUCCESS, $login), "user", NULL);
}

function userUpdate($id, $fields) {
    $user = sqlQuery("SELECT * FROM users WHERE id=?", array($id), "one");
    foreach ($fields as $key => $value) {
        $user[$key] = $value;
    }
    sqlQuery("UPDATE users SET login=?, firstname=?, lastname=?, email=?, phone=?, password=?, account=?, creditcard=?, birthdate=?, lastvisit=CURRENT_TIMESTAMP(), amount=?, role=?, code=?, avatar=?, about=?, otp=? WHERE id=?",
        array($user["login"], $user["firstname"], $user["lastname"], $user["email"],
              $user["phone"], $user["password"], $user["account"], $user["creditcard"],
              $user["birthdate"], $user["amount"], $user["role"], $user["code"],
              $user["avatar"], $user["about"], (int)$user["otp"], $user["id"]), NULL);

    if ($user["amount"] != $_SESSION["amount"] && (string)basename($_SERVER['HTTP_REFERER']) == "users.php") {
        $difference = $user["amount"] - $_SESSION["amount"];
        $query = "INSERT INTO transactions (from_user,to_user,amount,timestamp,comment,approved) VALUES ((SELECT account FROM users WHERE id=?),(SELECT account FROM users WHERE id=?),?,CURRENT_TIMESTAMP(),?,?)";
        if ($difference < 0) {
            sqlQuery($query, array(-1, $user["id"], abs($difference), "Deposit", 1), NULL);
        } else {
            sqlQuery($query, array($user["id"], -1, abs($difference), "Withdraw", 1), NULL);
        }
    }
    if ($_SESSION["id"] == $id) {
        foreach ($user as $key => $value) {
            if (in_array($key, $_SESSION)) $_SESSION["key"] = $value;
        }
        responseSend(TRUE, MSG_USER_UPDATE_SELF_SUCCESS, "user", array("balance" => $_SESSION["amount"]));
    } else {
        responseSend(TRUE, sprintf(MSG_USER_UPDATE_SELF_SUCCESS, $user["login"]), "user", array("balance" => $_SESSION["amount"]));
    }
}

function userDelete($id) {
    sqlQuery("DELETE FROM users WHERE id=?", array($id), NULL);
    responseSend(TRUE, MSG_USER_REMOVE_SUCCESS, "user", NULL);
}

function userPasswordForgot($login, $code, $password) {
    if (otpCheck($login)) {
        $count =sqlQuery("SELECT * FROM users WHERE login=? AND code=?",
                          array($login, $code), "count");
        if($count != 1) responseSend(FALSE, MSG_CODE_INVALID, "key", NULL);
    }
    sqlQuery("UPDATE users SET password=? WHERE login=?",
              array(hash("sha256", $password, false), $login), NULL);
    responseSend(TRUE, MSG_PASS_UPDATE_SUCCESS, "key", NULL);
}

function userPasswordChange($oldpassword, $newpassword) {
    $count = sqlQuery("SELECT * FROM users WHERE id=? and password=?",
                       array($_SESSION["id"],hash("sha256", $oldpassword, false)), "count", NULL);
    if ($count == 1) {
        sqlQuery("UPDATE users SET password=? WHERE id=?",
                  array(hash("sha256", $newpassword, false), $_SESSION["id"]), NULL);
        responseSend(TRUE, MSG_PASS_UPDATE_SUCCESS, "key", NULL);
    } else {
        responseSend(FALSE, MSG_PASS_CHECK_FAIL, "key", NULL);
    }
}

function userCheck($account, $firstname, $lastname, $creditcard) {
    include("inc/db.php");
    $query = "SELECT * FROM users WHERE ";
    $array = array("account", "firstname", "lastname", "creditcard");
    foreach ($array as $name) {
        if (isset($$name) && !empty($$name)) {
            if (!preg_match('/WHERE $/',$query)) $query .= " AND";
            $query .= " {$name}='{$$name}'";
        }
    }
    $db_reply = $link->query($query);
    if (!$db_reply) responseSend(FALSE, $link->error, "user", NULL);
    $row = $db_reply->fetch_object();
    if ($row and $db_reply->num_rows == 1 ) {
        responseSend(TRUE, MSG_VALID_SUCCESS, "user", array(
            "firstname" => $row->firstname,
            "lastname" => $row->lastname,
            "recipient" => $row->account,
            "creditcard" => $row->creditcard));
    } else {
        responseSend(FALSE, MSG_VALID_FAIL, "user", NULL); 
    }
}

function codeSend($provider, $phone, $message) {
    switch ($provider) {
        case "nexmo":
            $url = 'https://rest.nexmo.com/sms/json?' . http_build_query(
                array('api_key' => NEXMO_API_KEY,
                'api_secret' => NEXMO_API_SECRET,
                "to" => $phone,
                "from" => 900,
                "text" => $message));
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            break;
    }
}

function codeGenerate($login) {
    $row = sqlQuery("SELECT * FROM users WHERE login=?", array($login), "one");
    if ($row) {
        $code = rand(100,999);
        sqlQuery("UPDATE users SET code=? WHERE login=?", array($code, $login), NULL);
        if (otpCheck($login)) {
            codeSend(SMS_API, $row["phone"], "OTP code: {$code}");
            responseSend(TRUE, MSG_CODE_SENT, "key", NULL);
        } else {
            responseSend(TRUE, MSG_CODE_CHANGED, "key", NULL);
        }
    } else {
        responseSend(FALSE, sprintf(MSG_USER_FIND_FAIL, $login), "user", NULL);
    }
}

function transactionSend($sender, $recipient, $amount, $comment) {
    if (empty($amount)) responseSend(FALSE, sprintf(MSG_VALID_PARAM_FAIL, "amount"), "user", array("variable" => ""));
    if ($_SESSION["amount"] < $amount) responseSend(FALSE, MSG_TRANSACTION_LOWMONEY, "cash", NULL);
    if ($_SESSION["account"] == $recipient) responseSend(FALSE, MSG_TRANSACTION_YOURSELF, "cash", NULL);
    $id = sqlQuery("INSERT INTO transactions (from_user,to_user,amount,timestamp,comment,approved) VALUES (?,?,?,CURRENT_TIMESTAMP(),?,0)",
                    array($sender, $recipient, $amount, $comment), "id");
    $_SESSION["amount"] = $_SESSION["amount"] - $amount;
    sqlQuery("UPDATE users SET amount=? WHERE account=?", array($_SESSION["amount"], $sender), NULL);
    if (otpCheck($sender)) {
        $user = sqlQuery("SELECT * FROM users WHERE account=?", array($sender), "one");
        codeGenerate($user["login"]);
        responseSend(TRUE, MSG_CODE_SENT, "key", array("id" => $id));
    } else {
        $user = sqlQuery("SELECT * FROM users WHERE account=?", array($recipient), "one");
        echo(json_encode(array("status" => "success",
                               "message" => sprintf(MSG_TRANSACTION_SUCCESS, $amount, $user["login"]),
                               "balance" => $_SESSION["amount"],
                               "icon" => "pe-7s-cash")));
        transactionVerify($id, "none");
    }
}

function transactionVerify($id, $code) {
    if ($code == "none") {
        fastcgi_finish_request();
        popen("php inc/approve.php {$id} >/dev/null &", "r");
    } else {
        $transaction = sqlQuery("SELECT * FROM transactions WHERE id=?", array($id), "one");
        if ($transaction) {
            $user = sqlQuery("SELECT * FROM users WHERE account=?", array($transaction["from_user"]), "one");
            if($user && $user["code"] == $code) {
                echo(json_encode(array("status" => "success",
                                      "message" => sprintf(MSG_TRANSACTION_SUCCESS, $transaction["amount"], $transaction["to_user"]),
                                      "balance" => $_SESSION["amount"],
                                      "icon" => "pe-7s-cash")));
                fastcgi_finish_request();
                popen("php inc/approve.php {$id} >/dev/null &", "r");
            } else {
                transactionUpdate($id, array("approved" => 3));
                responseSend(FALSE, MSG_TRANSACTION_VERIFY_FAIL, "cash", NULL);
            }
        } else {
            responseSend(FALSE, MSG_TRANSACTION_FIND_FAIL, "cash", NULL);
        }
    }
}

function transactionRemoveFailed () {
    sqlQuery("DELETE FROM transactions WHERE approved=2 AND from_user=?", array($_SESSION["account"]), NULL);
    responseSend(TRUE, MSG_TRANSACTION_REMOVEFAILED, "cash", NULL);
}

function transactionCancel () {
    $rows = sqlQuery("SELECT * FROM transactions WHERE approved=0 AND from_user=?", array($_SESSION["account"]), "all");
    foreach ($rows as $row) {
        $_SESSION["amount"] += $row["amount"];
        sqlQuery("UPDATE users SET amount=? WHERE account=?", array($_SESSION["amount"], $_SESSION["account"]), NULL);
        sqlQuery("UPDATE transactions SET approved=3 WHERE id=?", array($row["id"]), NULL);
    }
    responseSend(TRUE, sprintf(MSG_TRANSACTION_CANCELED, count($rows)), "cash", NULL);
}

function transactionApprove($id) {
    sqlQuery("UPDATE transactions SET approved=2 WHERE id=?", array($id), NULL);
    responseSend(TRUE, sprintf(MSG_TRANSACTION_APPROVED, $id), "cash", NULL);
}

function transactionUpdate($id, $fields) {
    $transaction = sqlQuery("SELECT * FROM transactions WHERE id=?", array($id), "one");
    foreach ($fields as $key => $value) {
        $transaction[$key] = $value;
    }
    sqlQuery("UPDATE transactions SET from_user=?, to_user=?, amount=?, timestamp=?, comment=?, approved=? WHERE id=?",
        array($transaction["from_user"], $transaction["to_user"], $transaction["amount"], $transaction["timestamp"],
        $transaction["comment"], $transaction["approved"], $transaction["id"]), NULL);
}

function graphGetData() {
    $rows = sqlQuery("SELECT DATE_FORMAT(timestamp,'%m') AS month, SUM(CASE WHEN from_user=? THEN amount ELSE 0 END) AS from_sum, SUM(CASE WHEN to_user=? THEN amount ELSE 0 END) AS to_sum FROM transactions GROUP BY DATE_FORMAT(timestamp,'%Y-%m') LIMIT 10", array($_SESSION["account"],$_SESSION["account"]), "all");
    $keys = array_keys($rows);
    $last_key = end($keys);
    $months = array();
    $to_sum = array();
    $from_sum = array();
    foreach ($rows as $key => $row) {
        $monthName = DateTime::createFromFormat('!m',$row["month"])->format("M");
        array_push($months, $monthName);
        array_push($to_sum, $row["to_sum"]);
        array_push($from_sum, $row["from_sum"]);
    }
    responseSend(TRUE, "Graph data", "note2",
        array("months" => $months, "recieved" => $to_sum, "sent" => $from_sum));
}

function serviceState($proto, $host, $port) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "{$proto}://{$host}:{$port}");
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $rt = curl_exec($ch);
    $info = curl_getinfo($ch);
    $disk = shell_exec("df -h --total | grep total");
    $disk_parts = preg_split('/\s+/', $disk);
    $memory = shell_exec("free -m | grep Mem");
    $memory_parts = preg_split('/\s+/', $memory);
    $swap = shell_exec("free -m | grep Swap");
    $swap_parts = preg_split('/\s+/', $swap);
    header('Content-Type: application/json');
    if ($info["http_code"] == "200" or $info["http_code"] == "301") {
    die(json_encode(
            array("code" => $info["http_code"],
                  "time" => $info["total_time"],
                  "disk" => array("total" => $disk_parts[1],
                  "free" => $disk_parts[3],
                  "used" => $disk_parts[2]),
                  "memory" => array("total" => $memory_parts[1],
                  "free" => $memory_parts[3],
                  "used" => $memory_parts[2]),
                  "swap" => array("total" => $swap_parts[1],
                  "free" => $swap_parts[3],
                  "used" => $swap_parts[2]))));
    } else {
            die(json_encode(array("code" => $info["http_code"],
                  "time" => $info["total_time"],
                  "disk" => array("total" => $disk_parts[1],
                  "free" => $disk_parts[3],
                  "used" => $disk_parts[2]),
                  "memory" => array("total" => $memory_parts[1],
                  "free" => $memory_parts[3],
                  "used" => $memory_parts[2]),
                  "swap" => array("total" => $swap_parts[1],
                  "free" => $swap_parts[3],
                  "used" => $swap_parts[2]))));
    }
}

function fileUpload() {
    $patterns = array(".php", ".php5", ".phtml");
    $match = 0;
    foreach ($patterns as $pattern)  {
        $match += (strpos($_FILES["upload_avatar"]["name"], $pattern) != 0 ) ? 1 : 0;
    }
    if ($match > 0) responseSend(FALSE, MSG_IMAGE_UPLOAD_EXT_FAILED, "tools", NULL);
    $path = UPLOAD_PATH;
    $target_file = "{$path}/{$_SESSION["id"]}_{$_FILES["upload_avatar"]["name"]}";
    $_SESSION["avatar"] = $target_file;
    move_uploaded_file($_FILES["upload_avatar"]["tmp_name"], $target_file);
    sqlQuery("UPDATE users SET avatar=? WHERE id=?", array($target_file, $_SESSION["id"]), NULL);
    $response = popen("/usr/local/bin/convert {$target_file} -resize 100x100 {$target_file}", "r");
    responseSend(TRUE, MSG_IMAGE_UPLOAD_SUCCESS, "photo", array("source" => $_SESSION["avatar"]));

}

function dbReset() {
    sqlQuery("DELETE FROM transactions", NULL, NULL);
    sqlQuery("DELETE FROM users", NULL, NULL);
    sqlQuery("INSERT INTO users VALUES (1,'j.doe','John','Doe','j.doe@vulnbank.de','+15555555','5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8','DE12345123451234512345','5138-3266-5138-5315','1984-04-04','2017-06-02 11:07:04',760,'admin',845,'uploads/1_profile-1.png','Hi!',0),(2,'j.adams','Jack','Adams','j.adams@somecompany.de','+14444444','5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8','DE00000111112222233333','4556-7491-4729-3700','1990-05-05','2017-04-29 13:36:55',940,'user',121,'uploads/2_profile-3.png',NULL,1);", NULL, NULL);
    sqlQuery("INSERT INTO transactions VALUES (1,NULL,'DE12345123451234512345',500,'2016-10-10 10:01:17','Deposit',1),(2,'DE12345123451234512345','DE00000111112222233333',50,'2016-10-13 10:08:41','Thank you for buying me sandwich',1),(3,'DE12345123451234512345','DE00000111112222233333',35,'2016-10-25 12:15:05','I owed you some',1),(4,'DE12345123451234512345','DE00000111112222233333',100,'2016-10-13 14:12:52','Take that and return when you will have spare money',1),(5,'DE00000111112222233333','DE12345123451234512345',50,'2016-10-20 15:22:13','Partial return of my debt',1),(6,NULL,'DE00000111112222233333',300,'2016-10-17 12:44:04','Deposit',1),(7,'DE00000111112222233333','DE12345123451234512345',50,'2016-10-30 09:06:17','Thank you to borrow me money :)',1),(8,'DE12345123451234512345','DE00000111112222233333',100,'2016-10-31 16:27:16','Happy Halloween!',1),(9,'DE12345123451234512345','DE00000111112222233333',75,'2016-11-05 12:20:40','Thank you for that thing, you know',1),(10,NULL,'DE12345123451234512345',250,'2016-11-10 10:05:10','Deposit',1),(11,'DE00000111112222233333','DE12345123451234512345',50,'2016-11-17 15:16:37','As promised',1),(12,'DE12345123451234512345','DE00000111112222233333',35,'2016-11-25 13:31:27','You are welcome :)',1),(13,NULL,'DE12345123451234512345',150,'2016-12-15 13:23:20','Deposit',1),(14,'DE12345123451234512345','DE00000111112222233333',100,'2016-12-25 12:10:17','Merry Christmas!',1),(15,'DE00000111112222233333','DE12345123451234512345',75,'2016-12-25 17:14:43','Merry Christmas to you too, pal!',1),(16,'DE12345123451234512345','DE00000111112222233333',75,'2016-12-31 14:16:57','Happy New Year!',1),(17,'DE00000111112222233333','DE12345123451234512345',100,'2016-12-31 16:20:22','Wish you great New Year! :)',1),(18,NULL,'DE12345123451234512345',150,'2017-01-01 11:12:22','Deposit',1),(19,NULL,'DE00000111112222233333',250,'2017-01-05 21:17:02','Deposit',1),(20,'DE12345123451234512345',NULL,75,'2017-01-16 18:56:34','Withdraw',1),(21,'DE12345123451234512345','DE00000111112222233333',145,'2017-02-15 13:36:54','Wish you all the best',1),(22,NULL,'DE12345123451234512345',175,'2017-02-20 13:21:02','Deposit',1)", NULL, NULL);
    responseSend(TRUE, MSG_DBRESET, "tools", NULL);
}

function settingsUpdate ($vb_api, $sms_api, $nexmo_api_key, $nexmo_api_secret, $upload_path, $vb_otp) {
    sqlQuery("INSERT INTO settings (param_name, param_value) VALUES ('vb_api',?),('sms_api',?),('nexmo_api_key',?),('nexmo_api_secret',?),('upload_path',?),('vb_otp',?) ON DUPLICATE KEY UPDATE param_name=VALUES(param_name),param_value=VALUES(param_value);",
            array($vb_api, $sms_api, $nexmo_api_key, $nexmo_api_secret, $upload_path, (int)$vb_otp), NULL);
    responseSend(TRUE, MSG_SETTINGS_UPDATE, "tools", NULL);
}

?>
