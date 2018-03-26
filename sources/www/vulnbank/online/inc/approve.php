<?php
if ($argv[1]) {
    include("common.php");
    $id = $argv[1];
    $transaction = sqlQuery("SELECT * FROM transactions WHERE id=?", array($id), "one");
    if ($transaction) {
        $from_user = sqlQuery("SELECT * FROM users where account=?", array($transaction["from_user"]), "one");
        $to_user = sqlQuery("SELECT * FROM users where account=?", array($transaction["to_user"]), "one");
        if ($to_user && $from_user) {
            sqlQuery("UPDATE users SET amount=? WHERE id=?",
                    array($to_user["amount"] + $transaction["amount"], $to_user["id"]), NULL);
            sqlQuery("UPDATE transactions SET approved=? WHERE id=?",
                    array(1, $transaction["id"]), NULL);
            if (otpCheck($from_user["login"])) {
                sleep(60);
                codeSend("nexmo", $from_user["phone"], "Transfered {$transaction["amount"]}$ to {$to_user["account"]}");
            }
        } else {
            sqlQuery("UPDATE users SET amount=? WHERE id=?",
                    array($from_user["amount"] + $transaction["amount"], $from_user["id"]), NULL);
        }
    }
}
?>
