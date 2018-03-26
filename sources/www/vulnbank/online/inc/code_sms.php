<?php
if ($argv[1]) {
    include "db.php";
    $login = $argv[1];
    $sql = $db->prepare("SELECT * FROM users WHERE login=?");
    $sql->execute(array($login));
    $row = $sql->fetch();
    if ($row) {
        $phone = $row["phone"];
        $code = rand(100,999);
        echo(json_encode(array("status" => "success",
                                "message" => "Code changed",
                                "icon" => "pe-7s-user")));
        $sql = $db->prepare("UPDATE users SET code=? WHERE login=?");
        $sql->execute(array($code, $login));
        if ($SMS) {
            $url = 'https://rest.nexmo.com/sms/json?' . http_build_query(
                        array("api_key" => $API_KEY,
                            "api_secret" => $API_SECRET,
                            "to" => $phone,
                            "from" => "VulnBank",
                            "text" => 'Reset password code: '. $code));
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
        }
    } else {
        header(':', true, 400);
        die(json_encode(array("status" => "error",
                "message" => "Cannot find single user related to this username",
                "icon" => "pe-7s-user")));
    }
}
?>
