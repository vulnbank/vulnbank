<?php
include("inc/db.php");
include("inc/common.php");
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$validation_template = array(
    "about" => array("regex" => "/^.+$/", "description" => "About"),
    "account" => array("regex" => "/^[A-Z]{2}[0-9]{20}$/", "description" => "Account"),
    //"action" => array("regex" => "/^.+$/", "description" => "Action"),
    "amount" => array("regex" => "/^[0-9\.-]+$/", "description" => "Amount"),
    "birthdate" => array("regex" => "/^[0-9]*-[0-9]*-[0-9]*$/", "description" => "Birthdate"),
    "code" => array("regex" => "/^[0-9]{3}$/", "description" => "Code"),
    //"comment" => array("regex" => "/.*/", "description" => "Comment"),
    "creditcard" => array("regex" => "/^[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{4}$/", "description" => "Credit Card"),
    "currentuser" => array("regex" => "/^[a-zA-Z0-9\.-_]*$/", "description" => "Current User"),
    "email" => array("regex" => "", "description" => "Email"),
    //"firstname" => array("regex" => "/.*/", "description" => "Firstname"),
    "id" => array("regex" => "/^[0-9]*$/", "description" => "ID"),
    "lastname" => array("regex" => "/^[a-zA-Z0-9\.-_']*$/", "description" => "Lastname"),
    "language" => array("regex" => "/^[a-z]*$/", "description" => "Language"),
    "login" => array("regex" => "/^[a-zA-Z0-9\.-_]*$/", "description" => "Login"),
    "nexmo_api_key" => array("regex" => "/^[a-zA-Z0-9]*$/", "description" => "Nexmo API key"),
    "nexmo_api_secret" => array("regex" => "/^[a-zA-Z0-9]*$/", "description" => "SMS API secret"),
    //"newpassword" => array("regex" => "/^.+$/", "description" => "New Password"),
    //"oldpassword" => array("regex" => "/^.+$/", "description" => "Old Password"),
    "otp" => array("regex" => "/^[0-9]*$/", "description" => "One Time Password"),
    //"password" => array("regex" => "/^.*$/", "description" => "Password"),
    "phone" => array("regex" => "/^[0-9]*$/", "description" => "Phone"),
    "recipient" => array("regex" => "/^[A-Z]{2}[0-9]{20}$/", "description" => "Recipient"),
    "role" => array("regex" => "/(user|admin)/", "description" => "Role"),
    "sender" => array("regex" => "/^[A-Z]{2}[0-9]{20}$/", "description" => "Sender"),
    "sms_api" => array("regex" => "/^[a-z]*$/", "description" => "SMS API type"),
    //"type" => array("regex" => "/^.+$/", "description" => "Type"),
    "username" => array("regex" => "/^[a-zA-Z0-9\.-_]*$/", "description" => "Username"),
    "upload_path" => array("regex" => "/^[a-zA-Z0-9\/:]*$/", "description" => "Upload path"),
    "vb_api" => array("regex" => "/^[a-z]*$/", "description" => "API type"),
    "vb_otp" => array("regex" => "/^[a-z0-9]*$/", "description" => "One Time Password (OTP)")
);

if (isset($_GET["xml"])) {
    libxml_disable_entity_loader(false);
    $xml = simplexml_load_string(file_get_contents('php://input'), "SimpleXMLElement", LIBXML_PARSEHUGE | LIBXML_NOENT | LIBXML_NOCDATA);
    $json = json_encode($xml);
    $decoded = json_decode($json, true);
    $args = [];
    foreach ($decoded as $key => $value) {
        if (empty($value)) {
            $args[$key] = "";
        } else {
            if ($key == "currentuser" && $value != $_SESSION["login"])
                responseSend(FALSE, "User mismatch", "user", NULL);
            $args[$key] = $value;
        }
    }
} elseif (isset($_GET["rest"])) {
    $args = json_decode(file_get_contents('php://input'), true);
} else {
    $args = $_POST;
}

if ($_FILES) {
    $args["type"] = "file";
    if ($_FILES["upload_avatar"]) {
        $args["action"] = "upload_avatar";
    }
}

foreach ($args as $key => $value) {
    if (isset($validation_template[$key])) {
        validate($validation_template[$key]["regex"], $value, $validation_template[$key]["description"]);
    }
}

if (!empty($_SESSION) && in_array("account", $_SESSION)) {
    $row = sqlQuery("SELECT * FROM users WHERE account=?", array($_SESSION["account"]), "one");
    $_SESSION["amount"] = $row["amount"];
}

if (!isset($args["type"])) responseSend(FALSE, sprintf(MSG_VALID_PARAM_FAIL, "type"), "user", array("variable" => ""));
if (!isset($args["action"])) responseSend(FALSE, sprintf(MSG_VALID_PARAM_FAIL, "action"), "user", array("variable" => ""));

switch ($args["type"]) {
    case "user":
        switch ($args["action"]) {
            case "login":
                userLogin($args["username"], $args["password"], (in_array("code",$args)) ? $args["code"] : "none");
                break;
            case "create":
                is_admin();
                userCreate($args["username"], $args["account"], $args["firstname"], $args["lastname"], $args["password"],
                           $args["email"], $args["phone"], $args["birthdate"], $args["creditcard"]);
                break;
            case "update":
                is_admin();
                userUpdate($args["id"], array("amount" => $args["amount"], "role" => $args["role"], "otp" => (int)$args["otp"]));
                break;
            case "infoupdate":
                userUpdate($_SESSION["id"], array("firstname" => $args["firstname"], "lastname" => $args["lastname"],
                           "birthdate" => $args["birthdate"], "email" => $args["email"],
                           "phone" => $args["phone"], "about" => $args["about"]));
                break;
            case "delete":
                is_admin();
                userDelete($args["id"]);
                break;
            case "forgotpass":
                userPasswordForgot($args["username"], $args["code"], $args["password"]);
                break;
            case "changepass":
                userPasswordChange($args["oldpassword"], $args["newpassword"]);
                break;
            case "check":
                if (isset($args["recipient"]) or isset($args["firstname"]) or isset($args["lastname"]))
                    userCheck($args["recipient"], $args["firstname"], $args["lastname"], $args["creditcard"]);
                break;
            case "statistics":
                graphGetData();
                break;
        }
    case "code":
        switch ($args["action"]) {
            case "sms":
                codeGenerate($args["username"], 1);
                break;
        }
    case "transaction":
        switch ($args["action"]) {
            case "send":
                transactionSend($args["sender"], $args["recipient"], $args["amount"], $args["comment"]);
                break;
            case "verify":
                transactionVerify($args["id"], ($args["code"]) ? $args["code"] : "none");
                break;
            case "clear":
                transactionRemoveFailed();
                break;
            case "cancel":
                transactionCancel();
                break;
        }
    case "status":
        switch ($args["action"]) {
            case "get":
                serviceState("http", "localhost", 80);
                break;
        }
    case "file":
        switch($args["action"]) {
            case "upload_avatar":
                fileUpload();
                break;
        }
    case "settings":
        switch($args["action"]) {
            case "changelocale":
                $_SESSION["language"] = $args["language"];
                responseSend(TRUE, "success", "flag", NULL);
                break;
            case "update":
                is_admin();
                settingsUpdate($args["vb_api"], ($args["sms_api"] == "true" ? 1 : 0), $args["nexmo_api_key"], $args["nexmo_api_secret"],
                               $args["upload_path"], ($args["vb_otp"] == "true" ? 1 : 0));
                break;
            case "resetdb":
                is_admin();
                dbReset();
                break;
        }
}
?>
